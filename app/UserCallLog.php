<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class UserCallLog extends Model
{
    protected $table = "user_call_logs";

    public static function getCounterData($counterOBJ){
    	$counterOBJ->selectRaw("count(0) as total, LOWER(sub_category) as subcategory");
    	$counterOBJ->groupBy("sub_category");
    	$data = $counterOBJ->get()->toArray();
    	return $data;
    }

    public static function getTypeCounterData($counterOBJ){
    	$counterOBJ->selectRaw("count(0) as total, category");
    	$counterOBJ->groupBy("category");
    	$data = $counterOBJ->get()->toArray();
    	return $data;
    }

    public static function getLocationData($counterOBJ){

        $data = [];
        $counterOBJ->selectRaw("lat,lng,location");
        $rows = $counterOBJ
                ->whereRaw("lat IS NOT NULL AND lat != ''")
                ->groupBy("location")
                ->get()
                ->toArray();

        // $data[] = ['lat' => '29.974649','lng' => '-92.134293'];
        // $data[] = ['lat' => '45.465141','lng' => '-98.488068'];
        // $data[] = ['lat' => '32.448734','lng' => '-99.733147'];

        foreach($rows as $row){
            $data[] = ['lat' => $row->lat,'lng' => $row->lng];
        }

        // $data = array_unique($data);
        return $data;
    }    
    public static function getSubcategoryGraphData($counterOBJ){

        $counterOBJ2 = clone $counterOBJ;

        $returnCounterData = 
        [
            'inbound_counter' => [],
            'outbound_counter' => [],
            'connected_counter' => [],
            'missed_counter' => [],
            'rejected_counter' => [],
            'hangup_counter' => [],
            'noanswer_counter' => [],
            'busy_counter' => []                   
        ];        

        $counterOBJ->selectRaw("count(0) as total, LOWER(sub_category) as subcategory, DATE_FORMAT(start_time,'%Y-%m-%d') as dayDate");
        $counterOBJ->groupBy(\DB::raw("sub_category,dayDate"));
        $rows = $counterOBJ->get()->toArray();

        $dates = [];
        foreach($rows as $row)
        {
            $dates[] = $row['dayDate'];
        }

        $counterOBJ2->selectRaw("count(0) as total, category, DATE_FORMAT(start_time,'%Y-%m-%d') as dayDate");
        $counterOBJ2->groupBy(\DB::raw("category,dayDate"));
        $rows2 = $counterOBJ2->get()->toArray();
        foreach($rows2 as $row)
        {
            $dates[] = $row['dayDate'];
        }

        usort($dates, "\App\Custom::dateSort");

        foreach($rows as $dt)
        {
            if($dt['subcategory'] == 'call connected')
            {
                $returnCounterData['connected_counter'][$dt['dayDate']] = $dt['total'];
            }
            else if($dt['subcategory'] == 'missed')
            {
                $returnCounterData['missed_counter'][$dt['dayDate']] = $dt['total'];
            }
            else if($dt['subcategory'] == 'rejected')
            {
                $returnCounterData['rejected_counter'][$dt['dayDate']] = $dt['total'];
            }
            else if($dt['subcategory'] == 'hang up')
            {
                $returnCounterData['hangup_counter'][$dt['dayDate']] = $dt['total'];
            }
            else if($dt['subcategory'] == 'no answer')
            {
                $returnCounterData['noanswer_counter'][$dt['dayDate']] = $dt['total'];
            }
            else if($dt['subcategory'] == 'busy')
            {
                $returnCounterData['busy_counter'][$dt['dayDate']] = $dt['total'];
            }            
        }

        foreach($rows2 as $dt)
        {
            if($dt['category'] == 'Outbound')
            {
                $returnCounterData['outbound_counter'][$dt['dayDate']] = $dt['total'];
            }
            else if($dt['category'] == 'Inbound')
            {
                $returnCounterData['inbound_counter'][$dt['dayDate']] = $dt['total'];
            }
        }

        $data = [];

        foreach($dates as $date)
        {
            $data[] = 
            [
                $date, 
                isset($returnCounterData['inbound_counter'][$date]) ? $returnCounterData['inbound_counter'][$date]:0,
                isset($returnCounterData['outbound_counter'][$date]) ? $returnCounterData['outbound_counter'][$date]:0,
                isset($returnCounterData['connected_counter'][$date]) ? $returnCounterData['connected_counter'][$date]:0,
                isset($returnCounterData['missed_counter'][$date]) ? $returnCounterData['missed_counter'][$date]:0,
                isset($returnCounterData['rejected_counter'][$date]) ? $returnCounterData['rejected_counter'][$date]:0,
                isset($returnCounterData['hangup_counter'][$date]) ? $returnCounterData['hangup_counter'][$date]:0,
                isset($returnCounterData['noanswer_counter'][$date]) ? $returnCounterData['noanswer_counter'][$date]:0,
                isset($returnCounterData['busy_counter'][$date]) ? $returnCounterData['busy_counter'][$date]:0
            ];
        }

        return $data;        
    }
}
