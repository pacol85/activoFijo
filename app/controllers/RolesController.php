<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class RolesController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for roles
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Roles', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "r_id";

        $roles = Roles::find($parameters);
        if (count($roles) == 0) {
            $this->flash->notice("The search did not find any roles");

            return $this->dispatcher->forward(array(
                "controller" => "roles",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $roles,
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
     * Edits a role
     *
     * @param string $r_id
     */
    public function editAction($r_id)
    {
        if (!$this->request->isPost()) {

            $role = Roles::findFirstByr_id($r_id);
            if (!$role) {
                $this->flash->error("role was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "roles",
                    "action" => "index"
                ));
            }

            $this->view->r_id = $role->r_id;

            $this->tag->setDefault("r_id", $role->r_id);
            $this->tag->setDefault("r_rol", $role->r_rol);
            $this->tag->setDefault("r_descripcion", $role->r_descripcion);
            
        }
    }

    /**
     * Creates a new role
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "roles",
                "action" => "index"
            ));
        }

        $role = new Roles();

        $role->r_id = $this->request->getPost("r_id");
        $role->r_rol = $this->request->getPost("r_rol");
        $role->r_descripcion = $this->request->getPost("r_descripcion");
        

        if (!$role->save()) {
            foreach ($role->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "roles",
                "action" => "new"
            ));
        }

        $this->flash->success("role was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "roles",
            "action" => "index"
        ));
    }

    /**
     * Saves a role edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "roles",
                "action" => "index"
            ));
        }

        $r_id = $this->request->getPost("r_id");

        $role = Roles::findFirstByr_id($r_id);
        if (!$role) {
            $this->flash->error("role does not exist " . $r_id);

            return $this->dispatcher->forward(array(
                "controller" => "roles",
                "action" => "index"
            ));
        }

        $role->r_id = $this->request->getPost("r_id");
        $role->r_rol = $this->request->getPost("r_rol");
        $role->r_descripcion = $this->request->getPost("r_descripcion");
        

        if (!$role->save()) {

            foreach ($role->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "roles",
                "action" => "edit",
                "params" => array($role->r_id)
            ));
        }

        $this->flash->success("role was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "roles",
            "action" => "index"
        ));
    }

    /**
     * Deletes a role
     *
     * @param string $r_id
     */
    public function deleteAction($r_id)
    {
        $role = Roles::findFirstByr_id($r_id);
        if (!$role) {
            $this->flash->error("role was not found");

            return $this->dispatcher->forward(array(
                "controller" => "roles",
                "action" => "index"
            ));
        }

        if (!$role->delete()) {

            foreach ($role->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "roles",
                "action" => "search"
            ));
        }

        $this->flash->success("role was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "roles",
            "action" => "index"
        ));
    }

	function armaMenu($uid) {
		//cargar rol
		$user = Usuarios::findFirst("u_id = $uid");
		//$rol = Roles::findFirst("r_id = $user->r_id");
		//$mxr = Mxr::find("r_id = $user->r_id");
		
		$titulos = Menu::find(array("m_parent is null and m_id in (select x.m_id from mxr x where x.r_id = $user->r_id)", "order" => "m_id"));
    	$li1 = '<li class="pure-menu-item">';
    	$lip = '<li class="pure-menu-item pure-menu-has-children pure-menu-allow-hover pure-menu-link">';
    	$a1 = '<a href="';
    	$a2 = '" class="pure-menu-link">';
    	$afin = '" class="custom-link-exit">';
    	$lie = '</a></li>';
    	$ul1 = '<ul class="pure-menu-children">';
    	$ul2 = '</ul>';
    	$html = '
    	<div class="pure-menu pure-menu-horizontal">
		    <ul class="pure-menu-list">';
    	foreach ($titulos as $t){    			
    		$smenus = Menu::find(array("m_parent = $t->m_id  and m_id in (select x.m_id from mxr x where x.r_id = $user->r_id)", "order" => "m_id"));
    		if(count($smenus) > 0){
    			$html = $html.$lip.$t->m_label.$ul1;
    			foreach ($smenus as $sm){
    				$html = $html.$li1.$a1.$sm->m_href.$a2.$sm->m_label.$lie;
    			}
    			$html = $html.$ul2;
    		}else{
    			if($t->m_id == 99){
    				$html = $html.$li1.$a1.$t->m_href.$afin.$t->m_label.$lie;
    			}else{
    				$html = $html.$li1.$a1.$t->m_href.$a2.$t->m_label.$lie;
    			}
    		}    		
    	}
    	$html = $html.'</ul></div>';
    	return $html;
    }
	    
	    /*
	     /*$texto = '<div class="pure-menu pure-menu-horizontal">
		    <ul class="pure-menu-list">
		        <li class="pure-menu-item pure-menu-selected"><a href="./inicio/index" class="pure-menu-link">Inicio</a></li>
		        <li class="pure-menu-item pure-menu-has-children pure-menu-allow-hover">
					Movimiento
						<ul class="pure-menu-children">
							<li class="pure-menu-item"><a href="./formulario/ingreso" class="pure-menu-link">Ingreso</a></li>
							<li class="pure-menu-item"><a href="./formulario/traslado" class="pure-menu-link">Traslado</a></li>
							<li class="pure-menu-item"><a href="./formulario/baja" class="pure-menu-link">Baja</a></li>
							<li class="pure-menu-item"><a href="./formulario/reimprimir" class="pure-menu-link">Reimpresi&oacute;n</a></li>
							<li class="pure-menu-item"><a href="./formulario/movimientos?r=1" class="pure-menu-link">Movimientos del Mes</a></li>
					</ul>
				</li>
		        <li class="pure-menu-item pure-menu-has-children pure-menu-allow-hover">
					Inventario
						<ul class="pure-menu-children">
							<li class="pure-menu-item"><a href="./inventario/activo" class="pure-menu-link">Activo Fijo</a></li>
							<li class="pure-menu-item"><a href="./inventario/bienes" class="pure-menu-link">Bienes llevados a Gasto</a></li>
							<li class="pure-menu-item"><a href="./inventario/correlativos" class="pure-menu-link">Agregar Correlativos</a></li>							
					</ul>
				</li>
				<li class="pure-menu-item"><a href="./inicio/salir" class="custom-link-exit">Cerrar Sesi&oacute;n</a></li>        
		    </ul>
		</div>';*/
	     
}
