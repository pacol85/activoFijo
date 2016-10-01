<?php

class IndexController extends ControllerBase
{

    public function indexAction()
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
    
    public function logoutAction()
    {
    	$this->view->disable();
    	echo "Debe iniciar sesion para entrar a este sitio";
    }

}

