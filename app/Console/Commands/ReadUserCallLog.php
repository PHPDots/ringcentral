<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\RingCentralApi;

class ReadUserCallLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'read:user-call-log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read User Call Logs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {        
        $startDate = date("Y-m-d\T00:00:00O",strtotime("-1 Year"));
        $endDate = date("Y-m-d\T23:59:59O");

        $perPage = 100;
        $page = 1;
        $apiObject = new RingCentralApi();

        $filterParams = [];
        $filterParams['view'] = "Detailed";
        $filterParams['perPage'] = $perPage;
        $filterParams['page'] = $page;
        $filterParams['dateFrom'] = $startDate;
        $filterParams['dateTo'] = $endDate;

        echo "\n $startDate => $endDate";

        $flag = true;
        $i = 0;
        while($flag)
        {
            echo "\nPage: $page";
            $callLogs = $apiObject->getUserCallLogs($filterParams);
            $records = isset($callLogs['records']) ? $callLogs['records']:[];

            foreach($records as $record)
            {
                    $isExist = \DB::table("user_call_logs")
                               ->where("log_id",$record['id'])
                               ->count();

                    if($isExist > 0)
                    {
                        // skip insert operation
                    }           
                    else
                    {
                        $dataToInsert = 
                        [
                            "log_id" => $record['id'],
                            "type" => $record['type'],                            
                            "category" => $record['direction'],
                            "action" => $record['action'],
                            "start_time" => date("Y-m-d H:i:s",strtotime($record['startTime'])),
                            "extention" => isset($record['extension']['id']) ? $record['extension']['id']:"",
                            "transport" => isset($record['transport']) ? $record['transport']:"-",
                            "result" => $record['result'],
                            "sub_category" => $record['result'],
                            "duration" => isset($record['duration']) ? $record['duration']:"",
                            "from_name" => isset($record['from']['name']) ? $record['from']['name']:"",
                            "to_name" => isset($record['to']['name']) ? $record['to']['name']:"",
                            "from_number" => isset($record['from']['phoneNumber']) ? $record['from']['phoneNumber']:"",
                            "to_number" => isset($record['to']['phoneNumber']) ? $record['to']['phoneNumber']:"", 
                            "from_location" => isset($record['from']['location']) ? $record['from']['location']:"", 
                            "to_location" => isset($record['to']['location']) ? $record['to']['location']:"", 
                            "is_recording" => isset($record['recording']) ? 1:0,
                            "forward_to" => isset($record['legs'][0]['phoneNumber']) ? $record['legs'][0]['phoneNumber']:"",
                            "apiData" => json_encode($record),
                            "created_at" => date("Y-m-d H:i:s")
                        ];

                        \DB::table("user_call_logs")
                        ->insert($dataToInsert);
                    }
                    $i++;
                    echo "\n$i";
            }

            if(isset($callLogs['navigation']['nextPage']['uri']))
            {
               $page++;     
               $filterParams['page'] = $page;
            }
            else
            {
                $flag = false;
            }
        }        

        $this->info("\nCommand has been run!\n");
    }
}
