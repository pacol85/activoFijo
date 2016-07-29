<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
	/**
	 * Main init function
	 */
	public function initialize() {
		#code ...
		$this->flash->output();
	}
	
	public function fechaExcel($xl_date)
	{
		$PHPTimeStamp = PHPExcel_Shared_Date::ExcelToPHP($xl_date);
		$fechaExcel = date('Y-m-d',$PHPTimeStamp);
		//$fechaExcel = date_format((($xl_date - 25569) * 86400), "Y-m-d H:i:s");
		return $fechaExcel;
	}
	
	public function fechaMySQLx($xl_date)
	{
		$PHPTimeStamp = PHPExcel_Shared_Date::ExcelToPHP($xl_date);
		$fechaExcel = date('Y-m-d',$PHPTimeStamp);
		return $fechaExcel;
	}
	
	public function fechaHoraMySQLx($xl_date)
	{
		$PHPTimeStamp = PHPExcel_Shared_Date::ExcelToPHP($xl_date);
		$fechaExcel = date('Y-m-d H:i:s',$PHPTimeStamp);
		return $fechaExcel;
	}
	
	public function fechaHoy($conHora){
		$timezone  = -6;
		if($conHora == true){
			return gmdate("Y-m-d H:i:s", time() + 3600*($timezone));
		}else{
			return gmdate("Y-m-d", time() + 3600*($timezone));
		}
	
	}
	
	// Sends the json response
	public function sendJson($data) {
		$this->view->disable();
		$this->response->setContentType('application/json', 'UTF-8');
		$this->response->setContent(json_encode($data));
		return $this->response;
	}
}
