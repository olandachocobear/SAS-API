<?php

define('SERVER_KEY', 'AAAADKcijEs:APA91bHX9VMNsAOpfiGmXHLzPUGU0dlCEZ04Ht4Ut3_B_psj4KccF3STqYYvRGW31j1Hf6LQB7e0jACawO3vt4KTmEGjxa0VeGqpLuMI1PBgWmhAJ926MNUFxf-8EhS0C-uZjFpinOGR');
define('APP_NAME', 'com.SAS.Workforce');

class FCM
{   
    private $recipients = [];
    public $result;

	private $headers = array( 'Authorization: key=' . SERVER_KEY,
							  'Content-Type: application/json');

	public function SendNotification($msg, $recipient_id)
	{
		if (is_array($recipient_id))
			$this->recipients = $recipient_id;
		else
			array_push ($this->recipients, $recipient_id);

		$payload = array (
			'registration_ids' 	=> $this->recipients,
			'data'	=> $msg
		);
		         
        return $this->SendToFCM($payload);
    }


	private function SendToFCM($params) 
	{
		$ch = curl_init();
		
		curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $this->headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $params ) );
		
		$result = curl_exec($ch);
		curl_close( $ch );
		
		return $result;
	}
}

?>