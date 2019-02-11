<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\RingCentralApi;

class ReadUserGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'read:user-groups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read User Groups From Ring Central';

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
        $filterParams['perPage'] = $perPage;                
        $groups = $apiObject->getAllGroups($filterParams);

        if(isset($groups['records']))
        {
            foreach($groups['records'] as $group)
            {                
                $groupID = $group['id'];

                echo "\n Group: $groupID";

                $userOBJ = \DB::table("ringcentral_users")
                            ->where("api_id", $groupID)
                            ->first();

                if($userOBJ)
                {
                    $users = $apiObject->getGroupUsers($groupID);                
                    if(isset($users['records']))
                    {
                        foreach($users['records'] as $user)
                        {
                            $extensionNumber = $user['extensionNumber'];
                            echo "\n User: $extensionNumber";
                            \DB::table("ringcentral_users")
                            ->where("extension", $extensionNumber)
                            ->update(['parent_id' => $userOBJ->id]);
                        }
                    }
                }            
            }
        }
        
        $this->info("\nCommand has been run!\n");
    }
}
