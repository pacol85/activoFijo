<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class UsuariosController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
        $this->tag->resetInput();
        $deptos = Departamento::find();
        $roles = Roles::find();
        $campos = [
        		["t", ["nombre"], "Nombre"],
        		["t", ["lanid"], "LAN ID"],
        		["t", ["codigo"], "Codigo conta"],
        		["t", ["eid"], "ID Empleado"],
        		["sdb", ["dept", $deptos, ["d_id", "d_nombre"]], "Departamento"],
        		["sel", ["tipo", ["1" => "Empleado", "2" => "Outsource"]], "Tipo empleado"],
        		["sdb", ["rol", $roles, ["r_id", "r_rol"], 5], "Rol"],
        		["s", [""], "Crear Usuario"]
        ];
        $js = parent::jsCargarDatos(
        		["nombre", "lanid", "codigo", "eid", "dept", "tipo", "rol"], 
        		["main"], ["edit"]);
        $tabla = parent::thead("users", [
        		"LAN ID", "Nombre", "ID empleado", "C&oacute;digo", "Departamento", 
        		"Rol", "Tipo", "Estado", "Acciones" 
        ]);
        
        $users = Usuarios::find();
        foreach ($users as $u){
        	$tabla = $tabla."<tr>";
        	$dept = new Departamento();
        	if($u->d_id != null){
        		$dept = Departamento::findFirst(array("d_id = ".$u->d_id));
        	}        	
        	$rol = Roles::findFirst(array("r_id = ".$u->r_id));
        	$t = "Empleado";
        	if($u->u_tipo == 2) $t = "Outsource";
        	$e = "Activo";
        	$action = "Deshabilitar";
        	if($u->u_estado == 2) {
        		$e = "Inactivo";
        		$action = "Habilitar";
        	}
        	        	
        	
        	$tabla = $tabla.parent::td([
        			$u->u_lanid,
        			$u->u_nombre,
        			$u->u_eid,
        			$u->u_codigo,
        			$dept->d_nombre,
        			$rol->r_rol,
        			$t,
        			$e,
        			"<a onClick=\"cargarDatos('".$u->u_nombre."','".$u->u_lanid.
        			"','$u->u_codigo','$u->u_eid','$u->d_id','$u->u_tipo','$u->r_id');\">Editar</a> |
        			<a href='usuarios/disable?u=$u->u_id'>$action</a>"        			
        	]);
        	$tabla = $tabla."</tr>";
        }
        
        $this->view->js = $js;
        $this->view->titulo = parent::elemento("h1", ["usuarios"], "Usuarios");
        $this->view->form = parent::form($campos, "usuarios/crear", "form1");
        $this->view->botones = parent::elemento("bg", [["edit", "guardarCambio()", "Editar"],["cancel", "cancelar()", "Cancelar"]], "");
        $this->view->tabla = parent::ftable($tabla);
    }

    /**
     * Creación de usuarios
     */
    public function crearAction()
    {
    	$nombre = $this->request->getPost("nombre");
    	$lid = $this->request->getPost("lanid");
    	if($nombre == "" || $lid == ""){
    		$this->flash->error("Nombre o LAN ID no pueden ir en blanco");
    	}else {
    		$user = new Usuarios();
    		$user->d_id = $this->request->getPost("dept");
    		$user->r_id = $this->request->getPost("rol");
    		$user->u_codigo = $this->request->getPost("codigo");
    		$user->u_contrasena = $this->security->hash('fakePass');
    		$user->u_creacion = parent::fechaHoy(true);
    		$user->u_eid = $this->request->getPost("eid");
    		$user->u_estado = 1;
    		$user->u_lanid = $lid;
    		$user->u_modificacion = parent::fechaHoy(true);
    		$user->u_nombre = $nombre;
    		$user->u_tipo = $this->request->getPost("tipo");
    		
    		if ($user->save()){
    			$this->flash->success("Usuario creado exitosamente");
    		}else{
    			$this->flash->error("Ocurri&oacute; un error al guardar el usuario");
    		}
    	}
    	return $this->dispatcher->forward(array(
    			"controller" => "usuarios",
    			"action" => "index"
    	));
    }
    
    /**
     * Deshabilitar usuarios
     */
    public function disableAction()
    {
    	$uid = $this->request->get("u");
    	$user = Usuarios::findFirst("u_id = $uid");
    	if($user->u_estado == 1){
    		$user->u_estado = 2;
    	}else $user->u_estado = 1;
    	if($user->save()){
    		if($user->u_estado == 1){
    			$this->flash->success("El usuario: $user->u_lanid ha sido Habilitado");
    		}else $this->flash->success("El usuario: $user->u_lanid ha sido Deshabilitado");    		
    	}else{
    		$this->flash->error("Ocurri&oacute; un error al guardar el usuario");
    	}
    	return $this->dispatcher->forward(array(
    			"controller" => "usuarios",
    			"action" => "index"
    	));
    }
    
    
    /**
     * Searches for usuarios
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Usuarios', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "u_id";

        $usuarios = Usuarios::find($parameters);
        if (count($usuarios) == 0) {
            $this->flash->notice("The search did not find any usuarios");

            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $usuarios,
            "limit"=> 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a usuario
     *
     * @param string $u_id
     */
    public function editAction($u_id)
    {
        if (!$this->request->isPost()) {

            $usuario = Usuarios::findFirstByu_id($u_id);
            if (!$usuario) {
                $this->flash->error("usuario was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "usuarios",
                    "action" => "index"
                ));
            }

            $this->view->u_id = $usuario->u_id;

            $this->tag->setDefault("u_id", $usuario->u_id);
            $this->tag->setDefault("u_nombre", $usuario->u_nombre);
            $this->tag->setDefault("u_apellido", $usuario->u_apellido);
            $this->tag->setDefault("u_lanid", $usuario->u_lanid);
            $this->tag->setDefault("u_contrasena", $usuario->u_contrasena);
            $this->tag->setDefault("u_creacion", $usuario->u_creacion);
            $this->tag->setDefault("u_modificacion", $usuario->u_modificacion);
            $this->tag->setDefault("u_estado", $usuario->u_estado);
            $this->tag->setDefault("u_codigo", $usuario->u_codigo);
            $this->tag->setDefault("u_eid", $usuario->u_eid);
            $this->tag->setDefault("d_id", $usuario->d_id);
            $this->tag->setDefault("u_tipo", $usuario->u_tipo);
            
        }
    }

    /**
     * Creates a new usuario
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "index"
            ));
        }

        $usuario = new Usuarios();

        $usuario->u_nombre = $this->request->getPost("u_nombre");
        $usuario->u_apellido = $this->request->getPost("u_apellido");
        $usuario->u_lanid = $this->request->getPost("u_lanid");
        $usuario->u_contrasena = $this->request->getPost("u_contrasena");
        $usuario->u_creacion = $this->request->getPost("u_creacion");
        $usuario->u_modificacion = $this->request->getPost("u_modificacion");
        $usuario->u_estado = $this->request->getPost("u_estado");
        $usuario->u_codigo = $this->request->getPost("u_codigo");
        $usuario->u_eid = $this->request->getPost("u_eid");
        $usuario->d_id = $this->request->getPost("d_id");
        $usuario->u_tipo = $this->request->getPost("u_tipo");
        

        if (!$usuario->save()) {
            foreach ($usuario->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "new"
            ));
        }

        $this->flash->success("usuario was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "usuarios",
            "action" => "index"
        ));
    }

    /**
     * Saves a usuario edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "index"
            ));
        }

        $u_id = $this->request->getPost("u_id");

        $usuario = Usuarios::findFirstByu_id($u_id);
        if (!$usuario) {
            $this->flash->error("usuario does not exist " . $u_id);

            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "index"
            ));
        }

        $usuario->u_nombre = $this->request->getPost("u_nombre");
        $usuario->u_apellido = $this->request->getPost("u_apellido");
        $usuario->u_lanid = $this->request->getPost("u_lanid");
        $usuario->u_contrasena = $this->request->getPost("u_contrasena");
        $usuario->u_creacion = $this->request->getPost("u_creacion");
        $usuario->u_modificacion = $this->request->getPost("u_modificacion");
        $usuario->u_estado = $this->request->getPost("u_estado");
        $usuario->u_codigo = $this->request->getPost("u_codigo");
        $usuario->u_eid = $this->request->getPost("u_eid");
        $usuario->d_id = $this->request->getPost("d_id");
        $usuario->u_tipo = $this->request->getPost("u_tipo");
        

        if (!$usuario->save()) {

            foreach ($usuario->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "edit",
                "params" => array($usuario->u_id)
            ));
        }

        $this->flash->success("usuario was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "usuarios",
            "action" => "index"
        ));
    }

    /**
     * Deletes a usuario
     *
     * @param string $u_id
     */
    public function deleteAction($u_id)
    {
        $usuario = Usuarios::findFirstByu_id($u_id);
        if (!$usuario) {
            $this->flash->error("usuario was not found");

            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "index"
            ));
        }

        if (!$usuario->delete()) {

            foreach ($usuario->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "search"
            ));
        }

        $this->flash->success("usuario was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "usuarios",
            "action" => "index"
        ));
    }

}
