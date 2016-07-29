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
		//$usuario = $this->session->get("usuario");
		//echo "Bienvenido/a $usuario";
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
				$error = "Contrase&ntilde;a incorrecta";
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
				$this->flashSession->error($error);
				return $this->dispatcher->forward(
						array(
								"controller" => "inicio",
								"action"     => "newPass"
						)
						);				
			}else{
				$u->u_contrasena = $this->security->hash($np);
				$u->save();
				$this->flash->success($exito);
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
		$success = Usuarios::find("u_lanid ='$user'");
		 
		if ($success->count() < 2 && $success->count() > 0) {
			$usuario = new Usuarios();
			$usuario = $success->getFirst();
	
			//validar contrasena
			if($this->security->checkHash($pass, $usuario->u_contrasena)){
				$this->session->set("usuario", $usuario->u_id);
				$this->session->set("user_time", time());
				 
				if($this->security->checkHash('fakePass', $usuario->u_contrasena)){
					return $this->dispatcher->forward(
							array(
									"controller" => "inicio",
									"action"     => "newPass"
							)
							);
				}else{
					return $this->dispatcher->forward(
							array(
									"controller" => "inicio",
									"action"     => "index"
							)
							);
				}
			}else{
				$this->flashSession->error("Credenciales suministradas son err&oacute;neas");
				return $this->dispatcher->forward(
						array(
								"controller" => "inicio",
								"action"     => "retry"
						)
						);
			}
			 
		} else {
			$this->flashSession->error("no existe el usuario");
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