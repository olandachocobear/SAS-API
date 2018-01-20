<?php

class JobReportList
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
		$this->response = (object)[];
    }
	

	public function postReport($kd_job,$u,$msg) 
	{
		$response = $this->response;

		try
		{
			$stmt = $this->conn->prepare("UPDATE tb_report_job SET report=:msg, report_date = NOW() where kode_detail_job = :kd_job AND no_NIP = :nip AND date(start_at) = date(NOW())");
		
			$stmt->execute(array(':nip'=>$u, 
								 ':kd_job'=>$kd_job, 
								 ':msg'=>$msg));

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
	
			$response = $this->response;
			
			try
			{
				$stmt = $this->conn->prepare("SELECT tb_list_job.id, tb_job.kode_detail_job, tb_list_job.nama_job, tb_list_job.deskripsi_job, kode_admin, start_at, report_date, report FROM tb_job INNER JOIN tb_list_karyawan ON tb_list_karyawan.kode_list_karyawan=tb_job.nomor_kontrak INNER JOIN tb_list_job ON tb_list_job.kode_detail_job=tb_job.kode_detail_job LEFT JOIN tb_report_job on tb_report_job.kode_detail_job = tb_list_job.id AND tb_report_job.no_nip=tb_list_karyawan.no_nip WHERE tb_list_karyawan.no_nip =:nip");
				$stmt->execute(array(':nip'=>$u));

				$stmt2 = $this->conn->prepare("SELECT tb_job.kode_detail_job FROM tb_job INNER JOIN tb_list_karyawan ON tb_list_karyawan.kode_list_karyawan=tb_job.nomor_kontrak INNER JOIN tb_list_job ON tb_list_job.kode_detail_job=tb_job.kode_detail_job LEFT JOIN tb_report_job on tb_report_job.kode_detail_job = tb_list_job.id AND tb_report_job.no_nip=tb_list_karyawan.no_nip WHERE tb_list_karyawan.no_nip =:nip AND report_date is not null AND report_date != '' ");
				$stmt2->execute(array(':nip'=>$u));				

				$results=$stmt->fetchAll(PDO::FETCH_ASSOC);

				$response->done = $stmt2->rowCount();
				$response->total = $stmt->rowCount();
				$response->status = '200';
				$response->joblist = $results;

			}
			catch(PDOException $e)
			{
				$response->status = '500';
				$str = $e->getMessage();
				$response->error = $str;

			}

		return $response;

		}

}	
?>