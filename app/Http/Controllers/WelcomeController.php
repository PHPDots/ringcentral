<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\RingCentralApi;
use App\UserCallLog;
use App\User;

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

		// Get Request Parameters
		$page = $request->get("page",1);
		$filterDate = $request->get("filterDate");
		$filterType = $request->get("filterType");
		$isInbound = $request->get("isInbound",0);
		$isOutbound = $request->get("isOutbound",0);
		$filter_sub_category = $request->get("filter_sub_category");		

		$dateFrom = date("Y-m-d 00:00:00");
		$dateTo = date("Y-m-d 23:59:59");


		// Filter By Date
		if(!empty($filterDate)){
			$filterDates = explode("/", $filterDate);			
			if(count($filterDates) == 2){
				$dateFrom = date("Y-m-d 00:00:00",strtotime($filterDates[0]));
				$dateTo = date("Y-m-d 23:59:59",strtotime($filterDates[1]));				
			}
		}	
		$model = UserCallLog::whereBetween("start_time",[$dateFrom, $dateTo]);		

		// Filter By Type
		if(!empty($filterType))
		{
			$model->where("type", $filterType);
		}

		// Filter By Call Types/Result
		$subCategories = [];
		if(isset($filter_sub_category['connected']) && $filter_sub_category['connected'] == 1){
			$subCategories[] = "call connected";
		}
		if(isset($filter_sub_category['missed']) && $filter_sub_category['missed'] == 1){
			$subCategories[] = "missed";
		}
		if(isset($filter_sub_category['rejected']) && $filter_sub_category['rejected'] == 1){
			$subCategories[] = "rejected";
		}
		if(isset($filter_sub_category['hang_up']) && $filter_sub_category['hang_up'] == 1){
			$subCategories[] = "hang up";
		}
		if(isset($filter_sub_category['no_answer']) && $filter_sub_category['no_answer'] == 1){
			$subCategories[] = "no answer";
		}
		if(isset($filter_sub_category['busy']) && $filter_sub_category['busy'] == 1){
			$subCategories[] = "busy";
		}		
		if(count($subCategories) > 0){
			$model->whereIn("sub_category", $subCategories);
		}

		$categories = [];
		if($isInbound == 1){
			$categories[] = "Inbound";
		}
		if($isOutbound == 1){
			$categories[] = "Outbound";
		}		
		if(count($categories) > 0){
			$model->whereIn("category", $categories);
		}		

		$returnCounterData = 
		[
			'connected_counter' => 0,
			'missed_counter' => 0,
			'rejected_counter' => 0,
			'hangup_counter' => 0,
			'noanswer_counter' => 0,
			'busy_counter' => 0,
			'inbound_counter' => 0,
			'outbound_counter' => 0,
		];

		$counterOBJ = clone $model;
		$counterOBJ1 = clone $model;
		$counterOBJ2 = clone $model;

		$model = $model->paginate($perPage);

		$counterData = UserCallLog::getCounterData($counterOBJ);
		foreach($counterData as $dt)
		{
			if($dt['subcategory'] == 'call connected')
			{
				$returnCounterData['connected_counter'] = $dt['total'];
			}
			else if($dt['subcategory'] == 'missed')
			{
				$returnCounterData['missed_counter'] = $dt['total'];
			}
			else if($dt['subcategory'] == 'rejected')
			{
				$returnCounterData['rejected_counter'] = $dt['total'];
			}
			else if($dt['subcategory'] == 'hang up')
			{
				$returnCounterData['hangup_counter'] = $dt['total'];
			}
			else if($dt['subcategory'] == 'no answer')
			{
				$returnCounterData['noanswer_counter'] = $dt['total'];
			}
			else if($dt['subcategory'] == 'busy')
			{
				$returnCounterData['busy_counter'] = $dt['total'];
			}
		}

		$typeCounterData = UserCallLog::getTypeCounterData($counterOBJ1);
		foreach($typeCounterData as $dt){
			if($dt['category'] == 'Outbound'){
				$returnCounterData['outbound_counter'] = $dt['total'];
			}
			elseif($dt['category'] == 'Inbound'){
				$returnCounterData['inbound_counter'] = $dt['total'];
			}
		}

		

		$callsData = [];
		foreach($model as $row)
		{
			$callsData[] = 
			[
				"date" => date("Y-m-d H:i:s",strtotime($row->start_time)),
				"extension" => !empty($row->extention) ? $row->extention:"-",
				"result" => \App\Custom::displayResultField($row->result),
				"image" => \App\Custom::displayResultImage($row->result),
				"duration" => gmdate("H:i:s", $row->duration),
				"from_name" => $row->from_name,
				"to_name" => $row->to_name,
				"from_no" => $row->from_number,
				"to_no" => $row->to_number, 
				"recording" => $row->is_recording ? "Yes":"No",
				"forward_to" => !empty($row->forward_to) ? $row->forward_to:"-",
			];
		}

		$graph_data = UserCallLog::getSubcategoryGraphData($counterOBJ2);

		// $graph_data = 
		// [
		// 	["2019-02-01", 10,20,30,40,50,60,0,80],
		// 	["2019-02-02", 20,40,60,80,100,120,140,180],
		// 	["2019-02-03", 30,60,80,90,65,70,110,45],
		// 	["2019-01-29", 60,20,0,0,75,90,0,80],
		// ];

		$data['gridHtml'] = view("includes.gridRows",["rows" => $callsData])->render();
		$data['paginationHTML'] = view("includes.gridRows",["model" => $model,"only_pagination" => 1])->render();
		$data['counter_data'] = $returnCounterData;
		$data['graph_data'] = $graph_data;
		return ['status' => $status, 'msg' => $msg, 'data' => $data];
	}	 

    /**
     * Get Active Calls
     * @param  $request
     * @return array
     */
    public function getActiveCalls(Request $request){

    	$apiObject = new RingCentralApi();
    	$filterParams = ['view' => 'Detailed'];
    	$callLogs = $apiObject->getUserActiveCalls($filterParams);
    	$records = isset($callLogs['records']) ? $callLogs['records']:[];
    	$callsData = [];

    	// dd($records);

		foreach($records as $record)
		{
			$row = 
			[
				"date" => date("Y-m-d H:i:s",strtotime($record['startTime'])),
				"extension" => isset($record['extension']['id']) ? $record['extension']['id']:"-",
				"result" => $record['result'],
				"from_name" => isset($record['from']['name']) ? $record['from']['name']:"",
				"to_name" => isset($record['to']['name']) ? $record['to']['name']:"",
				"to_number" => isset($record['to']['phoneNumber']) ? $record['to']['phoneNumber']:"",
			];

			$callsData[] = $row;
		}

    	return view("includes.activeCalls",["rows" => $callsData]);
    }

    public function profile()
    {
    	$data = array();
    	$data['authUser'] = \Auth::user();
    	return view('profile',$data);
    }

    public function updateProfile(Request $request)
    {
    	$status = 1;
    	$msg = 'Profile has been updated successfully!';

    	$authUser = \Auth::user();
    	$user = User::find($authUser->id);
    	if(!$user){
    		$status = 0;
    		$msg = 'User not found';
    	}
    	$email = $request->get('email');
        $name = $request->get('name');

    	$rquestData = $request->all();
    	$rquestData['email'] = trim($email);
    	$rquestData['name'] = trim($name);

        // check validations
    	$validator = Validator::make($rquestData, [
            'name' => 'required|min:2',            
            'email' => 'required|email|unique:users,email,'.$authUser->id, 
            'image' => 'image|max:4000',
        ]);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            
            $status = 0;
            $msg = "";
            
            foreach ($messages->all() as $message) 
            {
                $msg .= $message . "<br />";
            }
        }else{
        	
        	$image = $request->file('image');

        	$user->name = $name;
        	$user->email = $email;
        	$user->save();

        	if(!empty($image))
            {
                $destinationPath = public_path().DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'users';

                $image_name =$image->getClientOriginalName();              
                $extension =$image->getClientOriginalExtension();
                $image_name=md5($image_name);
                $profile_image= $image_name.'.'.$extension;
                $file =$image->move($destinationPath,$profile_image);
                
                $user->image = $profile_image;
                $user->save();
            }
        }
        return ['status' => $status, 'msg' => $msg];
    }

    public function changePassword(Request $request)
    {
    	$status = 1;
    	$msg = 'Password has been changed successfully!';

    	$authUser = \Auth::user();
    	$user = User::find($authUser->id);
    	if(!$user){
    		$status = 0;
    		$msg = 'User not found';
    	}

        // check validations
    	$validator = Validator::make($request->all(), [
            'password_current' => 'required',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);
        if ($validator->fails())
        {
            $messages = $validator->messages();
            
            $status = 0;
            $msg = "";
            
            foreach ($messages->all() as $message) 
            {
                $msg .= $message . "<br />";
            }
        }else{
        	
        	$password_current = $request->get('password_current');
        	$password = $request->get('password');
        	if(Hash::check($password_current, $user->password))
        	{
	        	$user->password = bcrypt($password);
	        	$user->save();
        	}else
            {
                $status = 0;
                $msg = 'Current password is incorrect.';
            }

        }
        return ['status' => $status, 'msg' => $msg];
    }
}