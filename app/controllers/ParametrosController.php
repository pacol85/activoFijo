<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class ParametrosController extends ControllerBase
{
	/**
	 * Index action
	 */
	public function indexAction()
	{
		$this->persistent->parameters = null;
		$dept = new Departamento();
		$campos = [
				["t",["uNombre"], "Nombre"],
				["t", ["uApellido"], "Apellido"],
				["t", ["uCodigo"], "C&oacute;digo"],
				["p", ["uPass"], "Contrase&ntilde;a"],
				["p", ["uPass2"], "Repita Contrase&ntilde;a"],
				["h", ["activo"], "1"],
				["sbd", ["depto", $dept, array("d_id", "d_nombre")], "Departamento"],
				["s", [""], "Agregar"]
		];
		$action = "usuarios/nuevo";
		$this->view->elem = parent::form($campos, $action);
	}
}