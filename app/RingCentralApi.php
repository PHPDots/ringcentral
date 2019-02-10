<?php
namespace App;
include app_path().DIRECTORY_SEPARATOR."apiSDK".DIRECTORY_SEPARATOR."vendor".DIRECTORY_SEPARATOR."autoload.php";

class RingCentralApi
{
    /*
    |--------------------------------------------------------------------------
    | RingCentralApi Model Class
    |--------------------------------------------------------------------------
    |
    | This class is responsible for handling for all the RingCentral Api operations    
    |
    */

	public $rcsdk;
	public $platform;

    public function __construct()
    {            	

        // OUR Test APP

        // $clientID = "CLp9k7v5QD-3if4bvljgyw";
        // $clientSecretKey = "2vxVWQ7QSLevAzmPpE0IBwq3KKbbOgRlCq9PQcRaSS2Q";
        // $username = "+12055060583";
        // $password = "Phpdots@2019";

        // Client SANDBOX APP
        $clientID = "fkFYLsaYT9Ce5MKof25hqg";
        $clientSecretKey = "ITSHiDRqS_6FSj8Uie939wsuA7CegbTNCc8muLpHHuNg";
        $username = "+12054793416";
        $password = "Apidashboard@2019";

        // Client SANDBOX APP 2
        // $clientID = "a73cGAA8QmyWsHSHOB_7Sg";
        // $clientSecretKey = "0wzVt1RvTCCoHqHQbXw3AQhHPAGu0eTSmqJMqh85yeWg";
        // $username = "+12054793416";
        // $password = "Apidashboard2019";
        // $this->rcsdk = new \RingCentral\SDK\SDK($clientID, $clientSecretKey, \RingCentral\SDK\SDK::SERVER_SANDBOX);

        // Client Live APP
		// $clientID = "gnP8jKdTQNutSfRHoxjJIA";
		// $clientSecretKey = "PVY1JNKvSQSuhhZJVdwh8AxsofiWB-TkmpUhRAmQxgRg";
		// $username = "al@westtexdisposal.com";
		// $password = "Apidashboard2019";
		// Login Call		
        // $this->rcsdk = new \RingCentral\SDK\SDK($clientID, $clientSecretKey, \RingCentral\SDK\SDK::SERVER_PRODUCTION);

        $clientID = "j8gJEfZeTbmL2N2GEb4C9g";
        $clientSecretKey = "WGvTR2LzRaaH0IDYzpaX3wip1N1pF3QCiLpjnpcjseUA";
        $username = "+14323812004";
        $password = "Apidashboard2019";

        // Login Call
        $this->rcsdk = new \RingCentral\SDK\SDK($clientID, $clientSecretKey, \RingCentral\SDK\SDK::SERVER_PRODUCTION);

		$this->platform = $this->rcsdk->platform();
		$this->platform->login($username, '', $password);
    }    

    /**
     * Get List Of Users
     * @param  mixed  $params
     * @return array
     */
    public function getAllUsers($params = [])
    {
        // $params['perPage'] = 10;
        $apiResponse = $this->platform->get('/account/~/extension',$params);
        $res = json_decode(json_encode($apiResponse->json()),1);
        return $res;
    }    

    /**
     * Get List Of Groups
     * @param  mixed  $params
     * @return array
     */
    public function getAllGroups($params = [])
    {
        // $params['perPage'] = 10;
        $apiResponse = $this->platform->get('/account/~/extension?type=Department',$params);
        $res = json_decode(json_encode($apiResponse->json()),1);
        return $res;
    }    

    /**
     * Get User Active Calls
     * @param  mixed  $params
     * @return array
     */
    public function getUserActiveCalls($params = [])
    {
        $params['perPage'] = 10;
        $apiResponse = $this->platform->get('/account/~/active-calls',$params);        
        $res = json_decode(json_encode($apiResponse->json()),1);
        return $res;
    }    


    /**
     * Get User Call Logs
     * @param  mixed  $value
     * @return array
     */
    public function getUserCallLogs($params = [])
    {
        $query = "";

        if(isset($params['isInbound']) && $params['isInbound'] == 1 && isset($params['isOutbound']) && $params['isOutbound'] == 1){
            $query = "?direction=Inbound&direction=Outbound";
            unset($params['isInbound']);
            unset($params['isOutbound']);
        }
        else if(isset($params['isInbound']) && $params['isInbound'] == 1)
        {
            $params['direction'] = "Inbound";
            unset($params['isInbound']);
        }
        else if(isset($params['isOutbound']) && $params['isOutbound'] == 1)
        {
            $params['direction'] = "Outbound";
            unset($params['isOutbound']);
        }
        else
        {
            // return [];
        }

		// $apiResponse = $this->platform->get('/account/~/extension/~/call-log'.$query,$params);
        // $apiResponse = $this->platform->get('/account/~/call-log'.$query,$params);
        $apiResponse = $this->platform->get('/account/~/call-log'.$query,$params);        
		$res = json_decode(json_encode($apiResponse->json()),1);
		return $res;        
    }
}