<?php

define ("TITLE_NEW_MSG", "Pesan baru dari Admin SAS");
define ("TITLE_NEW_SCHEDULE", "Jadwal Tes Rekrutment SAS");

class Push
{	

	private $conn, $Firebase;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;

		$this->Firebase = new FCM;
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

	public function new_msg_notif($msg_id) {
		try
		{
			$stmt = $this->conn->prepare("SELECT m.*,t.kode_barkode FROM tb_detail_push m INNER JOIN tb_push p on p.kd_push = m.kd_push INNER JOIN tb_karyawan k on k.no_KTP = p.kepada  LEFT JOIN tb_karyawan_mobile t ON t.id = k.id WHERE m.id = :kode");

			$stmt->execute(array(':kode'=>$msg_id));

			$result=$stmt->fetchAll(PDO::FETCH_ASSOC);

			// prepping Notif contents...
			$msg_payload = (object)[];
			$msg_payload->title = TITLE_NEW_MSG;

			//var_dump ($result);

			$msg_payload->message = $result[0]['pesan'];
			$msg_payload->notId = rand(1,1000);

			$target = $result[0]['kode_barkode'];

			return $this->Firebase->SendNotification($msg_payload, $target);
			 
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}


}	
?>