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
    
    public function logoutAction()
    {
    	$this->view->disable();
    	echo "Debe iniciar sesion para entrar a este sitio";
    }

}

