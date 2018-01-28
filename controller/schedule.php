<?php

class Schedule
{	
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }

	public function confirm_interview($intrvw_id, $jam) {
		try
		{
			$stmt = $this->conn->prepare("UPDATE tb_info_interview SET status = :status WHERE kd_interview = :kd");

			$stmt->execute(array(':status'=>$jam, ':kd'=>$intrvw_id));

			$response = (object)[];
			$response->status = 200;
			return $response;

		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function confirm_test($test_id, $jam) {
		try
		{
			$stmt = $this->conn->prepare("UPDATE tb_info_test SET status = :status WHERE kode_test = :kd");

			$stmt->execute(array(':status'=>$jam, ':kd'=>$test_id));

			$response = (object)[];
			$response->status = 200;
			return $response;

		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}	
?>