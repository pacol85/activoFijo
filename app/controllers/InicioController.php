<?php
use Phalcon\Flash;
class InicioController extends ControllerBase {
	public function indexAction(){
		if (!($this->session->has("usuario"))){
			$this->dispatcher->forward(
					array(
							"controller" => "index",
							"action"     => "index"
					)
			);
		}
		
	}
	
	public function salirAction(){
		$usuario = $this->session->destroy(true);
		$this->dispatcher->forward(
				array(
						"controller" => "index",
						"action"     => "index"
				)
		);
		
	}
	
	public function newPassAction()
	{
	
	}
	
	public function cambiarAction()
	{
		$usuario = $this->session->get("usuario");
		$op = $this->request->getPost("oldPass");
		$np = $this->request->getPost("newPass");
		$rp = $this->request->getPost("repeatPass");
		$exito = "";
		$error = "";
		 
		if(($op == null || $op == "") and ($np == null || $np == "") and ($rp == null || $rp == "")){
			$this->flashSession->error("Alguno de los campos solicitados no fue llenado");
			return $this->dispatcher->forward(array(
					"controller" => "inicio",
					"action" => "newPass"
			));
			
		}else{
			$u = Usuarios::findFirst($usuario);
			if(!$this->security->checkHash($op, $u->u_contrasena)){
				$error = "Contrase&ntilde;a original incorrecta";
			}
			if($np != $rp){
				$error = "Las contrase&ntilde;as no concuerdan";
			}
			if(preg_match('/^(?=.{8,}$)(?=.*?[A-Z])(?=.*?([\x20-\x40\x5b-\x60\x7b-\x7e\x80-\xbf]).*?$).*$/',$np)){
				$exito = "Contrase&ntilde;a cambiada exitosamente";
			}else{
				$error = "Nueva contrase&ntilde;a no cumple con los estandares";
			}
			if($error != ""){
				parent::msg($error);
				return $this->dispatcher->forward(
						array(
								"controller" => "inicio",
								"action"     => "newPass"
						)
						);				
			}else{
				$u->u_contrasena = $this->security->hash($np);
				$u->update();
				parent::msg($exito, "s");
				return $this->dispatcher->forward(
						array(
								"controller" => "inicio",
								"action"     => "index"
						)
						);
			}
		}
	}
	
	public function retryAction()
	{
		
	}
	
	public function ingresarAction()
	{
	//$this->view->disable();
    	$user = $this->request->get("user");
    	$user = trim($user);
    	$user = strtoupper($user);
    	$pass = $this->request->get("pass");
    	$pass = trim($pass);
    	// Ver si existe el usuario y contraseña
    	$success = Usuarios::find("u_lanid ='$user' and u_estado = 1");
    	
    	if ($success->count() < 2 && $success->count() > 0) {
    		$usuario = new Usuarios();
    		$usuario = $success->getFirst();
    		
    		//validar contrasena
    		if(parent::checkPass($pass, $usuario->u_contrasena)){
    			$this->session->set("usuario", $usuario->u_id);
    			if(parent::checkPass($pass, "",true)){
    				parent::forward("inicio", "newPass");
    			}else{
    				parent::forward("inicio", "index");
    			}
    		}else{
    			parent::msg("Credenciales suministradas son err&oacute;neas");
    			parent::forward("inicio", "retry");
    		}   		
    		   		    		
    	} else {
    		parent::msg("no existe el usuario");
    		return $this->dispatcher->forward(
    				array(
    						"controller" => "inicio",
    						"action"     => "retry"
    				)
    				);
    	}
	}
}
?>