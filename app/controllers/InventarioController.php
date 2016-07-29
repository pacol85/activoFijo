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

}
