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

	public static function generatePassword($length = 8)
	{ 
		// inicializa variables 
		$password = ""; 
		$i = 0; 
		$possible = "0123456789abcdfghjkmnpqrstvwxyz";  

		// agrega random 
		while ($i < $length){ 
		    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1); 

		    if (!strstr($password, $char)) {  
		        $password .= $char; 
		        $i++; 
		    }
		} 
		return $password; 
	}
	public static function sendHtmlMail($params)
	{
	    $files = isset($params['files']) ? $params['files'] : array();

	    if(isset($params['from']))
	        $from = $params['from'];
	    else
	        $from = "reports.phpdots@gmail.com";

	    $params['from'] = $from;
	    
	    $toEmails[] = $params['to'];   

	    if(isset($params['ccEmails']))
	    {
	        foreach($params['ccEmails'] as $em)
	        {
	            $toEmails[] =  $em;
	        }
	    }
    
    	$params['to_emails'] = $toEmails;
    

	    \Mail::send('emails.index', $params, function($message) use ($params, $files) {
			
			$fromName = "RingCentral";
			if(isset($params['from_name']))
			{
				$fromName = $params['from_name'];
			}	

	        if(isset($params['from']))
	        {
	            $message->from($params['from'], $fromName);
	            $message->sender($params['from'], $fromName);
	        }

	        $message->to($params['to_emails'], '')->subject($params['subject']);

	        if (count($files) > 0) 
	        {
	            foreach ($files as $file) {
	                $message->attach($file['path']);
	            }
	        }
	    });

	    $dataToInsert = [
	            'to_email' => $params['to'],
	            'cc_emails' => '',
	            'bcc_emails' => '',
	            'from_email' => $from,
	            'email_subject' => $params['subject'],
	            'email_body' => $params['body'],
	            'mail_response' => '',
	            'status' => 1,
	            'ip_address' => '.ip',
	            'is_mandrill' => 1,
	            'created_at' => \DB::raw('NOW()'),
	            'updated_at' => \DB::raw('NOW()')
	        ];

	    \DB::table('email_sent_logs')->insert($dataToInsert);
	}
}