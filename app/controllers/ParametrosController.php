<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ParametrosController extends ControllerBase
{
	/**
	 * Index action
	 */
	public function indexAction()
	{
		$this->persistent->parameters = null;
		$users = Usuarios::find("u_estado = 1");
		$pMBienes = Parametros::findFirst("p_id = 1");
		$mb = str_ireplace(",", "", $pMBienes->p_valor);
		$pMActivos = Parametros::findFirst("p_id = 2");
		$ma = str_ireplace(",", "", $pMActivos->p_valor);
		$pAdmin = Parametros::findFirst("p_id = 3");
		$pConta = Parametros::findFirst("p_id = 4");
		$pRRHH = Parametros::findFirst("p_id = 5");
		$campos = [				
				["h2",["usuarios"], "Usuarios"],
				["sdb", ["admin", $users, ["u_id", "u_nombre"], $pAdmin->p_valor], "Administraci&oacute;n"],
				["sdb", ["conta", $users, ["u_id", "u_nombre"], $pConta->p_valor], "Usuario Contabilidad"],
				["sdb", ["rrhh", $users, ["u_id", "u_nombre"], $pRRHH->p_valor], "Recursos Humanos"],
				["h2",["min"], "M&iacute;nimos para Bienes y Activos"],
				["m", ["mbienes", 100*$mb], "M&iacute;nimo Bienes"],
				["m", ["mactivos", 100*$ma], "M&iacute;nimo Activos"],
				["s", [""], "Actualizar"]
		];
		$action = "parametros/actualizar";
		$this->view->elem = parent::form($campos, $action);
		$this->view->titulo = parent::elemento("h1", [""], "Par&aacute;metros");		
	}
	
	public function actualizarAction() {
		$error = "";
		$admin = $this->request->getPost("admin");
		$conta = $this->request->getPost("conta");
		$rh = $this->request->getPost("rrhh");
		$mbienes = $this->request->getPost("mbienes");
		$mbienes = str_ireplace(",", "", $mbienes);
		$mactivos = $this->request->getPost("mactivos");
		$mactivos = str_ireplace(",", "", $mactivos);
		if($mbienes <= 0 || $mactivos <= 0){
			$error = "Valores m&iacute;nimos no pueden quedar en blanco";
		}else{
			if($mactivos <= $mbienes){
				$error = "El valor m&iacute;nimo para activos no puede ser menor que el de bienes";
			}
		}
		if($error != ""){
			$this->flash->error($error);
		}else{
			$param = Parametros::find();
			foreach ($param as $p){
				switch ($p->p_id){ 
					case 1 :
						$p->p_valor = $mbienes;
						$p->save();
						break;
					case 2 :
						$p->p_valor = $mactivos;
						$p->save();
						break;
					case 3 :
						$p->p_valor = $admin;
						$p->save();
						break;
					case 4 :
						$p->p_valor = $conta;
						$p->save();
						break;
					case 5 :
						$p->p_valor = $rh;
						$p->save();
						break;
				}
			}
			$this->flash->success("Actualizaci&oacute;n exitosa");
		}
		return $this->dispatcher->forward(array(
				"controller" => "parametros",
				"action" => "index"
		));
	}
}