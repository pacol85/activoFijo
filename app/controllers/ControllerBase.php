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
	
	public function elemento($t, $n, $l, $c){
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
			default :
				$elem = '<div class="form-group"><label for="';
				//agregamos el nombre
				$elem = $elem.$n[0].'" class="col-sm-2 control-label">';
				//agrega label
				$elem = $elem.$l.'</label><div class="col-sm-10">';
				//agrega nombre campo
				switch ($t){
					case "t" :
						$elem = $elem.$this->tag->textField(array("$n[0]", "size" => 30, "class" => "form-control $c", "id" => "$n[0]", "value" => "$n[1]"));
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
						$elem = $elem.$this->tag->select(array("$n[0]", $n[1], "class" => "form-control", "id" => "$n[0]"));
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
			$elem = ControllerBase::elemento($c[0], $c[1], $c[2], $c[3]);
			$form = $form.$elem;
		}
	
		$form = $form.$this->tag->endForm();
		return $form;
	}
	
	public function tabla($id, $head, $body, $extras, $total){
		$tabla = '<div id="tdiv"><table id="'.$id.'" class="display" cellspacing="0"><thead><tr>';
		
		//Dibujar table head
		foreach ($head as $h){
			$tabla = $tabla.'<th>'.$h.'</th>';
		}
		$tabla = $tabla.'</tr></thead><tbody>';
		
		//dibujar table body
		foreach ($body as $b){
			$tabla = $tabla.'<tr><td>';
			echo "<tr>";
			echo "<td>$f->f_correlativo</td>";
			echo "<td>";
			if($f->f_tipoinventario == 1){
				echo "Activo Fijo";
			}else echo "Gasto";
			echo "</td>";
			echo "<td>".date("Y-m-d",strtotime($f->f_fecha))."</td>";
			$inv = Inventario::findFirst("i_id = $f->i_id");
			echo "<td>$inv->i_correlativo</td>";
			echo "<td>$inv->i_serie</td>";
			echo "<td>$inv->i_proveedor</td>";
			echo "<td>";
			if($f->f_movimiento == 1){
				echo "Ingreso";
			}elseif ($f->f_movimiento == 2){
				echo "Traslado";
			}else echo "Baja";
			echo "</td>";
			echo "<td>$ ".number_format($inv->i_vadquisicion,2)."</td>";
			echo "<td><a href='formulario/reimprime2?c=$f->f_correlativo&t=$f->f_tipoinventario'>Reimprimir</a></td>";
			echo "</tr>";
		}
		$tabla = $tabla.'</tbody></table></div>';
	}
}
