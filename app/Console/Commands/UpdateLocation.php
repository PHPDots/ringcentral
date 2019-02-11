<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\RingCentralApi;

class UpdateLocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-location';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Lat Long';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    // function to get  the address
    public function get_lat_long($address){

        $address = str_replace(" ", "+", $address);

        $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=$address&sensor=false");
        $json = json_decode($json);

        $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
        return ['lat' => $lat, 'long' => $long];
    }    

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {                
        $tableName = "user_call_logs";
        $limit = 500;
        $i = 0;
        $rows = \DB::table("user_call_logs")
                ->whereRaw("(location IS NOT NULL OR TRIM(location) != '') AND (lat IS NULL OR TRIM(lat) = '')")
                ->limit($limit)
                ->get();

        foreach($rows as $row)
        {
            $location = $row->location;
            $id = $row->id;
            $lat = "";
            $lng = "";

            // Get Lat Long
            $record = \DB::table("locations")
                      ->where("location",$location)
                      ->first();
            
            if($record)
            {
                $lat = $record->lat;
                $lng = $record->lng;
            }
            else
            {
                // $data = $this->get_lat_long($location);
                // $lat = $data['lat'];
                // $lng = $data['lng'];
                // \DB::table("locations")
                // ->insert([
                //     "location" => $location,
                //     "lat" => $lat,
                //     "lng" => $lng
                // ]);                
            }          

            // update record
            \DB::table("user_call_logs")
            ->where("id",$id)
            ->update(["lat" => $lat, "lng" => $lng]);
            $i++;

            echo "\n $i";
        }        

        $this->info("\nCommand has been run!\n");
    }
}
