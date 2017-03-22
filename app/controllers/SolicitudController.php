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
    	parent::limpiar();
    	$tabla = parent::thead("solicitudes", ["#", "Usuario", "Inventario err&oacute;neo", "Apertura", "Cierre", "Acciones"]);
    	$solicitudes = Solicitudes::find();
    	foreach ($solicitudes as $s){
    		$user = Usuarios::findFirst("u_id = $s->usuario");
    		$cerrar = "Cerrar";
    		if($s->estado == 2){
    			$cerrar = "Reaperturar";
    		}
    		$tabla = $tabla.parent::tbody([
    				$s->id,
    				$user->u_nombre,
    				$s->descripcion,
    				$s->fapertura,
    				$s->fcierre,
    				parent::a(2, "solicitud/cerrar/$s->id", $cerrar)
    		]);    		
    	}
    	
    	parent::view("C&oacute;digos err&oacute;neos", "", $tabla);
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
