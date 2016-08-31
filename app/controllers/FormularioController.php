<?php
// include autoloader
require_once $config->application->dompdfDir.'/autoload.inc.php';
// reference the Dompdf namespace

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Dompdf\Dompdf;

class FormularioController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for formulario
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Formulario', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "f_id";

        $formulario = Formulario::find($parameters);
        if (count($formulario) == 0) {
            $this->flash->notice("The search did not find any formulario");

            return $this->dispatcher->forward(array(
                "controller" => "formulario",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $formulario,
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
     * Edits a formulario
     *
     * @param string $f_id
     */
    public function editAction($f_id)
    {
        if (!$this->request->isPost()) {

            $formulario = Formulario::findFirstByf_id($f_id);
            if (!$formulario) {
                $this->flash->error("formulario was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "formulario",
                    "action" => "index"
                ));
            }

            $this->view->f_id = $formulario->f_id;

            $this->tag->setDefault("f_id", $formulario->f_id);
            $this->tag->setDefault("f_fecha", $formulario->f_fecha);
            $this->tag->setDefault("i_id", $formulario->i_id);
            $this->tag->setDefault("f_movimiento", $formulario->f_movimiento);
            $this->tag->setDefault("f_documento", $formulario->f_documento);
            $this->tag->setDefault("f_proveedor", $formulario->f_proveedor);
            $this->tag->setDefault("f_uanterior", $formulario->f_uanterior);
            $this->tag->setDefault("f_fechacompra", $formulario->f_fechacompra);
            $this->tag->setDefault("f_adept", $formulario->f_adept);
            $this->tag->setDefault("f_ndept", $formulario->f_ndept);
            $this->tag->setDefault("f_unuevo", $formulario->f_unuevo);
            $this->tag->setDefault("f_fechamov", $formulario->f_fechamov);
            $this->tag->setDefault("f_motivobaja", $formulario->f_motivobaja);
            $this->tag->setDefault("f_elaboradopor", $formulario->f_elaboradopor);
            $this->tag->setDefault("f_autorizadopor", $formulario->f_autorizadopor);
            $this->tag->setDefault("f_personaconta", $formulario->f_personaconta);
            $this->tag->setDefault("f_fecharevisado", $formulario->f_fecharevisado);
            $this->tag->setDefault("f_correlativo", $formulario->f_correlativo);
            $this->tag->setDefault("f_tipoinventario", $formulario->f_tipoinventario);
            
        }
    }

    /**
     * Creates a new formulario
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "formulario",
                "action" => "index"
            ));
        }

        $formulario = new Formulario();

        $formulario->f_fecha = $this->request->getPost("f_fecha");
        $formulario->i_id = $this->request->getPost("i_id");
        $formulario->f_movimiento = $this->request->getPost("f_movimiento");
        $formulario->f_documento = $this->request->getPost("f_documento");
        $formulario->f_proveedor = $this->request->getPost("f_proveedor");
        $formulario->f_uanterior = $this->request->getPost("f_uanterior");
        $formulario->f_fechacompra = $this->request->getPost("f_fechacompra");
        $formulario->f_adept = $this->request->getPost("f_adept");
        $formulario->f_ndept = $this->request->getPost("f_ndept");
        $formulario->f_unuevo = $this->request->getPost("f_unuevo");
        $formulario->f_fechamov = $this->request->getPost("f_fechamov");
        $formulario->f_motivobaja = $this->request->getPost("f_motivobaja");
        $formulario->f_elaboradopor = $this->request->getPost("f_elaboradopor");
        $formulario->f_autorizadopor = $this->request->getPost("f_autorizadopor");
        $formulario->f_personaconta = $this->request->getPost("f_personaconta");
        $formulario->f_fecharevisado = $this->request->getPost("f_fecharevisado");
        $formulario->f_correlativo = $this->request->getPost("f_correlativo");
        $formulario->f_tipoinventario = $this->request->getPost("f_tipoinventario");
        

        if (!$formulario->save()) {
            foreach ($formulario->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "formulario",
                "action" => "new"
            ));
        }

        $this->flash->success("formulario was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "formulario",
            "action" => "index"
        ));
    }

    /**
     * Saves a formulario edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "formulario",
                "action" => "index"
            ));
        }

        $f_id = $this->request->getPost("f_id");

        $formulario = Formulario::findFirstByf_id($f_id);
        if (!$formulario) {
            $this->flash->error("formulario does not exist " . $f_id);

            return $this->dispatcher->forward(array(
                "controller" => "formulario",
                "action" => "index"
            ));
        }

        $formulario->f_fecha = $this->request->getPost("f_fecha");
        $formulario->i_id = $this->request->getPost("i_id");
        $formulario->f_movimiento = $this->request->getPost("f_movimiento");
        $formulario->f_documento = $this->request->getPost("f_documento");
        $formulario->f_proveedor = $this->request->getPost("f_proveedor");
        $formulario->f_uanterior = $this->request->getPost("f_uanterior");
        $formulario->f_fechacompra = $this->request->getPost("f_fechacompra");
        $formulario->f_adept = $this->request->getPost("f_adept");
        $formulario->f_ndept = $this->request->getPost("f_ndept");
        $formulario->f_unuevo = $this->request->getPost("f_unuevo");
        $formulario->f_fechamov = $this->request->getPost("f_fechamov");
        $formulario->f_motivobaja = $this->request->getPost("f_motivobaja");
        $formulario->f_elaboradopor = $this->request->getPost("f_elaboradopor");
        $formulario->f_autorizadopor = $this->request->getPost("f_autorizadopor");
        $formulario->f_personaconta = $this->request->getPost("f_personaconta");
        $formulario->f_fecharevisado = $this->request->getPost("f_fecharevisado");
        $formulario->f_correlativo = $this->request->getPost("f_correlativo");
        $formulario->f_tipoinventario = $this->request->getPost("f_tipoinventario");
        

        if (!$formulario->save()) {

            foreach ($formulario->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "formulario",
                "action" => "edit",
                "params" => array($formulario->f_id)
            ));
        }

        $this->flash->success("formulario was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "formulario",
            "action" => "index"
        ));
    }

    /**
     * Deletes a formulario
     *
     * @param string $f_id
     */
    public function deleteAction($f_id)
    {
        $formulario = Formulario::findFirstByf_id($f_id);
        if (!$formulario) {
            $this->flash->error("formulario was not found");

            return $this->dispatcher->forward(array(
                "controller" => "formulario",
                "action" => "index"
            ));
        }

        if (!$formulario->delete()) {

            foreach ($formulario->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "formulario",
                "action" => "search"
            ));
        }

        $this->flash->success("formulario was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "formulario",
            "action" => "index"
        ));
    }
    
    /**
     * Muestra el formulario de Ingreso
     */
    public function ingresoAction()
    {
    	//$this->view->fecha = parent::fechaHoy(false);
    	$dept = Departamento::find();
    	$d = $dept->getFirst();
    	$user = Usuarios::find("u_estado = 1 AND d_id = ".$d->d_id);
    	$ubic = Ubicacion::find();
    	 
    	$campos = [
    			["l", [parent::fechaHoy(false)], "Fecha"],
    			["h2", [""], "Descripci&oacute;n del Activo o Bien"],
    			["t", ["tipo"], "Tipo"],
    			["t", ["desc"], "Descripci&oacute;n"],
    			["t", ["marca"], "Marca"],
    			["t", ["modelo"], "Modelo"],
    			["t", ["color"], "Color"],
    			["t", ["serie"], "Serie"],
    			["t", ["otros"], "Otros"],
    			["h2", [""], "Datos Ingreso"],
    			["t", ["proveedor"], "Proveedor"],
    			["m", ["valor", 0], "Valor"],
    			["r", ["asignar", ["Usuario", "Departamento"]], "Asignar"],
    			["sdb", ["dept", $dept, ["d_id", "d_nombre"]], "Dept. que Recibe"],
    			["sdb", ["user", $user, ["u_id", "u_nombre"]], "Usuario que Recibe", "udiv"],
    			["sdb", ["ubic", $ubic, ["ub_id", "ub_nombre"]], "Nueva Ubicaci&oacute;n"],
    			["t", ["doc"], "Documento No."],
    			["d", ["fechaC", parent::fechaHoy(false)], "Fecha compra"],
    			["s", [""], "Ingresar"]
    	];
    	 
    	 
    	$this->view->titulo = parent::elemento("h1", ["titulo"], "Formulario para Nuevo Activo o Bien llevado a Gasto");
    	$this->view->form = parent::form($campos, "formulario/guardarNuevo", "form1");
    }
    
    public function guardarNuevoAction() {
    	if (!$this->request->isPost()) {
    		return $this->dispatcher->forward(array(
    				"controller" => "formulario",
    				"action" => "ingreso"
    		));
    	}
    	
    	$serie = $this->request->getPost("serie");
    	
    	$formulario = Inventario::findFirstByi_serie($serie);
    	if ($formulario) {
    		$this->flash->error("Ya existe esta serie: " . $serie);
    	
    		return $this->dispatcher->forward(array(
    				"controller" => "formulario",
    				"action" => "ingreso"
    		));
    	}
    	
    	$fh = parent::fechaHoy(true);
    	
    	//crea el articulo en el inventario
    	$inventario = new Inventario();
    	$inventario->i_color = $this->request->getPost("color");
    	$inventario->i_descripcion = $this->request->getPost("desc");
    	$inventario->i_fingreso = $fh;
    	$inventario->i_marca = $this->request->getPost("marca");
    	$inventario->i_modelo = $this->request->getPost("modelo");
    	$inventario->i_otros = $this->request->getPost("otros");
    	$inventario->i_serie = $serie;
    	$inventario->i_tipo = $this->request->getPost("tipo");
    	$inventario->i_vadquisicion = $this->request->getPost("valor");
    	$inventario->u_id = $this->request->getPost("user");
    	$inventario->ubicid = $this->request->getPost("ubic");
    	$inventario->i_estado = 1;
    	$inventario->i_proveedor = $this->request->getPost("proveedor");
    	
    	//ver parametros para decidir si es Activo o Bien llevado a Gasto
    	$minBienes = Parametros::find(array("p_parametro = 'min_bienes'"));
    	$minActivos = Parametros::find(array("p_parametro = 'min_activos'"));
    	foreach ($minActivos as $ma){
    		if($inventario->i_vadquisicion < $ma->p_valor){
    			foreach ($minBienes as $mb){
    				if($inventario->i_vadquisicion >= $mb->p_valor){
    					$inventario->i_activo = 2;
    				}else{
    					$inventario->i_activo = 3;
    				}
    			}
    		}else{
    			$inventario->i_activo = 1;
    		}
    	}
    	$inventario->save();
    	
    	$formulario = new Formulario();
    	$formulario->f_fecha = $fh;
    	$formulario->f_movimiento = 1;
    	$formulario->f_documento = $this->request->getPost("doc");    	
    	$formulario->i_id = $inventario->i_id;
    	$formulario->f_fechacompra = $this->request->getPost("fechaC");    	    	
    	$formulario->f_ndept = $this->request->getPost("dept");
    	$formulario->f_unuevo = $this->request->getPost("user");
    	$formulario->ubicnueva = parent::gPost("ubic");
    	$formulario->f_fechamov = $fh;
    	$formulario->f_elaboradopor = $this->session->get("usuario");
    	$formulario->f_tipoinventario = $inventario->i_activo;
    	$fti = $formulario->f_tipoinventario;
    	
    	//obtener el ultimo correlativo:
    	$maxCorr = Formulario::maximum(
    			array(
    					"f_tipoinventario = $fti",
    					"column" => "f_correlativo"    					
    			)
    	);
    	$formulario->f_correlativo = $maxCorr+1;
    	$formulario->save();
    	
    	$this->pdfAction($formulario, $inventario, 1, false);
    }
    
    public function listUsersAction()
    {
    	$dept = $this->request->getPost("dept");
    	$dis = $this->request->getPost("disable");
    	$disable = "";
    	$select = "";
    	if($dis == 1){
    		$select = $this->tag->select(array("user",
    				Usuarios::find("u_estado = 1 AND d_id = $dept"),
    				"using" => array("u_id", "u_nombre"), "class" => "form-control", "id" => "user", "disabled" => ""));
    	}else{
    		$select = $this->tag->select(array("user",
    				Usuarios::find("u_estado = 1 AND d_id = $dept"),
    				"using" => array("u_id", "u_nombre"), "class" => "form-control", "id" => "user"));
    	}       	
    	$response = ['select' => $select, 'dept' => $dept];
       	return parent::sendJson($response);
    
    }
    
    public function pdfAction($formulario, $inventario, $movimiento, $reimpresion = false)
    {
    	// set the default timezone to use
    	$this->view->disable();
    	$fechaHoy = parent::fechaHoy(false);
    	$aob = "ACTIVO FIJO";
    	if($formulario->f_tipoinventario == 2){
    		$aob = "BIENES LLEVADOS A GASTO";
    	}
    	$nusuario = "";
    	$nubic = "";
    	$usuario = new Usuarios();
    	$usuarioA = new Usuarios();
    	/*if($reimpresion){
    		$usuario = Usuarios::findFirst($formulario->f_unuevo);
    		$nusuario = $usuario->u_nombre;
    		$dept = Departamento::findFirst($formulario->f_ndept);    		 
    	}else{*/
    		if($inventario->u_id != null && $inventario->u_id != ""){
    			$usuario = Usuarios::findFirst($inventario->u_id);
    			$nusuario = $usuario->u_nombre;
    		}else{
    			$ubic = Ubicacion::findFirst($formulario->ubicnueva);
    			$nubic = $ubic->ub_nombre;
    		}
    		$dept = Departamento::findFirst($formulario->f_ndept);
    		    		 
    	//}
    	$ousuario = "";
    	$oubic = "";
    	
    	if($formulario->f_uanterior != null && $formulario->f_uanterior != ""){
    		$usuarioA = Usuarios::findFirst($formulario->f_uanterior);
    		$ousuario = $usuarioA->u_nombre;
    	}else{
    		$ubicA = Ubicacion::findFirst($formulario->ubicanterior);
    		$oubic = $ubicA->ub_nombre;
    	}
    	$deptA = Departamento::findFirst($formulario->f_adept);
    	    	
    	//obten usuario que llena el formulario
    	$elab = Usuarios::findFirst(array("u_id = ".$formulario->f_elaboradopor));
    
    	$html =
    	'<!DOCTYPE html>
<html>
<style type="text/css">
<!--
@page {
	margin: 10px
}

.title{	
	font: bolder;
	border-width: 2px;
	background-color: blue;
	text-align: center;
	text-shadow: aqua;
	font-weight: bold;
	font-size: 11pt;
    color: white;
}

table{
    width:80%;
    border-width: 2px;
}

td{
	width: 50%;
    font-size: 9pt;
   	height: 9pt;
}
-->
</style>
<body >
	<h2 align="center" style="height: 13pt">CONTROL DE '.$aob.'</h2>
	<h3 align="center" style="height: 12pt">Correlativo:'.$formulario->f_correlativo.'</h3>
	<h4 align="center" style="height: 11pt">Fecha: '.$fechaHoy.'</h4>
	<table border="1" align="center">
		<tbody>
            <tr>
				<td class="title" colspan="2">Descripcion del activo o bien</td>
			</tr>
			<tr>
				<td align="left">Tipo:</td><td align="center">'.$inventario->i_tipo.'</td>
			</tr>
			<tr>
				<td align="left">Descripcion:</td><td align="center">'.$inventario->i_descripcion.'</td>
			</tr>
			<tr>
				<td align="left">Marca:</td><td align="center">'.$inventario->i_marca.'</td>
			</tr>
			<tr>
				<td align="left">Modelo:</td><td align="center">'.$inventario->i_modelo.'</td>
			</tr>
			<tr>
				<td align="left">Color:</td><td align="center">'.$inventario->i_color.'</td>
			</tr>
			<tr>
				<td align="left">No. Serie:</td><td align="center">'.$inventario->i_serie.'</td>
			</tr>
			<tr>
				<td align="left">No. Inventario:</td><td align="center">'.$inventario->i_correlativo.'</td>
			</tr>
			<tr>
				<td align="left">Otros:</td><td align="center">'.$inventario->i_otros.'</td>
			</tr>
		</tbody>
	</table>
	<nobr>&nbsp;</nobr>
	<table border="0" align="center">
		<tbody>
            <tr>
				<td class="title" colspan="3">Tipo de Movimiento</td>
			</tr>
			<tr>';
    if($movimiento == 1){
    	$html = $html.'<td>Ingreso <input type="checkbox" checked value="input type checkbox" /></td>';
    }else{
    	$html = $html.'<td>Ingreso <input type="checkbox" value="input type checkbox" /></td>';
    }
    if($movimiento == 2){
    	$html = $html.'<td>Traslado <input type="checkbox" checked value="input type checkbox" /></td>';
    }else{
    	$html = $html.'<td>Traslado <input type="checkbox" value="input type checkbox" /></td>';
    }
    if($movimiento == 3){
    	$html = $html.'<td>Baja <input type="checkbox" checked value="input type checkbox" /></td>';
    }else{
    	$html = $html.'<td>Baja <input type="checkbox" value="input type checkbox" /></td>';
    }
	$html = $html.'</tr>
		</tbody>
	</table>
	<nobr>&nbsp;</nobr>
	<table border="1" align="center">
		<tbody>
            <tr>
				<td class="title" colspan="2">Datos Ingreso</td>
			</tr>';
	if($movimiento == 1){
		$html = $html.'<tr>
				<td align="left">Proveedor:</td><td align="center">'.$inventario->i_proveedor.'</td>
			</tr>
			<tr>
				<td align="left">Valor:</td><td align="center">'.$inventario->i_vadquisicion.'</td>
			</tr>
			<tr>
				<td align="left">Departamento Asignado:</td><td align="center">'.$dept->d_nombre.'</td>
			</tr>
			<tr>
				<td align="left">Usuario:</td><td align="center">'.$usuario->u_nombre.'</td>
			</tr>
			<tr>
				<td align="left">No. Documento de Compra:</td><td align="center">'.$formulario->f_documento.'</td>
			</tr>
			<tr>
				<td align="left">Fecha Compra:</td><td align="center">'.$formulario->f_fechacompra.'</td>
			</tr>';
	}else{
		$html = $html.'<tr>
				<td align="left">Proveedor:</td><td align="center"></td>
			</tr>
			<tr>
				<td align="left">Valor:</td><td align="center"></td>
			</tr>
			<tr>
				<td align="left">Departamento Asignado:</td><td align="center"></td>
			</tr>
			<tr>
				<td align="left">Usuario:</td><td align="center"></td>
			</tr>
			<tr>
				<td align="left">No. Documento de Compra:</td><td align="center"></td>
			</tr>
			<tr>
				<td align="left">Fecha Compra:</td><td align="center"></td>
			</tr>';
	}
	$html = $html.'</tbody>
	</table>
	<nobr>&nbsp;</nobr>
	<table border="1" align="center">
		<tbody>
            <tr>
				<td class="title" colspan="2">Datos Traslado</td>
			</tr>';
	if($movimiento == 2){
		$html = $html.'<tr>
				<td align="left">Dept. Anterior:</td><td align="center">'.$deptA->d_nombre.'</td>
			</tr>
			<tr>
				<td align="left">Usuario Anterior:</td><td align="center">'.$ousuario.'</td>
			</tr>
			<tr>
				<td align="left">Ubicaci&oacute;n Anterior:</td><td align="center">'.$oubic.'</td>
			</tr>
			<tr>
				<td align="left">Dept. que Recibe:</td><td align="center">'.$dept->d_nombre.'</td>
			</tr>
			<tr>
				<td align="left">Usuario que Recibe:</td><td align="center">'.$nusuario.'</td>
			</tr>
			<tr>
				<td align="left">Nueva Ubicaci&oacute;n:</td><td align="center">'.$nubic.'</td>
			</tr>
			<tr>
				<td align="left">Fecha de Traslado:</td><td align="center">'.$formulario->f_fechacompra.'</td>
			</tr>';
	}else{
		$html = $html.'<tr>
				<td align="left">Dept. Anterior:</td><td align="center"></td>
			</tr>
			<tr>
				<td align="left">Usuario Anterior:</td><td align="center"></td>
			</tr>
			<tr>
				<td align="left">Dept. que Recibe:</td><td align="center"></td>
			</tr>
			<tr>
				<td align="left">Usuario que Recibe:</td><td align="center"></td>
			</tr>
			<tr>
				<td align="left">Fecha de Traslado:</td><td align="center"></td>
			</tr>';
	}
	$html = $html.'</tbody>
	</table>
	<nobr>&nbsp;</nobr>
	<table border="1" align="center">
		<tbody>
			<tr>
				<td class="title" colspan="2">Datos Baja</td>
			</tr>';	
	if($movimiento == 3){
		$html = $html.'<tr>
				<td align="left">Motivo de Baja:</td><td align="center">'.$formulario->f_motivobaja.'</td>
			</tr>
			<tr>
				<td align="left">Fecha de Baja:</td><td align="center">'.$formulario->f_fechacompra.'</td>
			</tr>
			<tr>
				<td align="left">Acci&oacute;n:</td><td align="center">'.$inventario->i_accion.'</td>
			</tr>';
	}else{
		$html = $html.'<tr>
				<td align="left">Motivo de Baja:</td><td align="center"></td>
			</tr>
			<tr>
				<td align="left">Fecha de Baja:</td><td align="center"></td>
			</tr>';
	}
	$html = $html.'</tbody>
	</table>
	<nobr>&nbsp;</nobr>';
	
				
	if($movimiento ==1 || $movimiento == 3){		
		$autorizador = Parametros::findFirst("p_parametro = 'administrador'");
		$aut = Usuarios::findFirst("u_id = ".$autorizador->p_valor);
		$pconta = Parametros::findFirst("p_parametro = 'contabilidad'");
		$conta = Usuarios::findFirst("u_id = ".$pconta->p_valor);
		$html = $html.'<table border="0" align="center">
		<tbody>
			<tr>
				<td align="left" style="width:25%">Elaborado por:</td><td align="center" style="width:25%">'.$elab->u_nombre.'</td>
				<td align="left" style="width:25%">Autorizado por:</td><td align="center" style="width:25%">'.$aut->u_nombre.'</td>
			</tr>
			<tr>
				<td align="left" style="width:25%">Firma:</td><td align="center" style="width:25%"></td>
				<td align="left" style="width:25%">Firma:</td><td align="center" style="width:25%"></td>
			</tr>
		</tbody>
	</table>
	<nobr>&nbsp;</nobr>
	<table border="0" align="center">
		<tbody>
			<tr>
				<td align="left">Recibido en Contabilidad por:</td><td>'.$conta->u_nombre.'</td>
			</tr><tr>
				<td align="left">Fecha:</td><td>____________________</td>
			</tr><tr>
				<td align="left">Firma:</td><td>____________________</td>
			</tr>
		</tbody>
	</table>
	
</body>
</html>';
	}else{
		$uod = 'Usuario';
		$nuod = 'Usuario';
		if($usuarioA->u_id == null || $usuarioA->u_id == ""){
			$uod = "Departamento";
			$amanager = Usuarios::findFirst($deptA->u_id);
			$usuarioA->u_nombre = $amanager->u_nombre;
		}
		if($usuario->u_id == null || $usuario->u_id == ""){
			$nuod = "Departamento";
			$nmanager = Usuarios::findFirst($dept->u_id);
			$usuario->u_nombre = $nmanager->u_nombre;
		}
		$html = $html.'
	<table border="0" align="center">
		<tbody>
			<tr>
				<td align="left" style="width:25%">Elaborado por:</td><td style="width:25%">'.$elab->u_nombre.'</td>
			</tr><tr>
				<td align="left" style="width:25%">Firma:</td><td style="width:25%">____________________</td>
			</tr>
		</tbody>
	</table>
	<nobr>&nbsp;</nobr>
	<table border="0" align="center">
		<tbody>
			<tr>
				<td align="left" style="width:25%">'.$uod.' anterior:</td><td align="center" style="width:25%">'.$usuarioA->u_nombre.'</td>
				<td align="left" style="width:25%">'.$nuod.' que recibe:</td><td align="center" style="width:25%">'.$usuario->u_nombre.'</td>
			</tr>
			<tr>
				<td align="left" style="width:25%">Fecha:</td><td align="center" style="width:25%"></td>
				<td align="left" style="width:25%">Fecha:</td><td align="center" style="width:25%"></td>
			</tr>
			<tr>
				<td align="left" style="width:25%">Firma:</td><td align="center" style="width:25%"></td>
				<td align="left" style="width:25%">Firma:</td><td align="center" style="width:25%"></td>
			</tr>
		</tbody>
	</table>
	
		
</body>
</html>';
	}
	
    
    	$dompdf = new Dompdf();
    	$dompdf->set_option('isHtml5ParserEnabled', true);
    	$dompdf->loadHtml($html);
    
    	// (Optional) Setup the paper size and orientation
    	$dompdf->setPaper("Letter", 'portrait'); //array(0,0,132,408), 'landscape');
    
    	// Render the HTML as PDF
    	$dompdf->render();
    
    	// Get the generated PDF file contents
    	//$pdf = $dompdf->output();
    
    	// Output the generated PDF to Browser
    	$dompdf->stream();    	
    }

    /**
     * Muestra el formulario de Traslado
     */
    public function trasladoAction()
    {
    	//$this->view->fecha = parent::fechaHoy(false);
    	$dept = Departamento::find();
    	$d = $dept->getFirst();
    	$user = Usuarios::find("u_estado = 1 AND d_id = ".$d->d_id);
    	$ubic = Ubicacion::find();
    	
    	$campos = [
    			["l", [parent::fechaHoy(false)], "Fecha"],
    			["h2", [""], "Descripci&oacute;n del Activo o Bien"],
    			["t", ["inventario"], "No. Inventario"],
    			["t", ["tipo"], "Tipo"],
    			["t", ["desc"], "Descripci&oacute;n"],
    			["t", ["marca"], "Marca"],
    			["t", ["modelo"], "Modelo"],
    			["t", ["color"], "Color"],
    			["t", ["serie"], "Serie"],
    			["t", ["otros"], "Otros"],
    			["h2", [""], "Datos Traslado"],
    			["t", ["da"], "Dept. Anterior", 1],
    			["t", ["ua"], "Usuario Anterior", 1],
    			["t", ["aubic"], "Ubicaci&oacute;n Actual", 1],
    			["r", ["asignar", ["Usuario", "Departamento"]], "Asignar"],
    			["sdb", ["dept", $dept, ["d_id", "d_nombre"]], "Dept. que Recibe"],
    			["sdb", ["user", $user, ["u_id", "u_nombre"]], "Usuario que Recibe", "udiv"],
    			["sdb", ["ubic", $ubic, ["ub_id", "ub_nombre"]], "Nueva Ubicaci&oacute;n"],
    			["d", ["fechaT", parent::fechaHoy(false)], "Fecha traslado"],
    			["s", [""], "Trasladar"]    			
    	];
    	
    	
    	$this->view->titulo = parent::elemento("h1", ["titulo"], "Formulario para Traslado de Activo o Bien llevado a Gasto");
    	$this->view->form = parent::form($campos, "formulario/trasladar", "form1");
    }
    
    public function loadItemAction()
    {
    	$i = $this->request->getPost("inventario");
    	$inv = Inventario::findFirst(array("i_correlativo like '%$i%' and i_estado = 1"));
    	
    	$user = new Usuarios();
    	$ubic = new Ubicacion();
    	//obtener usuario y depto
    	if($inv->u_id != "" || $inv->u_id != null){
    		$user = Usuarios::findFirst("u_id = ".$inv->u_id);
    		$dept = Departamento::findFirst("d_id = ".$user->d_id);
    	}else{
    		//obtener ubicacion
    		$dept = Departamento::findFirst("d_id = ".$inv->depto);
    		$ubic = Ubicacion::findFirst("ub_id = ".$inv->ubicid);
    	}
    	
    	$response = ['a' => $inv->i_tipo, 'b' => $inv->i_descripcion, 'c' => $inv->i_marca,
    			'd' => $inv->i_modelo, 'e' => $inv->i_color, 'f' => $inv->i_serie, 'g' => $inv->i_otros,
    			'u' => $user->u_nombre, 'da' => $dept->d_nombre, 'aubic' => $ubic->ub_nombre
    	];
    	return parent::sendJson($response);    
    }
    
    public function trasladarAction() {
    	if (!$this->request->isPost()) {
    		parent::forward("formulario", "traslado");
    	}
    	    	 
    	$fh = parent::fechaHoy(true);
    	 
    	//Actualiza el articulo en el inventario
    	$i = $this->request->getPost("inventario");
    	$inventario = Inventario::findFirst(array("i_correlativo = '$i'"));
    	$inventario->i_color = $this->request->getPost("color");	
    	$inventario->i_descripcion = $this->request->getPost("desc");
    	$inventario->i_marca = $this->request->getPost("marca");
    	$inventario->i_modelo = $this->request->getPost("modelo");
    	$inventario->i_otros = $this->request->getPost("otros");
    	$inventario->i_serie = $this->request->getPost("serie");
    	$inventario->i_tipo = $this->request->getPost("tipo");
    	$inventario->i_uanterior = $inventario->u_id;
    	$inventario->adepto = $inventario->depto;
    	$inventario->depto = $this->request->getPost("dept");
    	
    	$uid = $this->request->getPost("user");
    	
    	$formulario = new Formulario();
    	$formulario->i_id = $inventario->i_id;
    	$formulario->f_fecha = $fh;
    	$formulario->f_movimiento = 2;
    	$formulario->f_uanterior = $inventario->u_id;
    	$formulario->f_adept = $inventario->adepto;
    	$formulario->f_ndept = $this->request->getPost("dept");
    	$formulario->f_unuevo = $this->request->getPost("user");
    	$formulario->f_fechamov = $fh;
    	$formulario->f_fechacompra = $this->request->getPost("fechaT");
    	$formulario->f_elaboradopor = $this->session->get("usuario");
    	$formulario->f_tipoinventario = $inventario->i_activo;
    	$formulario->ubicanterior = $inventario->ubicid;
    	$formulario->ubicnueva = $this->request->getPost("ubic");
    	$fti = $formulario->f_tipoinventario;
    	 
    	//obtener el ultimo correlativo:
    	$maxCorr = Formulario::maximum(
    			array(
    					"f_tipoinventario = $fti",
    					"column" => "f_correlativo"
    			)
    			);
    	$formulario->f_correlativo = $maxCorr+1;
    	if(!$formulario->save()){
    		$this->flash->error("Ocurri&oacute; un error al salvar el formulario");
    		parent::forward("formulario", "traslado");
    	}
    	
    	$inventario->u_id = $this->request->getPost("user");
    	$inventario->ubicid = $this->request->getPost("ubic");    	 
    	if(!$inventario->save()){
    		$this->flash->error("Ocurri&oacute; un error al salvar el inventario");
    		parent::forward("formulario", "traslado");
    	}
    	 
    	$this->pdfAction($formulario, $inventario, 2);
    }

    /**
     * Muestra el formulario de Baja
     */
    public function bajaAction()
    {
    	$campos = [
    			["l", [parent::fechaHoy(false)], "Fecha"],
    			["h2", [""], "Descripci&oacute;n del Activo o Bien"],
    			["t", ["inventario"], "No. Inventario"],
    			["t", ["tipo"], "Tipo"],
    			["t", ["desc"], "Descripci&oacute;n"],
    			["t", ["marca"], "Marca"],
    			["t", ["modelo"], "Modelo"],
    			["t", ["color"], "Color"],
    			["t", ["serie"], "Serie"],
    			["t", ["otros"], "Otros"],
    			["h2", [""], "Datos Baja"],
    			["t", ["motivo"], "Motivo de Baja"],
    			["d", ["fechaB", parent::fechaHoy(false)], "Fecha de Baja"],
    			["t", ["accion"], "Acci&oacute;n a tomar"],
    			["s", [""], "Bajar"]    			
    	];
    	
    	$this->view->titulo = parent::elemento("h1", ["titulo"], "Formulario para Baja de Activo o Bien llevado a Gasto");
    	$this->view->form = parent::form($campos, "formulario/bajar", "form1");
    }
    

    public function bajarAction() {
    	if (!$this->request->isPost()) {
    		return $this->dispatcher->forward(array(
    				"controller" => "formulario",
    				"action" => "baja"
    		));
    	}
    	 
    	$fh = parent::fechaHoy(true);
    
    	//Actualiza el articulo en el inventario
    	$i = $this->request->getPost("inventario");
    	$inventario = Inventario::findFirst(array("i_correlativo = '$i'"));
    	$inventario->i_color = $this->request->getPost("color");
    	$inventario->i_descripcion = $this->request->getPost("desc");
    	$inventario->i_marca = $this->request->getPost("marca");
    	$inventario->i_modelo = $this->request->getPost("modelo");
    	$inventario->i_otros = $this->request->getPost("otros");
    	$inventario->i_serie = $this->request->getPost("serie");
    	$inventario->i_tipo = $this->request->getPost("tipo");
    	$inventario->i_estado = 2;
    	$inventario->i_accion = $this->request->getPost("accion");
    	$inventario->save();
    
    	$formulario = new Formulario();
    	$formulario->i_id = $inventario->i_id;
    	$formulario->f_fecha = $fh;
    	$formulario->f_movimiento = 3;
    	$user = Usuarios::findFirst($inventario->u_id);
    	$dept = Departamento::findFirst($user->d_id);
    	$formulario->f_fechamov = $fh;
    	$formulario->f_fechacompra = $this->request->getPost("fechaB");
    	$formulario->f_elaboradopor = $this->session->get("usuario");
    	$formulario->f_tipoinventario = $inventario->i_activo;
    	$formulario->f_motivobaja = $this->request->getPost("motivo");
    	$fti = $formulario->f_tipoinventario;
    
    	//obtener el ultimo correlativo:
    	$maxCorr = Formulario::maximum(
    			array(
    					"f_tipoinventario = $fti",
    					"column" => "f_correlativo"
    			)
    			);
    	$formulario->f_correlativo = $maxCorr+1;
    	$formulario->save();
    	 
    	
    
    
    	/*
    	 if (!$formulario->save()) {
    	  
    	 foreach ($formulario->getMessages() as $message) {
    	 $this->flash->error($message);
    	 }
    	  
    	 return $this->dispatcher->forward(array(
    	 "controller" => "formulario",
    	 "action" => "edit",
    	 "params" => array($formulario->f_id)
    	 ));
    	 }
    	 */
    	//$this->flash->success("formulario was updated successfully");
    
    	$this->pdfAction($formulario, $inventario, 3);
    }
    
    /**
     * Ingresa a reimpresion de formularios
     */
    public function reimprimirAction()
    {
    	
    }
    
    public function reimprimeAction()
    {
    	 if($this->request->getPost("corr") != ""){
    	 	$corr = $this->request->getPost("corr");
    	 	$tipo = $this->request->getPost("tipoF");
    	 	$form = Formulario::findFirst("f_correlativo = $corr and f_tipoinventario = $tipo");
    	 	if($form == null){
    	 		$this->flash->error("El correlativo ingresado no existe");
    	 	}else{
    	 		$inv = Inventario::findFirst("i_id = $form->i_id");
    	 		$this->pdfAction($form, $inv, $form->f_movimiento, true);
    	 	}    	 	
    	 }else{
    	 	$this->flash->error("Debe ingresar un correlativo");
    	 }
    	 return $this->dispatcher->forward(array(
    	 		"controller" => "formulario",
    	 		"action" => "reimprimir"
    	 ));
    }
    
    /**
     * Ingresa a reporte de movimientos del mes
     */    
    public function movimientosAction(){
    	if($this->request->isGet()){
    		$r = $this->request->get("r");
    		if($r != 1){
    			$this->session->set("search", "");
    		}else {
	    		$actual = date("Y-m");
	    		$tot = $this->totales($actual);
	    	
		    	$this->session->set("mov", $tot["mes"]);
		    	$this->session->set("total", $tot["full"]);
	    	}
    	}
    	
    	//campos
    	$anios = Formulario::find();
    	$list = array();
    	foreach ($anios as $anio){
    		$a = date('Y', strtotime($anio->f_fecha));
    		//$a = date_format($anio->f_fecha, 'Y');
    		if(!array_key_exists($a, $list)){
    			$list[$a] = $a;
    		}
    	}
    	krsort($list);
    	
    	$meses = array("1" => "Enero", "2" => "Febrero",
        		"3" => "Marzo", "4" => "Abril", "5" => "Mayo", "6" => "Junio",
        		"7" => "Julio", "8" => "Agosto", "9" => "Septiembre", "10" => "Octubre",
        		"11" => "Noviembre", "12" => "Diciembre");
    	$m = date('m');
    	
    	$movMes = $this->session->get("mov");
    	$mm = "$ ".number_format($movMes,2);
    	$tot = $this->session->get("total");
    	$t = "$ ".number_format($tot,2);
    	
    	$campos = [
    			["lf", ["opciones"], "Seleccionar A&ntilde;o y Mes"],
    			["sel", ["anio", $list], "A&ntilde;o:"],
    			["sel", ["mes", $meses, $m], "Mes:"],
    			["s", [], "Cargar"],
    			["enter", [], ""],
    			["l", [$mm], "Total por movimientos:"],
    			["l", [$t], "Total a este mes:"]
    	];
    	
    	//tabla
    	$tabla = parent::thead("inv", ["Formulario", "Tipo", "Fecha", "Inventario",
    			"Serie", "Proveedor", "Movimiento", "Valor Adquisici&oacute;n", "Acciones"]);
    	
    	$search = $this->session->get("search");
    	if($search != null && $search != ""){
    		$flist = Formulario::find(array($search));
    	}else{
    		$actual = date("Y-m");
    		$flist = Formulario::find(array("f_fecha > '$actual'"));
    	}
    	
    	foreach ($flist as $f){
    		$tabla = $tabla."<tr>";
    		if($f->f_tipoinventario == 1){
    			$tp = "Activo Fijo";
    		}else $tp ="Gasto";
    		$inv = Inventario::findFirst("i_id = $f->i_id");
    		if($f->f_movimiento == 1){
    			$m = "Ingreso";
    		}elseif ($f->f_movimiento == 2){
    			$m =  "Traslado";
    		}else $m = "Baja";
    		
    		$tabla = $tabla.parent::td([
    				$f->f_correlativo,
    				$tp,
    				date("Y-m-d",strtotime($f->f_fecha)),
    				$inv->i_correlativo,
    				$inv->i_serie,
    				$inv->i_proveedor,
    				$m,
    				number_format($inv->i_vadquisicion,2),
    				"<a href='formulario/reimprime2?c=$f->f_correlativo&t=$f->f_tipoinventario'>Reimprimir</a>"
    		]);
    		$tabla = $tabla."</tr>";
    	}
    	
    	$tabla = parent::elemento("enter", [], "").$tabla;
    	$this->view->titulo = parent::elemento("h1", ["movMes"], "Movimientos del Mes");
    	$this->view->form = parent::form($campos, "formulario/listado");
    	$this->view->tabla = parent::ftable($tabla);
    }
    
    public function totales($mes){
    	$ym = explode("-",$mes);
    	$tot = array();
    	$tot["mes"] = 0;
    	$tot["full"] = 0;
    	$mmas1 = $ym[0]."-".str_pad($ym[1]+1, 2, "00", STR_PAD_LEFT);
    	$ingresos = Formulario::find("f_movimiento = 1 and f_fecha > '$mes' and f_fecha < '".$mmas1."'");
    	$bajas = Formulario::find("f_movimiento = 3 and f_fecha > '$mes' and f_fecha < '".$mmas1."'");
    	//$this->flash->warning("$mes y ".($mes+1));
    	$sumI = 0;
    	$sumB = 0;
    	
    	foreach ($ingresos as $i){
    		$invi = Inventario::findFirst("i_id = $i->i_id");
    		$sumI = $sumI + $invi->i_vadquisicion;    		    		
    	}
    	foreach ($bajas as $b){
    		$invi2 = Inventario::findFirst("i_id = $b->i_id");
    		$sumB = $sumB + $invi2->i_vadquisicion;    		
    	}
    	
    	$cierre = Cierre::findFirst(array("mes between '$mes' and '".$mmas1."'"));
    	if($cierre){
    		$tva = $cierre->valor;    		
    	}else{
    		$tva = Inventario::sum(
    				array("column" => "i_vadquisicion",
    						"conditions" => "i_estado = 1
    					and i_fingreso < '".$mmas1."'"));    				 
    	}
    	$tva = round($tva, 2);
    	
    	$mov = $sumI-$sumB;
    	$mov = round($mov,2);
    	$tot["mes"] = $mov;
    	$tot["full"] = $tva;
    	return $tot;
    }
    
    public function listadoAction(){
    	$anio = $this->request->getPost("anio");
    	$mes = $this->request->getPost("mes");
    	$smesa = str_pad($mes, 2, "00", STR_PAD_LEFT);
    	$smesb = str_pad($mes+1, 2, "00", STR_PAD_LEFT);
    	$query = "f_fecha > '$anio-$smesa' AND f_fecha < '$anio-$smesb'";
    	$this->session->set("search", $query);
    	$this->flash->notice("Mostrando resultado para $anio-$mes");
    	
    	$tot = $this->totales("$anio-$smesa");
    	
    	$this->session->set("mov", $tot["mes"]);
    	$this->session->set("total", $tot["full"]);
    	
    	return $this->dispatcher->forward(array(
    			"controller" => "formulario",
    			"action" => "movimientos"
    	));
    }
    
    public function reimprime2Action()
    {
    	if($this->request->get("c") != ""){
    		$corr = $this->request->get("c");
    		$tipo = $this->request->get("t");
    		$form = Formulario::findFirst("f_correlativo = $corr and f_tipoinventario = $tipo");
    		if($form == null){
    			$this->flash->error("El correlativo ingresado no existe");
    		}else{
    			$inv = Inventario::findFirst("i_id = $form->i_id");
    			$this->pdfAction($form, $inv, $form->f_movimiento, true);    			 
    		}
    	}else{
    		$this->flash->error("No se encontr&oacte; el formulario");
    		return $this->dispatcher->forward(array(
    				"controller" => "formulario",
    				"action" => "movimientos"
    		));
    	}
    	 
    }
    
    /**
     * Ingresa a reporte de movimientos del mes
     */
    public function pendientesAction(){
    	//tabla
    	$tabla = parent::thead("pend", ["Formulario", "Tipo", "Fecha", "Inventario",
    			"Serie", "Proveedor", "Movimiento", "Valor Adquisici&oacute;n", "Acciones"]);
    	
    	$uid = $this->session->get("usuario");
    	$pAdmin = Parametros::findFirst("p_id = 3");
    	$pConta = Parametros::findFirst("p_id = 4");
    	$flist = array();
    	$pid = 0;
    	if ($uid == $pAdmin->p_valor){
    		$pid = 3;
    		$flist = Formulario::find(
    				array("(f_movimiento = 1 or f_movimiento = 3) 
    						and f_autorizadopor is NULL")
    				);
    	}
    	if ($uid == $pConta->p_valor){
    		$pid = 4;
    		$flist = Formulario::find(
    				array("(f_movimiento = 1 or f_movimiento = 3)
    						and f_autorizadopor is not NULL and f_personaconta is null")
    				);
    	}
    	$tabla = $this->dibujaTabla($flist, 1, $tabla, $pid);
    	$manager = Departamento::find("u_id = $uid");
    	foreach ($manager as $m){
    		$flist2 = Formulario::find(
    				array("f_movimiento = 2
    						and f_autorizadopor is NULL
    						and (f_uanterior = $uid or f_adept = ".$m->d_id.")"
    				));
    		$tabla = $this->dibujaTabla($flist2, 2, $tabla, 0);
    		
    		$flist3 = Formulario::find(
    				array("f_movimiento = 2
    						and f_personaconta is NULL
    						and (f_unuevo = $uid or f_ndept = ".$m->d_id.")"
    				));
    		$tabla = $this->dibujaTabla($flist3, 3, $tabla, 0);
    	}    	
    	 
    	$tabla = parent::elemento("enter", [], "").$tabla;
    	$this->view->titulo = parent::elemento("h1", ["pend"], "Pendientes de Aprobar");
    	$this->view->tabla = parent::ftable($tabla);
    }
    
    public function dibujaTabla($flist, $accion, $tabla, $pid){
    	foreach ($flist as $f){
    		$tabla = $tabla."<tr>";
    		if($f->f_tipoinventario == 1){
    			$tp = "Activo Fijo";
    		}else $tp ="Gasto";
    		$inv = Inventario::findFirst("i_id = $f->i_id");
    		if($f->f_movimiento == 1){
    			$m = "Ingreso";
    		}elseif ($f->f_movimiento == 2){
    			$m =  "Traslado";
    		}else $m = "Baja";
    	
    		$tabla = $tabla.parent::td([
    				$f->f_correlativo,
    				$tp,
    				date("Y-m-d",strtotime($f->f_fecha)),
    				$inv->i_correlativo,
    				$inv->i_serie,
    				$inv->i_proveedor,
    				$m,
    				number_format($inv->i_vadquisicion,2),
    				"<a href='formulario/aprobar?pid=".$pid."&fid=".$f->f_id."&a=".$accion."'>Aprobar</a> | <a href='formulario/reimprime2?c=$f->f_correlativo&t=$f->f_tipoinventario'>Pdf</a>"
    		]);
    		$tabla = $tabla."</tr>";
    	}
    	return $tabla;
    }
    
    public function aprobarAction(){
    	$acccion = $this->request->get("a");
    	$fid = $this->request->get("fid");
    	
    	$form = Formulario::findFirst("f_id = $fid");
    	switch ($acccion){
    		case 1 :
    			$pid = $this->request->get("pid");
    			$user = $this->session->get("usuario");
    			if($pid == 3){
    				$form->f_autorizadopor = $user;
    				$form->f_fechaaprobado = parent::fechaHoy(true);
    			}else{
    				$form->f_personaconta = $user;
    				$form->f_fecharevisado = parent::fechaHoy(true);
    			}
    			break;
    		case 2 :
    			$form->f_autorizadopor = $form->f_uanterior;
    			$form->f_fechaaprobado = parent::fechaHoy(true);
    			break;
    		case 3 :
    			$form->f_personaconta = $form->f_unuevo;
    			$form->f_fecharevisado = parent::fechaHoy(true);
    			break;
    	}
    	if($form->save()){
    		$this->flash->success("Aprobaci&oacute;n exitosa");
    	}else{
    		$this->flash->error("Ocurri&oacute; un error durante la aprobaci&oacute;n");
    	}    	
    	return $this->dispatcher->forward(array(
    			"controller" => "formulario",
    			"action" => "pendientes"
    	));
    	
    }
    
}
