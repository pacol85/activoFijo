<?php

class SubirController extends ControllerBase {

	public function indexAction(){

	}

	public function uploadAction(){
		if($this->request->hasFiles())
		{
			foreach($this->request->getUploadedFiles() as $file)
			{
				//  Read your Excel workbook
				try {
					$inputFileType = PHPExcel_IOFactory::identify($file->getTempName());
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($file->getTempName());
				} catch(Exception $e) {
					die('Error loading file "'.$file->getTempName().'": '.$e->getMessage());
				}
					
				SubirController::leerArchivo($objPHPExcel, 2);
			}
		}else {
			echo "No se selecciono ningun archivo";
		}
		$this->view->enable();

	}

	public function uploadAFAction(){
		if($this->request->hasFiles())
		{
			foreach($this->request->getUploadedFiles() as $file)
			{
				//  Read your Excel workbook
				try {
					$inputFileType = PHPExcel_IOFactory::identify($file->getTempName());
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($file->getTempName());
				} catch(Exception $e) {
					die('Error loading file "'.$file->getTempName().'": '.$e->getMessage());
				}
					
				SubirController::leerArchivo($objPHPExcel, 1);
			}
		}else {
			echo "No se selecciono ningun archivo";
		}
		$this->view->enable();

	}

	//Función leerArchivo recibe la hoja a leer y el documento
	function leerArchivo($archivo, $af){
		//  Get worksheet dimensions
		//$sheets = $archivo->getAllSheets();
		set_time_limit(300);
		$sheet = $archivo -> setActiveSheetIndex(0);
		//foreach ($sheets as $sheet){
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
			
		$stringTabla = "";
		$titulo = true;
		$longitud = 0;
		$inventario = array();
		$usuario = array();
		$timezone  = -6; //(GMT -5:00) EST (U.S. & Canada)
		$fechaHoy = gmdate("Y-m-d H:i:s", time() + 3600*($timezone));
		$fila = 0;
			
		//Mostrar datos del Excel en una tabla
			
		for ($row = 1; $row <= $highestRow; $row++){
			//  Read a row of data into an array
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
					NULL,
					TRUE,
					FALSE);
				
			foreach ($rowData as $row2){
					
				//Imprime fila de títulos
				if ($titulo == true){
					$titulo = false;
					continue;
						
				}
				else{
					$pos = 0;
					//$nada = false;
						
					//$stringTabla += "<tr>";
					foreach ($row2 as $columna){
						/*if($pos == 0 and $columna == ""){
						 $nada = true;
						 break;
							}*/
						if($pos<6 || $pos > 7){
							$inventario[$pos] = $columna;
						}else{
							$usuario[$pos-6]= $columna;
						}
						//$stringTabla += "<td>$columna</td>";
						$pos++;
							
					}
					//if($nada == false){
					//$stringTabla += "</tr>";
					//$fila = $fila + 1;
					$item = new Inventario();
					$user = new Usuarios();
					//$cxp = new CrColorXProducto();
					//echo "producto_id: $productos[0]";
					//Comparar si existe el producto
					if($usuario[0] == "V" || $usuario[0] == "v") $usuario[0] = 0;
					if($usuario[0] == null || $usuario[0] == "") $usuario[0] = -1;
					$users = Usuarios::find("u_codigo = $usuario[0]");
					if( count($users) < 1){
						//Guardar producto
						//echo "entro al count de productos ".count($prods);
						$user->u_codigo = $usuario[0];
						$user->u_nombre = $usuario[1];
						$user->u_creacion = $fechaHoy;
						$user->save();
					}else{
						foreach ($users as $user2) {
							$user = $user2;
						}
					}
						
					//agrega inventario con el usuario correspondiente
					$item->i_activo = 1;
					$item->i_correlativo = $inventario[0];
					$item->i_descripcion = $inventario[1];
					$item->i_ubicacion = $inventario[2];
					$item->i_fingreso = SubirController::fechaMySQL($inventario[3]);
					$item->i_vadquisicion = $inventario[4];
					if($inventario[5] == "C"){
						$item->i_encontrado = 3;
					}else{
						$item->i_encontrado = 1;
					}
					$item->u_id = $user->u_id;
					$item->i_observaciones = $inventario[8];
					$item->i_activo = $af;
					if($af == 1){
						$item->i_vresidual = $inventario[9];
						$item->i_dacumulada = $inventario[10];
						$item->i_penddepreciar = $inventario[11];
					}
					$item->save();
						
					//}

				}
					
			}

		}
			
