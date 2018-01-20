<?php
class Validasi
{	
	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function validateSPK($u,$s)
		{
			try
			{
				$stmt = $this->conn->prepare("SELECT * FROM tb_list_karyawan WHERE no_nip=:nip AND kode_list_karyawan=:spk ");
				$stmt->execute(array(':nip'=>$u, ':spk'=>$s));
				
				$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
				if($stmt->rowCount() == 1)
				{
					return array("status"=> "OK");
				}
				else
				{
					return array("status"=> "failed");
				}
				
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}

}	
?>