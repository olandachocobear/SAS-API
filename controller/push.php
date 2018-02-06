<?php

define ("TITLE_NEW_MSG", "Pesan baru dari Admin SAS");
define ("TITLE_NEW_SCHEDULE", "Jadwal baru dari Admin SAS");
define ("ICON_NEW_MSG", "chats");
define ("ICON_NEW_SCHEDULE", "schedule");

class Push
{	

	private $conn, $Firebase, $soundColl;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;

		$this->Firebase = new FCM;

		$this->soundColl = Array(
				'pikachu' => 'pikapikasound',
				'bell' => 'bellsound',
				'ohyeah' => 'koolaidsound',
				'mario' => 'mushroomsound',
				'moan' => 'moansound'
		);
    }
	
	public function register($u,$t,$d)
	{
		try
		{
			$stmt = $this->conn->prepare("INSERT INTO tb_karyawan_mobile (id, create_date, kode_barkode, password) VALUES((select id from tb_karyawan where email=:uname), NOW(), :token, :device) ON DUPLICATE KEY UPDATE create_date=NOW(), kode_barkode=:token, password=:device");

			$stmt->execute(array(':uname'=>$u, ':token'=>$t, ':device'=>$d));

			$response = (object)[];
			$response->status = 200;
			return $response;
			 
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}

	}

	public function msg_notif($msg_id, $img=null, $sound=null) {
		try
		{
			$stmt = $this->conn->prepare("SELECT m.*,t.kode_barkode, s.nama_subject as subjek FROM tb_detail_push m INNER JOIN tb_push p on p.kd_push = m.kd_push INNER JOIN tb_subject_push s ON s.kd_subject=p.subject INNER JOIN tb_karyawan k on k.no_KTP = p.kepada  LEFT JOIN tb_karyawan_mobile t ON t.id = k.id WHERE m.kd_push = :kode");

			$stmt->execute(array(':kode'=>$msg_id));

			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);

			// prepping standard Notif contents...
			$msg_payload = (object)[];
			$msg_payload->title = TITLE_NEW_MSG;
			$msg_payload->message = $result[0]['subjek'];
			$msg_payload->notId = rand(1,1000);

			// Set icons
			if (isset($img))
				$msg_payload->image = $img;
			else
				$msg_payload->image = ICON_NEW_MSG;

			// Set sound
			if (isset($sound))
				$msg_payload->soundname = $this->getSound($sound); // changeable!!

			$target = $result[0]['kode_barkode'];

			return $this->Firebase->SendNotification($msg_payload, $target);
			 
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function interview_notif($intrvw_id, $img=null, $sound=null) {
		try
		{
			$stmt = $this->conn->prepare("SELECT i.*,m.kode_barkode FROM tb_info_interview i INNER JOIN tb_karyawan k on k.no_ktp = i.no_ktp INNER JOIN tb_karyawan_mobile m ON m.id = k.id WHERE kd_interview = :kode");

			$stmt->execute(array(':kode'=>$intrvw_id));

			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);

			// prepping standard Notif contents...
			$msg_payload = (object)[];
			$msg_payload->title = TITLE_NEW_SCHEDULE;
			$msg_payload->message = 'Interview, Tgl: ' . $result[0]['date_interview'];
			$msg_payload->notId = rand(1,1000);

			// additional data specific to schedule
			$msg_payload->notif_type = 'schedule';
			$msg_payload->schedule_type = 'interview';
			$msg_payload->schedule_note = $result[0]['detail'];
			$msg_payload->schedule_date = $result[0]['date_interview'];
			$msg_payload->schedule_id = $result[0]['kd_interview'];

			// Set icons
			if (isset($img))
				$msg_payload->image = $img;
			else
				$msg_payload->image = ICON_NEW_SCHEDULE;

			// Set sound
			if (isset($sound))
				$msg_payload->soundname = $this->getSound($sound); // changeable!!

			$target = $result[0]['kode_barkode'];

			return $this->Firebase->SendNotification($msg_payload, $target);
			 
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function test_notif($test_id, $img=null, $sound=null) {
		try
		{
			$stmt = $this->conn->prepare("SELECT t.*,m.kode_barkode FROM tb_info_test t INNER JOIN tb_karyawan k on k.no_ktp = t.no_ktp INNER JOIN tb_karyawan_mobile m ON m.id = k.id WHERE kode_test = :kode");

			$stmt->execute(array(':kode'=>$test_id));

			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);

			// prepping standard Notif contents...
			$msg_payload = (object)[];
			$msg_payload->title = TITLE_NEW_SCHEDULE;
			$msg_payload->message = 'Test, Tgl: ' . $result[0]['date_test'];
			$msg_payload->notId = rand(1,1000);

			// additional data specific to schedule
			$msg_payload->notif_type = 'schedule';
			$msg_payload->schedule_type = 'test';
			$msg_payload->schedule_note = $result[0]['keterangan'];
			$msg_payload->schedule_date = $result[0]['date_test'];
			$msg_payload->schedule_id = $result[0]['kode_test'];

			// Set icons
			if (isset($img))
				$msg_payload->image = $img;
			else
				$msg_payload->image = ICON_NEW_SCHEDULE;

			// Set sound
			if (isset($sound))
				$msg_payload->soundname = $this->getSound($sound); // changeable!!

			$target = $result[0]['kode_barkode'];

			return $this->Firebase->SendNotification($msg_payload, $target);
			 
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function interview_notif_w_actions($intrvw_id, $img=null, $sound=null) {
		try
		{
			$stmt = $this->conn->prepare("SELECT i.*,m.kode_barkode FROM tb_info_interview i INNER JOIN tb_karyawan k on k.no_ktp = i.no_ktp INNER JOIN tb_karyawan_mobile m ON m.id = k.id WHERE kd_interview = :kode");

			$stmt->execute(array(':kode'=>$intrvw_id));

			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);

			// prepping standard Notif contents...
			$msg_payload = (object)[];
			$msg_payload->title = TITLE_NEW_SCHEDULE;
			$msg_payload->message = 'Interview, Tgl: ' . $result[0]['date_interview'];
			$msg_payload->message .= " Harap konfirm Jam ketersediaan di bawah:";
			$msg_payload->notId = rand(1,1000);

			// additional data specific to schedule
			$msg_payload->notif_type = 'schedule';
			$msg_payload->schedule_type = 'interview';
			$msg_payload->schedule_note = $result[0]['detail'];
			$msg_payload->schedule_date = $result[0]['date_interview'];
			$msg_payload->schedule_id = $result[0]['kd_interview'];

			// Set Actions 
			$actions = json_decode( '[{
					
					"title": "Jam 10 Pagi",
					"callback": "confirm10AM",
					"foreground": true
				},{
					
					"title": "Jam 2 Siang",
					"callback": "confirm14PM",
					"foreground": true
				},{
					
					"title": "Hubungi Admin",
					"callback": "contactAdmin",
					"foreground": false
				}]
			');

			$msg_payload->actions = $actions;

			// required to always put on top & show Action options on notification drawer..
			$msg_payload->priority = 2;

			// Set icons
			if (isset($img))
				$msg_payload->image = $img;
			else
				$msg_payload->image = ICON_NEW_SCHEDULE;

			// Set sound
			if (isset($sound))
				$msg_payload->soundname = $this->getSound($sound); // changeable!!

			$target = $result[0]['kode_barkode'];

			return $this->Firebase->SendNotification($msg_payload, $target);
			 
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function test_notif_w_actions($test_id, $img=null, $sound=null) {
		try
		{
			$stmt = $this->conn->prepare("SELECT t.*,m.kode_barkode FROM tb_info_test t INNER JOIN tb_karyawan k on k.no_ktp = t.no_ktp INNER JOIN tb_karyawan_mobile m ON m.id = k.id WHERE kode_test = :kode");

			$stmt->execute(array(':kode'=>$test_id));

			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);

			// prepping standard Notif contents...
			$msg_payload = (object)[];
			$msg_payload->title = TITLE_NEW_SCHEDULE;
			$msg_payload->message = 'Test, Tgl: ' . $result[0]['date_test'];
			$msg_payload->message .= " Harap konfirm Jam ketersediaan di bawah:";
			$msg_payload->notId = rand(1,1000);

			// additional data specific to schedule
			$msg_payload->notif_type = 'schedule';
			$msg_payload->schedule_type = 'test';
			$msg_payload->schedule_note = $result[0]['keterangan'];
			$msg_payload->schedule_date = $result[0]['date_test'];
			$msg_payload->schedule_id = $result[0]['kode_test'];

			// Set Actions 
			$actions = json_decode( '[{
					
					"title": "Jam 10 Pagi",
					"callback": "confirm10AM",
					"foreground": true
				},{
					
					"title": "Jam 2 Siang",
					"callback": "confirm14PM",
					"foreground": true
				},{
					
					"title": "Hubungi Admin",
					"callback": "contactAdmin",
					"foreground": false
				}]
			');

			$msg_payload->actions = $actions;

			// required to always put on top & show Action options on notification drawer..
			$msg_payload->priority = 2;

			// Set icons
			if (isset($img))
				$msg_payload->image = $img;
			else
				$msg_payload->image = ICON_NEW_SCHEDULE;

			// Set sound
			if (isset($sound))
				$msg_payload->soundname = $this->getSound($sound); // changeable!!

			$target = $result[0]['kode_barkode'];

			// debug!
			echo json_encode( $msg_payload);

			return $this->Firebase->SendNotification($msg_payload, $target);
			 
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	private function getSound($what){
		return $this->soundColl[$what];
	}
}	
?>