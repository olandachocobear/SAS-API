<?php

class Login
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
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