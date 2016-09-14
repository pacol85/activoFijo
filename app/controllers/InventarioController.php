<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use FormularioController as FormCont;


class InventarioController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }
    
    public function activoAction(){
    	
    }
    
    public function bienesAction(){
    	
    }

    /**
     * Searches for inventario
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Inventario', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "i_id";

        $inventario = Inventario::find($parameters);
        if (count($inventario) == 0) {
            $this->flash->notice("The search did not find any inventario");

            return $this->dispatcher->forward(array(
                "controller" => "inventario",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $inventario,
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
     * Edits a inventario
     *
     * @param string $i_id
     */
    public function editAction($i_id)
    {
        if (!$this->request->isPost()) {

            $inventario = Inventario::findFirstByi_id($i_id);
            if (!$inventario) {
                $this->flash->error("inventario was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "inventario",
                    "action" => "index"
                ));
            }

            $this->view->i_id = $inventario->i_id;

            $this->tag->setDefault("i_id", $inventario->i_id);
            $this->tag->setDefault("i_correlativo", $inventario->i_correlativo);
            $this->tag->setDefault("i_descripcion", $inventario->i_descripcion);
            $this->tag->setDefault("i_uanterior", $inventario->i_uanterior);
            $this->tag->setDefault("i_fingreso", $inventario->i_fingreso);
            $this->tag->setDefault("i_vadquisicion", $inventario->i_vadquisicion);
            $this->tag->setDefault("i_vresidual", $inventario->i_vresidual);
            $this->tag->setDefault("i_dacumulada", $inventario->i_dacumulada);
            $this->tag->setDefault("i_penddepreciar", $inventario->i_penddepreciar);
            $this->tag->setDefault("i_encontrado", $inventario->i_encontrado);
            $this->tag->setDefault("i_observaciones", $inventario->i_observaciones);
            $this->tag->setDefault("i_ubicacion", $inventario->i_ubicacion);
            $this->tag->setDefault("u_id", $inventario->u_id);
            $this->tag->setDefault("i_activo", $inventario->i_activo);
            $this->tag->setDefault("i_serie", $inventario->i_serie);
            $this->tag->setDefault("i_modelo", $inventario->i_modelo);
            $this->tag->setDefault("i_marca", $inventario->i_marca);
            
        }
    }

    /**
     * Creates a new inventario
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "inventario",
                "action" => "index"
            ));
        }

        $inventario = new Inventario();

        $inventario->i_correlativo = $this->request->getPost("i_correlativo");
        $inventario->i_descripcion = $this->request->getPost("i_descripcion");
        $inventario->i_uanterior = $this->request->getPost("i_uanterior");
        $inventario->i_fingreso = $this->request->getPost("i_fingreso");
        $inventario->i_vadquisicion = $this->request->getPost("i_vadquisicion");
        $inventario->i_vresidual = $this->request->getPost("i_vresidual");
        $inventario->i_dacumulada = $this->request->getPost("i_dacumulada");
        $inventario->i_penddepreciar = $this->request->getPost("i_penddepreciar");
        $inventario->i_encontrado = $this->request->getPost("i_encontrado");
        $inventario->i_observaciones = $this->request->getPost("i_observaciones");
        $inventario->i_ubicacion = $this->request->getPost("i_ubicacion");
        $inventario->u_id = $this->request->getPost("u_id");
        $inventario->i_activo = $this->request->getPost("i_activo");
        $inventario->i_serie = $this->request->getPost("i_serie");
        $inventario->i_modelo = $this->request->getPost("i_modelo");
        $inventario->i_marca = $this->request->getPost("i_marca");
        

        if (!$inventario->save()) {
            foreach ($inventario->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "inventario",
                "action" => "new"
            ));
        }

        $this->flash->success("inventario was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "inventario",
            "action" => "index"
        ));
    }

    /**
     * Saves a inventario edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "inventario",
                "action" => "index"
            ));
        }

        $i_id = $this->request->getPost("i_id");

        $inventario = Inventario::findFirstByi_id($i_id);
        if (!$inventario) {
            $this->flash->error("inventario does not exist " . $i_id);

            return $this->dispatcher->forward(array(
                "controller" => "inventario",
                "action" => "index"
            ));
        }

        $inventario->i_correlativo = $this->request->getPost("i_correlativo");
        $inventario->i_descripcion = $this->request->getPost("i_descripcion");
        $inventario->i_uanterior = $this->request->getPost("i_uanterior");
        $inventario->i_fingreso = $this->request->getPost("i_fingreso");
        $inventario->i_vadquisicion = $this->request->getPost("i_vadquisicion");
        $inventario->i_vresidual = $this->request->getPost("i_vresidual");
        $inventario->i_dacumulada = $this->request->getPost("i_dacumulada");
        $inventario->i_penddepreciar = $this->request->getPost("i_penddepreciar");
        $inventario->i_encontrado = $this->request->getPost("i_encontrado");
        $inventario->i_observaciones = $this->request->getPost("i_observaciones");
        $inventario->i_ubicacion = $this->request->getPost("i_ubicacion");
        $inventario->u_id = $this->request->getPost("u_id");
        $inventario->i_activo = $this->request->getPost("i_activo");
        $inventario->i_serie = $this->request->getPost("i_serie");
        $inventario->i_modelo = $this->request->getPost("i_modelo");
        $inventario->i_marca = $this->request->getPost("i_marca");
        

        if (!$inventario->save()) {

            foreach ($inventario->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "inventario",
                "action" => "edit",
                "params" => array($inventario->i_id)
            ));
        }

        $this->flash->success("inventario was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "inventario",
            "action" => "index"
        ));
    }

    /**
     * Deletes a inventario
     *
     * @param string $i_id
     */
    public function deleteAction($i_id)
    {
        $inventario = Inventario::findFirstByi_id($i_id);
        if (!$inventario) {
            $this->flash->error("inventario was not found");

            return $this->dispatcher->forward(array(
                "controller" => "inventario",
                "action" => "index"
            ));
        }

        if (!$inventario->delete()) {

            foreach ($inventario->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "inventario",
                "action" => "search"
            ));
        }

        $this->flash->success("inventario was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "inventario",
            "action" => "index"
        ));
    }
    
    public function correlativosAction(){
    	 
    }
    
    public function agregaCorrAction(){
    	if($this->request->getPost("corr") != ""){
    		$iid = $this->request->getPost("iid");
    		$inv = Inventario::findFirst("i_id = $iid");
    		$corr = $this->request->getPost("corr");
    		if($inv->i_activo == 2){
    			$corr = "G".$corr;
    		}
    		$inv->i_correlativo = $corr;
    		if($inv->save()){
    			$this->flash->success("Correlativo guardado exitosamente");
    		}else{
    			$this->flash->error("No se pudo guardar el correlativo");
    		}
    	}else{
    		$this->flash->error("No se ingres&oacute; n&uacute;mero de correlativo");
    	}
    	return $this->dispatcher->forward(array(
    			"controller" => "inventario",
    			"action" => "correlativos"
    	));
    }
    
    public function editInventarioAction(){
    	 $id = parent::gReq("id");
    	 $i = Inventario::findFirst("i_id = '$id'");
    	 $user = "";
    	 $ubic = "";
    	 if($i->u_id != null && $i->u_id != ""){
    	 	$usuario = Usuarios::findFirst("u_id = $i->u_id");
    	 	$user = $usuario->u_lanid;
    	 }else{
    	 	$ubicacion = Ubicacion::findFirst("ub_id = $i->ubicid");
    	 	$ubic = $ubicacion->ub_nombre;
    	 }
    	 
    	 $campos = [
    	 		["tv", ["desc", $i->i_descripcion], "Descripci&oacute;n"],
    	 		["d", ["fing", $i->i_fingreso], "Fecha Ingreso"],
    	 		["m", ["va", $i->i_vadquisicion], "valor Adquisici&oacute;n"],
    	 		["sel", ["encontrado", ["1" => "Verdadero", "0" => "Falso"], $i->i_encontrado], "Encontrado"],
    	 		["tv", ["ubict", $i->i_ubicacion], "Encontrado en"],
    	 		["tv", ["obs", $i->i_observaciones], "Observaciones"],    	 		
    	 		["tv", ["user", $user], "Usuario", 1],
    	 		["tv", ["ubic", $ubic], "Ubicaci&oacute;n", 1],
    	 		["tv", ["tipo", $i->i_tipo], "Tipo"],
    	 		["tv", ["marca", $i->i_marca], "Marca"],
    	 		["tv", ["mod", $i->i_modelo], "Modelo"],
    	 		["tv", ["serie", $i->i_serie], "Serie"],
    	 		["tv", ["color", $i->i_color], "Color"],
    	 		["tv", ["otros", $i->i_otros], "Otros"],
    	 		["tv", ["prov", $i->i_proveedor], "Proveedor"],
    	 		["h", ["id"], $id],
    	 		["s", ["guardar"], "Guardar"]
    	 ];
    	 
    	 $form = parent::form($campos, "inventario/guardar", "form1");
    	 parent::view("Inventario con Correlativo: $i->i_correlativo", $form);
    }
    
    public function guardarAction(){
    	$id = parent::gPost("id");
    	$i = Inventario::findFirst("i_id = $id");
    	$i->i_descripcion = parent::gPost("desc");
    	$i->i_encontrado = parent::gPost("encontrado");
    	$i->i_ubicacion = parent::gPost("ubict");
    	$i->i_observaciones = parent::gPost("obs");
    	$i->i_tipo = parent::gPost("tipo");
    	$i->i_marca = parent::gPost("marca");
    	$i->i_modelo = parent::gPost("mod");
    	$i->i_serie = parent::gPost("serie");
    	$i->i_color = parent::gPost("color");
    	$i->i_otros = parent::gPost("otros");
    	$i->i_proveedor = parent::gPost("prov");
    	if($i->update()){
    		parent::msg("Actualizaci&oacute;n de datos exitosa para correlativo: $i->i_correlativo", "s");
    	}else{
    		parent::msg("Ocurri&oacute; un error durante la transacci&oacute;n");
    	}
    	parent::forward("inventario", "activo");
    	
    }

}
