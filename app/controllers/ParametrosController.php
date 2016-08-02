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
	}
}