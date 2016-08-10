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
	
	public function elemento($t, $n, $l){
		$elem = "";
		switch ($t){
			case "h" :
				$elem = $elem.$this->tag->hiddenField(array("$n[0]", "value" => $l));
				break;
			case "s" :
				$elem = $elem.'<div class="form-group"><div class="col-sm-12" align="center">';
				$elem = $elem.$this->tag->submitButton(array("$l", "class" => "btn btn-default"));
				$elem = $elem.'</div></div>';
				break;
			case "h2" :
				$elem = $elem.'<h2>'.$l.'</h2>';
				break;
			case "h1" :
				$elem = $elem.'<div class="page-header"><h1>'.$l.'</h1></div>';
				break;
			case "l" :
				$elem = $elem.'<div class="form-group"><label for="'.$l.'" class="col-sm-2 control-label">'.$l.'</label>';
				$elem = $elem.'<div class="col-sm-2 control-label">'.$n[0].'</div></div>';
				break;
			case "lf" :
				$elem = $elem.'<div class="form-group"><label for="'.$n[0].'" class="col-sm-12">'.$l.'</label></div>';
				break;
			case "enter" :
				$elem = $elem.'<nobr>&nbsp;</nobr>';
				break;
			default :
				$elem = '<div class="form-group"><label for="';
				//agregamos el nombre
				$elem = $elem.$n[0].'" class="col-sm-2 control-label">';
				//agrega label
				$elem = $elem.$l.'</label><div class="col-sm-10">';
				//agrega nombre campo
				switch ($t){
					case "t" :
						$elem = $elem.$this->tag->textField(array("$n[0]", "size" => 30, "class" => "form-control", "id" => "$n[0]", "value" => "$n[1]"));
						break;
					case "m" :
						$elem = $elem.$this->tag->textField(array("$n[0]", "size" => 30, "class" => "form-control money", "id" => "$n[0]", "value" => "$n[1]"));
						break;
					case "p" :
						$elem = $elem.$this->tag->passwordField(array("$n[0]", "size" => 30, "class" => "form-control", "id" => "$n[0]"));
						break;
					case "d" :
						$elem = $elem.$this->tag->dateField(array("$n[0]", "min" => "0", "size" => 30, "class" => "form-control date datepicker", "id" => "$n[0]"));
						break;
					case "sdb" :
						if(count($n) > 3){
							$elem = $elem.$this->tag->select(array("$n[0]",
									$n[1],
									"using" => $n[2], "class" => "form-control", "id" => "$n[0]", "value" => $n[3]));
						}else{
							$elem = $elem.$this->tag->select(array("$n[0]",
									$n[1],
									"using" => $n[2], "class" => "form-control", "id" => "$n[0]"));
						}						
		    			break;
					case "sel" :
						if(count($n) > 2){
							$elem = $elem.$this->tag->select(array("$n[0]", $n[1], "class" => "form-control", "id" => "$n[0]", "value" => $n[2]));
						}else{
							$elem = $elem.$this->tag->select(array("$n[0]", $n[1], "class" => "form-control", "id" => "$n[0]"));
						}						
						break;
				}
				$elem = $elem.'</div></div>';
		}
		return $elem;
	}
	
	public  function form($campos, $action){
		$form = $this->tag->form(
				array(
						$action,
						"autocomplete" => "off",
						"class" => "form-horizontal"
				)
				);
		foreach ($campos as $c){
			$elem = ControllerBase::elemento($c[0], $c[1], $c[2]);
			$form = $form.$elem;
		}
	
		$form = $form.$this->tag->endForm();
		return $form;
	}
	
	public function thead($id, $head){
		$tabla = '<div id="tdiv"><table id="'.$id.'" class="display" cellspacing="0"><thead><tr>';
		
		//Dibujar table head
		foreach ($head as $h){
			$tabla = $tabla.'<th>'.$h.'</th>';
		}
		$tabla = $tabla.'</tr></thead><tbody>';			
		return $tabla;
	}
	
	public function td($col){
		$td = "";
		foreach ($col as $c){
			$td = $td.'<td>'.$c.'</td>';
		}
		return $td; 
	}
	
	public function ftable($tabla){
		$tabla = $tabla.'</tbody></table></div>';
		return $tabla;
	}
}
