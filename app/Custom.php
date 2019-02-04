<?php
namespace App;

class Custom
{
    public static function dateSort($a, $b) {
        return strtotime($a) - strtotime($b);
    }
	
	public static function displayResultField($label){

		$label = trim(strtolower($label));

		if($label == "call connected")
		{
			$label = "Connected";
		}

		return ucwords($label);
	}

	public static function displayResultImage($label){

		$label = trim(strtolower($label));

		$image = asset("images/ic-incoming-call.png");

		if($label == "accepted")
		{
			$image = asset("images/ic-outgoing-call-1.png");
		}		
		else if($label == "missed")
		{
			$image = asset("images/ic-reject-call.png");
		}		
		else if($label == "rejected")
		{
			$image = asset("images/ic-black-list.png");
		}		

		return $image;
	}
}