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
    
    public function getProcessIDsForRunningScript($filename_string)
    {
        $tmparr = array();

        ob_start();
        system(" ps ax|grep '".trim($filename_string)."'| grep -v 'grep' | awk '{print$1}'");
        $cmdoutput = ob_get_contents();
        ob_end_clean();

        $cmdoutput = trim($cmdoutput);
        if (empty($cmdoutput)) {
            return $tmparr;
        }

        $cmdoutput = preg_replace("#\n#", ",", trim($cmdoutput));
        $tmparr = explode(",", $cmdoutput);

        return $tmparr;
    }
    

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {        

        $tmp = $this->getProcessIDsForRunningScript("read:user-call-log");
        if(count($tmp) > 2)
        {
            exit("Script already running");
        }        
        
        $tableName = "user_call_logs";
        $startDate = date("Y-m-d\T00:00:00O",strtotime("-1 Year"));

        // $startDate = date("Y-m-d\T00:00:00O",strtotime("-2 day"));
        // $endDate = date("Y-m-d\T23:59:59O",strtotime("-1 day"));
        $startDate = date("Y-m-d\T00:00:00O",strtotime("-12 month"));
        $endDate = date("Y-m-d\T23:59:59O",strtotime("-9 month"));
        $startDate = date("Y-m-d\T00:00:00O",strtotime("-2 hour"));
        $endDate = date("Y-m-d\T23:59:59O");
        

        // $startDate = date("Y-m-d\T00:00:00O",strtotime("-2 Year"));
        // $endDate = date("Y-m-d\T00:00:00O",strtotime("-1 Year"));

        $perPage = 1000;
        $page = 1;        
        $apiObject = new RingCentralApi();


        $filterParams = [];
        $filterParams['view'] = "Detailed";
        $filterParams['perPage'] = $perPage;
        $filterParams['page'] = $page;
        $filterParams['dateFrom'] = $startDate;
        $filterParams['dateTo'] = $endDate;

        echo "\n $startDate => $endDate";

        // $callLogs = $apiObject->getUserCallLogs($filterParams);
        // print_r($callLogs);
        // exit;

        // $dt = $apiObject->getUserCallLogs($filterParams);
        // $dt = $apiObject->getAllGroups([]);
        // print_r($dt);
        // exit;

        $flag = true;
        $i = 0;
        while($flag)
        {
            echo "\nPage: $page";

            $callLogs = $apiObject->getUserCallLogs($filterParams);


            $records = isset($callLogs['records']) ? $callLogs['records']:[];

            // print_r($callLogs);
            // exit;

            foreach($records as $record)
            {
                    $isExist = \DB::table($tableName)
                               ->where("log_id",$record['id'])
                               ->count();

                    if($isExist > 0)
                    {
                        // skip insert operation
                    }           
                    else
                    {
                        $location = "";

                        if($record['direction'] == "Inbound"){
                            $location = isset($record['from']['location']) ? $record['from']['location']:"";
                        }
                        else{
                            $location = isset($record['to']['location']) ? $record['to']['location']:"";   
                        }

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
                            "location" => $location,
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

                        \DB::table($tableName)
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