		//}

	}

	function fechaExcel($xl_date)
	{
		$PHPTimeStamp = PHPExcel_Shared_Date::ExcelToPHP($xl_date);
		$fechaExcel = date('Y-m-d',$PHPTimeStamp);
		//$fechaExcel = date_format((($xl_date - 25569) * 86400), "Y-m-d H:i:s");
		return $fechaExcel;
	}

	function fechaMySQL($xl_date)
	{
		$PHPTimeStamp = PHPExcel_Shared_Date::ExcelToPHP($xl_date);
		$fechaExcel = date('Y-m-d H:i:s',$PHPTimeStamp);
		//$fechaExcel = date_format((($xl_date - 25569) * 86400), "Y-m-d H:i:s");
		return $fechaExcel;
	}

	function usuariosAction(){
		$file = "c:\TEMPFILE\Usuarios.xlsx";
		$inputFileType = PHPExcel_IOFactory::identify($file);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$archivo = $objReader->load($file);

		set_time_limit(300);
		$sheet = $archivo -> setActiveSheetIndex(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
			
		$titulo = true;
		$longitud = 0;
		$usuario = array();
		$timezone  = -6; //(GMT -5:00) EST (U.S. & Canada)
		$fechaHoy = gmdate("Y-m-d H:i:s", time() + 3600*($timezone));
		$fila = 0;

		for ($row = 1; $row <= $highestRow; $row++){
			//  Read a row of data into an array
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
					NULL,
					TRUE,
					FALSE);

			foreach ($rowData as $row2){
					
				//Imprime fila de títulos
				if ($titulo == true){
					$titulo = false;
					continue;

				}
				else{
					$user = Usuarios::find("u_lanid = '".$row2[0]."'");
					if (count($user) > 0){
						foreach ($user as $u){
							$u->u_eid = $row2[2];
							$u->email = $row2[3];
							$u->save();
						}
					}else{
						$usuario = new Usuarios();
						$usuario->u_lanid = $row2[0];
						$usuario->u_nombre = $row2[1];
						$usuario->u_eid = $row2[2];
						$usuario->email = $row2[3];
						$usuario->u_creacion = parent::fechaHoy(true);
						$usuario->save();
					}
						

				}
					
			}

		}
	}

	/*
	 * Funciones de subida del nuevo archivo para iniciar de cero en ASSA
	 */
	public function upload2Action(){
		if($this->request->hasFiles())
		{
			foreach($this->request->getUploadedFiles() as $file)
			{
				//  Read your Excel workbook
				try {
					$inputFileType = PHPExcel_IOFactory::identify($file->getTempName());
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($file->getTempName());
				} catch(Exception $e) {
					die('Error loading file "'.$file->getTempName().'": '.$e->getMessage());
				}
					
				SubirController::leerArchivo2($objPHPExcel, 2);
			}
		}else {
			echo "No se selecciono ningun archivo";
		}
		$this->view->enable();

	}

	public function uploadAF2Action(){
		if($this->request->hasFiles())
		{
			foreach($this->request->getUploadedFiles() as $file)
			{
				//  Read your Excel workbook
				try {
					$inputFileType = PHPExcel_IOFactory::identify($file->getTempName());
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($file->getTempName());
				} catch(Exception $e) {
					die('Error loading file "'.$file->getTempName().'": '.$e->getMessage());
				}
					
				SubirController::leerArchivo($objPHPExcel, 1);
			}
		}else {
			echo "No se selecciono ningun archivo";
		}
		$this->view->enable();

	}

	function subidaAFAction(){
		$file = "c:\TEMPFILE\af2.xlsx";
		$inputFileType = PHPExcel_IOFactory::identify($file);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$archivo = $objReader->load($file);

		set_time_limit(300);
		$sheet = $archivo -> setActiveSheetIndex(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
			
		$titulo = true;
		$usuario = array();
		$fila = 0;

		for ($row = 1; $row <= $highestRow; $row++){
			//  Read a row of data into an array
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
					NULL,
					TRUE,
					FALSE);

			foreach ($rowData as $col){
					
				if ($titulo == true){
					$titulo = false;
					continue;

				}
				else{
					$inventario = Inventario::find("i_correlativo like '".$col[0]."'");
					if (count($inventario) > 0){
						foreach ($inventario as $i){
							$i->i_vresidual = $col[4];
							$i->i_dacumulada = $col[5];
							$i->i_penddepreciar = $col[6];
							$i->update();
						}
					}else{
						$inv = new Inventario();
						$inv->i_correlativo = $col[0];
						$inv->i_descripcion = $col[1];
						$inv->i_fingreso = parent::fechaMySQLx($col[2]);
						$inv->i_vadquisicion = $col[3];
						$inv->i_vresidual = $col[4];
						$inv->i_dacumulada = $col[5];
						$inv->i_penddepreciar = $col[6];
						$inv->save();
					}


				}
					
			}

		}
	}

}