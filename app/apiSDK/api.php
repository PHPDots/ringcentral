<?php
require('vendor/autoload.php');

$clientID = "CLp9k7v5QD-3if4bvljgyw";
$clientSecretKey = "2vxVWQ7QSLevAzmPpE0IBwq3KKbbOgRlCq9PQcRaSS2Q";
$username = "+12055060583";
$password = "Phpdots@2019";

$clientID = "fkFYLsaYT9Ce5MKof25hqg";
$clientSecretKey = "ITSHiDRqS_6FSj8Uie939wsuA7CegbTNCc8muLpHHuNg";
$username = "+12054793416";
$password = "Apidashboard@2019";

$rcsdk = new RingCentral\SDK\SDK($clientID, $clientSecretKey, RingCentral\SDK\SDK::SERVER_SANDBOX);
$rcsdk->platform()->login($username, '', $password);

$params = 
[
	// "dateFrom" => "2019-01-01T00:00:00.000Z",
	// "dateTo" => "2019-01-31T23:59:59.000Z",
];

$params = [
	"firstName" => "John",
	"lastName" => "Smith",
	"mobilePhone" => "1234567890",	
];

$apiResponse = $rcsdk->platform()->get('/account/~/extension/~/address-book/contact',[]);
$res = json_decode(json_encode($apiResponse->json()),1);
print_r($res);
exit;

$apiResponse = $rcsdk->platform()->post("/account/~/extension/~/address-book/contact",$params);
$res = json_decode(json_encode($apiResponse->json()),1);
print_r($res);
exit;

$apiResponse = $rcsdk->platform()->get('/account/~/extension/~/call-log',$params);
$res = json_decode(json_encode($apiResponse->json()),1);
print_r($res);
exit;