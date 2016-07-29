<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class DepartamentoController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for departamento
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Departamento', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "d_id";

        $departamento = Departamento::find($parameters);
        if (count($departamento) == 0) {
            $this->flash->notice("The search did not find any departamento");

            return $this->dispatcher->forward(array(
                "controller" => "departamento",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $departamento,
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
     * Edits a departamento
     *
     * @param string $d_id
     */
    public function editAction($d_id)
    {
        if (!$this->request->isPost()) {

            $departamento = Departamento::findFirstByd_id($d_id);
            if (!$departamento) {
                $this->flash->error("departamento was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "departamento",
                    "action" => "index"
                ));
            }

            $this->view->d_id = $departamento->d_id;

            $this->tag->setDefault("d_id", $departamento->d_id);
            $this->tag->setDefault("d_nombre", $departamento->d_nombre);
            $this->tag->setDefault("d_descripcion", $departamento->d_descripcion);
            $this->tag->setDefault("u_id", $departamento->u_id);
            
        }
    }

    /**
     * Creates a new departamento
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "departamento",
                "action" => "index"
            ));
        }

        $departamento = new Departamento();

        $departamento->d_nombre = $this->request->getPost("d_nombre");
        $departamento->d_descripcion = $this->request->getPost("d_descripcion");
        $departamento->u_id = $this->request->getPost("u_id");
        

        if (!$departamento->save()) {
            foreach ($departamento->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "departamento",
                "action" => "new"
            ));
        }

        $this->flash->success("departamento was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "departamento",
            "action" => "index"
        ));
    }

    /**
     * Saves a departamento edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "departamento",
                "action" => "index"
            ));
        }

        $d_id = $this->request->getPost("d_id");

        $departamento = Departamento::findFirstByd_id($d_id);
        if (!$departamento) {
            $this->flash->error("departamento does not exist " . $d_id);

            return $this->dispatcher->forward(array(
                "controller" => "departamento",
                "action" => "index"
            ));
        }

        $departamento->d_nombre = $this->request->getPost("d_nombre");
        $departamento->d_descripcion = $this->request->getPost("d_descripcion");
        $departamento->u_id = $this->request->getPost("u_id");
        

        if (!$departamento->save()) {

            foreach ($departamento->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "departamento",
                "action" => "edit",
                "params" => array($departamento->d_id)
            ));
        }

        $this->flash->success("departamento was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "departamento",
            "action" => "index"
        ));
    }

    /**
     * Deletes a departamento
     *
     * @param string $d_id
     */
    public function deleteAction($d_id)
    {
        $departamento = Departamento::findFirstByd_id($d_id);
        if (!$departamento) {
            $this->flash->error("departamento was not found");

            return $this->dispatcher->forward(array(
                "controller" => "departamento",
                "action" => "index"
            ));
        }

        if (!$departamento->delete()) {

            foreach ($departamento->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "departamento",
                "action" => "search"
            ));
        }

        $this->flash->success("departamento was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "departamento",
            "action" => "index"
        ));
    }

}
