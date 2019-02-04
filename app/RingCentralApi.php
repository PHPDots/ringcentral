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
		$clientID = "fkFYLsaYT9Ce5MKof25hqg";
		$clientSecretKey = "ITSHiDRqS_6FSj8Uie939wsuA7CegbTNCc8muLpHHuNg";
		$username = "+12054793416";
		$password = "Apidashboard@2019";

		// $clientID = "CLp9k7v5QD-3if4bvljgyw";
		// $clientSecretKey = "2vxVWQ7QSLevAzmPpE0IBwq3KKbbOgRlCq9PQcRaSS2Q";
		// $username = "+12055060583";
		// $password = "Phpdots@2019";

		// Login Call
		$this->rcsdk = new \RingCentral\SDK\SDK($clientID, $clientSecretKey, \RingCentral\SDK\SDK::SERVER_SANDBOX);
		$this->platform = $this->rcsdk->platform();
		$this->platform->login($username, '', $password);
    }
    

    /**
     * Get User Active Calls
     * @param  mixed  $value
     * @return array
     */
    public function getUserActiveCalls($params = [])
    {
        $params['perPage'] = 10;
        $apiResponse = $this->platform->get('/account/~/extension/~/active-calls',$params);
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

		$apiResponse = $this->platform->get('/account/~/extension/~/call-log'.$query,$params);
		$res = json_decode(json_encode($apiResponse->json()),1);
		return $res;
    }
}