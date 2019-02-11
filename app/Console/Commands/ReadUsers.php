<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\RingCentralApi;

class ReadUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'read:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read Users From Ring Central';

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
        $perPage = 1000;
        $page = 1;
        $apiObject = new RingCentralApi();

        $filterParams = [];
        $filterParams['view'] = "Detailed";
        $filterParams['perPage'] = $perPage;        

        $users = $apiObject->getAllUsers($filterParams);
        // print_r($users);exit;

        $i = 0;
        if(isset($users['records']))
        {
            foreach($users['records'] as $user)
            {               
                $id = $user['id'];
                $extensionNumber = isset($user['extensionNumber']) ? $user['extensionNumber']:NULL;
                $type = $user['type'];
                $name = isset($user['name']) ? $user['name']:"";
                $firstName = isset($user['contact']['firstName']) ? $user['contact']['firstName']:"";
                $lastName = isset($user['contact']['lastName']) ? $user['contact']['lastName']:"";
                $company = isset($user['contact']['company']) ? $user['contact']['company']:"";
                $email = isset($user['contact']['email']) ? $user['contact']['email']:"";
                $businessPhone = isset($user['contact']['businessPhone']) ? $user['contact']['businessPhone']:""; 

                $userOBJ = \DB::table("ringcentral_users")                
                ->where("api_id", $id)
                ->first();

                if(!$userOBJ)
                {
                    \DB::table("ringcentral_users")
                    ->insert
                    (
                        [
                            "api_id" => $id,
                            "extension" => $extensionNumber,
                            "firstname" => $firstName,
                            "lastname" => $lastName,
                            "name" => $name,
                            "company" => $company,
                            "email" => $email,
                            "phone" => $businessPhone,
                            "email" => $email,
                            "user_type" => $type,
                            "data" => json_encode($user),
                            "created_at" => date("Y-m-d H:i:s")
                        ]
                    );
                }

                $i++;
                echo "\n$i processed!";
            }
        }

        // print_r($users);
        // exit;

        $this->info("\nCommand has been run!\n");
    }
}
