<?php
use Phalcon\Flash;
class InicioController extends ControllerBase {
	public function indexAction(){
		if (!($this->session->has("usuario"))){
			return $this->dispatcher->forward(
					array(
							"controller" => "index",
							"action"     => "index"
					)
					);
		}
		$usuario = $this->session->get("usuario");
		$user = Usuarios::findFirst($usuario);

		$tabla = parent::thead("activos", ["Correlativo", "Descripci&oacute;n", "Ubicaci&oacute;n", "Observaciones"]);
		$bienesList = Inventario::find("(i_activo = 1 or i_activo = 2) and i_estado = 1 and u_id = '$usuario'");
		foreach ($bienesList as $bien){
			$tabla = $tabla.parent::tbody([
					$bien->i_correlativo,
					$bien->i_descripcion,
					$bien->i_serie,
					$bien->i_observaciones
			]);
		}

		$sol = Solicitudes::find("tipo like '%Inventario Inicial%' and usuario = $user->u_id");
		$form = "";
		if(count($sol) < 1){
			$campos = [
					["h3", ["sol"], "Correlativos que no me pertenecen"],
					["tc", ["corr", "corr"], "Correlativos"],
					["s", [""], "Enviar"]
			];
			$form = parent::form($campos, "inicio/solicitud", "form1");
		}

		$this->view->h2 = parent::elemento("h2", "h2", "Activos y Bienes asociados al usuario");
		parent::view("Bienvenido/a $user->u_nombre $user->u_apellido", $form, $tabla);

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
			//if(parent::checkPass($pass, $usuario->u_contrasena)){
			if(parent::loginLDAP($user, $pass)){
				$this->session->set("usuario", $usuario->u_id);
				/*if(parent::checkPass($pass, "",true)){
					parent::forward("inicio", "newPass");
				}else{*/
					parent::forward("inicio", "index");
				//}
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

	/*
	 * Crear Solicitud inicial de inventario
	 */
	public function solicitudAction()
	{
		$s = parent::gPost("corr");
		if($s == "" or $s == null){
			parent::msg("No se puede quedar en blanco el campo de correlativos, como m&icaute;nimo colocar 0 si no hay cambios");
			return parent::forward("inicio", "index");
		}

		$sol = new Solicitudes();
		$sol->descripcion = $s;
		$sol->fapertura = parent::fechaHoy(true);
		$sol->resumen = "Inventario erroneo";

		//el tecnico es el administrador del sistema
		$tec = Parametros::findFirst("p_parametro = 'administrador'");
		$sol->tecnico = $tec->p_valor;
		$tipo = Tipo::findFirst("tipo like 'Inventario Inicial'");
		$sol->tipo = $tipo->tipo;
		$sol->usuario = parent::gSession("usuario");
		if($sol->save()){
			parent::msg("Creaci&oacute;n de requerimiento exitoso", "s");
			parent::forward("inicio", "index");
		}else{
			parent::msg("Ocurri&oacute; un error durante la operaci&oacute;n");
			parent::forward("inicio", "index");
		}

	}
}
?>