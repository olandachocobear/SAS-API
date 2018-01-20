<?php

class JobList
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
		$this->response = (object)[];
    }
	

	public function startJob($kd_job,$u) 
	{
		$response = $this->response;

		try
		{
			$stmt = $this->conn->prepare("INSERT into tb_report_job (kode_report,no_NIP,kode_detail_job,start_at) VALUES ('',:nip,:kd_job ,NOW())");
		
			$stmt->execute(array(':nip'=>$u,':kd_job'=>$kd_job));

			$response->status = '200';
			$response->row_upd = $stmt->rowCount();
		}

		catch(PDOException $e)
		{
				$response->status = '500';
				$str = $e->getMessage();
				$response->error = $str;
		}

		return $response;
	}

	
	public function getAll($u)
		{
			try
			{
				$stmt = $this->conn->prepare("SELECT tb_list_job.id, tb_job.kode_detail_job, tb_list_job.nama_job, tb_list_job.deskripsi_job, kode_admin, start_at FROM tb_job INNER JOIN tb_list_karyawan ON tb_list_karyawan.kode_list_karyawan=tb_job.nomor_kontrak INNER JOIN tb_list_job ON tb_list_job.kode_detail_job=tb_job.kode_detail_job LEFT JOIN tb_report_job ON tb_report_job.kode_detail_job = tb_list_job.id WHERE tb_list_karyawan.no_nip =:nip");
				$stmt->execute(array(':nip'=>$u));
				
				$results=$stmt->fetchAll(PDO::FETCH_ASSOC);
				if($stmt->rowCount() > 0)
				{
					$this->response->joblist = $results;
					return $this->response;
				}
				else
				{
					return false;
				}
			}
			catch(PDOException $e)
			{
				echo $e->getMessage();
			}
		}

}	
?>