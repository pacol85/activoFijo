<?php
class FnifController extends ControllerBase
{
	public function indexAction()
	{
		parent::limpiar();
		$campos = [
				["f", ["excel"], "Archivo Excel"],
				["s", ["generar"], "Generar XML"]
		];
		
		$form = parent::multiForm($campos, "fnif/generar", "form1");
		
		parent::view("Excel a XML", $form);
	}

	public function generarAction(){
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
					
				FnifController::excelToHtml($objPHPExcel);
			}
		}else {
			echo "No se selecciono ningun archivo";
		}
	}
	
	public function excelToHtml($archivo){
		//  Get worksheet dimensions
		//$sheets = $archivo->getAllSheets();
		set_time_limit(300);
		$sheet = $archivo -> setActiveSheetIndex(0);
		//foreach ($sheets as $sheet){
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();
			
		$html = "";
		$titulo = true;
		
                $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<rmif 
	xmlns=\"http://validador.ssf.gob.sv/rmif/informe\" 
	xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">\n";
                
                $xmlEnd = "</rmif>";
			
		//Mostrar datos del Excel en una tabla
			
		for ($row = 1; $row <= $highestRow; $row++){
			//  Read a row of data into an array
			$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
					NULL,
					TRUE,
					FALSE);
		
			foreach ($rowData as $row2){
					
				//Imprime fila de t�tulos
				if ($titulo == true){
					$titulo = false;
                                        continue;		
				}
				else{
                                    $xml = $xml . $this->xmlInforme($row2);
				}
			}
                        
		}
                
                $xml = $xml.$xmlEnd;
                
                //$file = 'informe.xml';
                // Write the contents back to the file
                //file_put_contents($file, $xml);
                //limpiar
                $this->view->disable();
                header('Content-type: text/plain');
                header('Content-Disposition: attachment; filename="informe.xml"');
                
                print $xml;
	}
        
        public function xmlInforme($arreglo) {
            /*$newArr = [];
            $i = 0;
            foreach ($arreglo as $arr) {
                $newArr[$i] = "". $arreglo;
                $i++;
            }*/
            if($arreglo[1] == "" || $arreglo[1] == null){
                return "";
            }
            $xml = "<informe>
		<codigo_instrumento>$arreglo[1]</codigo_instrumento>
		<cotizacion>$arreglo[2]</cotizacion>
		<valor_nominal>$arreglo[3]</valor_nominal>
		<inversion>$arreglo[4]</inversion>
		<activo>$arreglo[5]</activo>
		<portafolio>$arreglo[6]</portafolio>
		<intencion>$arreglo[7]</intencion>
		<gravamen>$arreglo[8]</gravamen>
            </informe>\n";
            return $xml;
        }

        public function eliminarAction($id){
		$menu = Menu::findFirst("id = $id");
		$items = Item::find("menu = $id");
		if(count($items) > 0){
			parent::msg("No se puede eliminar un Item que est&eacute; asociado a una orden", "w");
		}else {
			$nMenu = $menu->nombre;
			if($menu->delete()){
				parent::msg("Se elimin&oacute; el Item de Men&uacute;: $nMenu", "s");
			}else{
				parent::msg("","db");
			}
		}
		parent::forward("menu", "index");
	}

	public function disponibleAction($id){
		$menu = Menu::findFirst("id = $id");
		if($menu->disponible == 0){
			$menu->disponible = 1;
		}else{
			$menu->disponible = 0;
		}
		 
		if($menu->update()){
			parent::msg("Se cambio estado de disponibilidad de Item: $menu->nombre", "s");
		}else{
			parent::msg("","db");
		}
		parent::forward("menu", "index");
	}

	public function editAction(){
		if(parent::vPost("codigo")){
			$cod = parent::gPost("codigo");
			$id = parent::gPost("id");
			$exist = Menu::find("codigo = '$cod' and not(id = $id)");
			if(count($exist) > 0){
				parent::msg("El c&oacute;digo ingresado ya existe");
				return parent::forward("menu", "index");
			}
			$nombre = parent::gPost("nombre");

			$menu = Menu::findFirst("id = $id");
			$menu->codigo = $cod;
			$menu->descripcion = parent::gPost("desc");
			$menu->disponible = 1;
			$menu->nombre = $nombre;
			$menu->precio = parent::gPost("precio");
			$menu->seccion = parent::gPost("seccion");

			//Phalcon upload file
			if (true == $this->request->hasFiles() && $this->request->isPost()) {
				$upload_dir = APP_PATH . '\\public\\img\\';

				foreach ($this->request->getUploadedFiles() as $file) {
					if(strlen($file->getName()) > 0){
						$punto = strpos($file->getName(), ".");
						$menu->foto = $menu->codigo.substr($file->getName(), $punto);
						$file->moveTo($upload_dir . $menu->foto);
							
					}

				}

			}

			if($menu->update()){
				parent::msg("Men&uacute; actualizado exitosamente", "s");
			}else{
				parent::msg("Ocurri&oacute; un error durante la operaci�n");
			}
		}else{
			parent::msg("El campo c&oacute; no puede quedar en blanco");
		}
		parent::forward("menu", "index");
	}
}