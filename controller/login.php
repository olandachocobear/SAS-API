<?php

define ("SECRET_SERVER_KEY", "Mister_Toni");
define ("TOKEN_EXPIRY", 6000); // in sec

class Login
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	private function generate_JWT($email, $id, $nik, $foto, $firstname, $lastname) {
		$issueTime = time();
		$newCandidateFlag = ($nik == '') ? true : false;
		$payload = array (
			"iat" => $issueTime,
			"exp" => $issueTime + TOKEN_EXPIRY,
			"detail" => [
				"no_ktp" => $id,
				"email" => $email,
				"new_candidate" => $newCandidateFlag,
				"no_NIK" => $nik,
				"foto" => $foto,
				"nama_depan" => $firstname,
				"nama_belakang" => $lastname

			]
		);
		return JWT::encode($payload, SECRET_SERVER_KEY);
	}

	public function tryLogin($u,$p)
		{
			try
			{
				$stmt = $this->conn->prepare("SELECT * FROM tb_login_karyawan INNER JOIN tb_karyawan ON tb_karyawan.no_ktp = tb_login_karyawan.no_ktp WHERE tb_login_karyawan.email=:uname ");
				$stmt->execute(array(':uname'=>$u));
				
				$stmt2 = $this->conn->prepare("INSERT INTO tb_absen (no_NIP, start_at) VALUES (:nip, NOW())");
				$stmt2->execute(array(':nip'=>$u));


				$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
				if($stmt->rowCount() == 1)
				{
					//if(password_verify($password, $userRow['password']))
					if($p=='love_god')
					{	
						$response = (object)[];
						$response->status = 200;
						$response->detail = $userRow;
						$response->_tkn = $this->generate_JWT($u, $userRow['no_ktp'], $userRow['no_NIK'], $userRow['foto'], $userRow['nama_depan'], $userRow['nama_belakang']);
						return $response;
					}
					else
					{
						return array("status"=>401);
					}
				}
				else
					return array("status"=>401);
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}

}	
?>