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

        print_r($groups);
        exit;
        
        $this->info("\nCommand has been run!\n");
    }
}
