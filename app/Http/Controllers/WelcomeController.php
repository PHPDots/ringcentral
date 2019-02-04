<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use App\RingCentralApi;
use App\UserCallLog;

class WelcomeController extends Controller
{
    public function __construct()
    {

    }

    function index(Request $request)
    {
        $data = [];
        return view('home',$data);
    }

    /**
     * Get ApI Data
     * @param  $request
     * @return array
     */
    public function getApiData(Request $request)
	{
		$status = 1;
		$msg = "OK";
		$data = [];

		$perPage = 10;
		$page = $request->get("page",1);		
		$filterDate = $request->get("filterDate");
		$filterType = $request->get("filterType");

		$dateFrom = date("Y-m-d 00:00:00");
		$dateTo = date("Y-m-d 23:59:59");

		if(!empty($filterDate)){
			$filterDates = explode("/", $filterDate);			
			if(count($filterDates) == 2){
				$dateFrom = date("Y-m-d 00:00:00",strtotime($filterDates[0]));
				$dateTo = date("Y-m-d 23:59:59",strtotime($filterDates[1]));				
			}
		}	

		$model = UserCallLog::whereBetween("start_time",[$dateFrom, $dateTo]);		

		if(!empty($filterType))
		{
			$model->where("type", $filterType);
		}

		$model = $model->paginate($perPage);

		$callsData = [];

		foreach($model as $row)
		{
			$callsData[] = 
			[
				"date" => date("Y-m-d H:i:s",strtotime($row->start_time)),
				"extension" => !empty($row->extention) ? $row->extention:"-",
				"result" => $row->result,
				"duration" => $row->duration,
				"from_name" => $row->from_name,
				"to_name" => $row->to_name,
				"from_no" => $row->from_number,
				"to_no" => $row->to_number, 
				"recording" => $row->is_recording ? "Yes":"No",
				"forward_to" => !empty($row->forward_to) ? $row->forward_to:"-"
			];
		}

		$data['gridHtml'] = view("includes.gridRows",["rows" => $callsData])->render();
		$data['paginationHTML'] = view("includes.gridRows",["model" => $model,"only_pagination" => 1])->render();

		return ['status' => $status, 'msg' => $msg, 'data' => $data];
	}    

    /**
     * Get Active Calls
     * @param  $request
     * @return array
     */
    public function getActiveCalls(Request $request){

    	$apiObject = new RingCentralApi();
    	$callLogs = $apiObject->getUserActiveCalls($filterParams);
    	$records = isset($callLogs['records']) ? $callLogs['records']:[];
    	$callsData = [];

		foreach($records as $record)
		{
			$row = 
			[
				"date" => date("Y-m-d H:i:s",strtotime($record['startTime'])),
				"extension" => isset($record['extension']['id']) ? $record['extension']['id']:"-",
				"result" => $record['result'],				
				"from_name" => isset($record['from']['name']) ? $record['from']['name']:"",
			];

			$callsData[] = $row;
		}


    	return view("includes.activeCalls",["rows" => $callsData])->render();
    }	
}