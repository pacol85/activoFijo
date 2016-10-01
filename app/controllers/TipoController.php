<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class TipoController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
    	parent::limpiar();
    	$campos = [
    			["t", ["tipo"], "Nombre"],
    			["t", ["desc"], "Descripci&oacute;n"],
    			["h", ["id"], ""],
    			["s", [""], "Crear Tipo"]
    	];
    	$js = parent::jsCargarDatos(["id", "tipo", "desc"], ["main"], ["edit"]);
    	
    	$tabla = parent::thead("tipos", ["Nombre", "Descripci&oacute;n", "Acciones"]);
    	$tipo = Tipo::find();
    	foreach ($tipo as $t){
    		$tabla = $tabla.parent::tbody([
    				$t->tipo,
    				$t->descripcion,
    				parent::a(2, "cargarDatos('".$t->id."', '".$t->tipo."', '".$t->descripcion."')", "Editar")    				
    		]);    		
    	}
    	
    	//js
    	$fields = ["id", "tipo", "desc"];
    	$otros = "";
    	$jsBotones = ["form1", "tipo/editar", "tipo/index"];
        
    	$form = parent::form($campos, "tipo/crear", "form1");
        parent::view("Tipos", $form, $tabla, [$fields, $otros, $jsBotones]);
    }
    
    /**
     * Crear ubicacion
     */
    public function crearAction()
    {
    	$tipo = parent::gPost("tipo");
		if($tipo != null && $tipo != ""){
			$t = Tipo::find("tipo = '$tipo'");
			if(count($t) > 0){
				parent::msg("El tipo $tipo ya existe");
				parent::forward("tipo", "index");
			} else {
				$ntipo = new Tipo();
				$ntipo->tipo = $tipo;
				$ntipo->descripcion = parent::gPost("desc");
				if($ntipo->save()){
					parent::msg("El tipo fue creado exitosamente", "s");
				}else{
					parent::msg("Ocurri&oacute; un error durante la transacci&oacute;n");
				}
			}
		}else{
			parent::msg("El tipo no puede quedar en blanco");
		}
		parent::forward("tipo", "index");
    }
    
    /**
     * Guardar edicion
     */
    public function editarAction()
    {
    	if(!parent::vPost("id")){
			parent::msg("Id no se carg&oacute; correctamente");
			return parent::forward("tipo", "index");
		}
		$id = parent::gPost("id");
		$t = Tipo::findFirst("id = $id");
		$tipo = parent::gPost("tipo");
		$tipos = Tipo::find("tipo like '$tipo' and id not like $id");
		if(count($tipos) > 0){
			parent::msg("El tipo $tipo ya est&aacute; siendo utilizado");
			parent::forward("tipo", "index");
		} else {
			$t->tipo = $tipo;
			$t->descripcion = parent::gPost("desc");
			if($t->update()){
				parent::msg("Edici&oacute;n exitosa", "s");
				parent::forward("tipo", "index");
			}else{
				parent::msg("Ocurri&oacute; un error durante la transacci&oacute;n");
				parent::forward("tipo", "index");
			}
		}	
    }


}
