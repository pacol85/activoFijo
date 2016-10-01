<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class SolicitudController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
    	$this->persistent->parameters = null;
    	$this->tag->resetInput();
    	$campos = [
    			["t", ["nombre"], "Ubicaci&oacute;n"],
    			["t", ["desc"], "Descripci&oacute;n"],
    			["h", ["ubid"], ""],
    			["s", [""], "Crear Ubicacion"]
    	];
    	$js = parent::jsCargarDatos(["nombre", "desc", "ubid"], ["main"], ["edit"]);
    	
    	$tabla = parent::thead("ubicacion", ["Nombre", "Descripci&oacute;n", "Acciones"]);
    	$ubic = Ubicacion::find();
    	foreach ($ubic as $u){
    		$tabla = $tabla.parent::tbody([
    				$u->ub_nombre,
    				$u->ub_descripcion,
    				"<a onClick=\"cargarDatos('".$u->ub_nombre."','".$u->ub_descripcion.
    				"','$u->ub_id');\">Editar</a>"
    		]);    		
    	}
    	
    	$this->view->js = $js;
        $this->view->titulo = parent::elemento("h1", ["ubic"], "Ubicaci&oacute;n");
        $this->view->form = parent::form($campos, "ubicacion/crear", "form1");
        $this->view->botones = parent::elemento("bg", [["edit", "guardarCambio()", "Editar"],["cancel", "cancelar()", "Cancelar"]], "");
        $this->view->tabla = parent::ftable($tabla);
    }
    
    /**
     * Crear ubicacion
     */
    public function crearAction()
    {
    	 $nombre = $this->request->getPost("nombre");
    	 if($nombre != ""){
    	 	$ubic = new Ubicacion();
    	 	$ubic->ub_nombre = $nombre;
    	 	$ubic->ub_descripcion = $this->request->getPost("desc");
    	 	if($ubic->save()){
    	 		$this->flash->success("Creaci&oacute;n exitosa de Ubicaci&oacute;n");
    	 	}else{
    	 		$this->flash->error("Ocurri&oacute; un error durante la operaci&oacute;n");
    	 	}
    	 }else{
    	 	$this->flash->error("El nombre de la ubicaci&oacute;n no puede quedar en blanco");
    	 }
    	 parent::forward("ubicacion", "index");
    }
    
    /**
     * Guardar edicion
     */
    public function editarAction()
    {
    	$nombre = $this->request->getPost("nombre");
    	$ubid = $this->request->getPost("ubid");
    	if($nombre != ""){
    		$ubic = Ubicacion::findFirst("ub_id = $ubid");
    		$ubic->ub_nombre = $nombre;
    		$ubic->ub_descripcion = $this->request->getPost("desc");
    		if($ubic->save()){
    			$this->flash->success("Edici&oacute;n exitosa de Ubicaci&oacute;n");
    		}else{
    			$this->flash->error("Ocurri&oacute; un error durante la operaci&oacute;n");
    		}
    	}else{
    		$this->flash->error("El nombre de la ubicaci&oacute;n no puede quedar en blanco");
    	}
    	parent::forward("ubicacion", "index");
    }


}
