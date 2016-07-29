<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class UbicacionController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for ubicacion
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Ubicacion', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "ub_id";

        $ubicacion = Ubicacion::find($parameters);
        if (count($ubicacion) == 0) {
            $this->flash->notice("The search did not find any ubicacion");

            return $this->dispatcher->forward(array(
                "controller" => "ubicacion",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $ubicacion,
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
     * Edits a ubicacion
     *
     * @param string $ub_id
     */
    public function editAction($ub_id)
    {
        if (!$this->request->isPost()) {

            $ubicacion = Ubicacion::findFirstByub_id($ub_id);
            if (!$ubicacion) {
                $this->flash->error("ubicacion was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "ubicacion",
                    "action" => "index"
                ));
            }

            $this->view->ub_id = $ubicacion->ub_id;

            $this->tag->setDefault("ub_id", $ubicacion->ub_id);
            $this->tag->setDefault("ub_nombre", $ubicacion->ub_nombre);
            $this->tag->setDefault("ub_descripcion", $ubicacion->ub_descripcion);
            
        }
    }

    /**
     * Creates a new ubicacion
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "ubicacion",
                "action" => "index"
            ));
        }

        $ubicacion = new Ubicacion();

        $ubicacion->ub_id = $this->request->getPost("ub_id");
        $ubicacion->ub_nombre = $this->request->getPost("ub_nombre");
        $ubicacion->ub_descripcion = $this->request->getPost("ub_descripcion");
        

        if (!$ubicacion->save()) {
            foreach ($ubicacion->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "ubicacion",
                "action" => "new"
            ));
        }

        $this->flash->success("ubicacion was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "ubicacion",
            "action" => "index"
        ));
    }

    /**
     * Saves a ubicacion edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "ubicacion",
                "action" => "index"
            ));
        }

        $ub_id = $this->request->getPost("ub_id");

        $ubicacion = Ubicacion::findFirstByub_id($ub_id);
        if (!$ubicacion) {
            $this->flash->error("ubicacion does not exist " . $ub_id);

            return $this->dispatcher->forward(array(
                "controller" => "ubicacion",
                "action" => "index"
            ));
        }

        $ubicacion->ub_id = $this->request->getPost("ub_id");
        $ubicacion->ub_nombre = $this->request->getPost("ub_nombre");
        $ubicacion->ub_descripcion = $this->request->getPost("ub_descripcion");
        

        if (!$ubicacion->save()) {

            foreach ($ubicacion->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "ubicacion",
                "action" => "edit",
                "params" => array($ubicacion->ub_id)
            ));
        }

        $this->flash->success("ubicacion was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "ubicacion",
            "action" => "index"
        ));
    }

    /**
     * Deletes a ubicacion
     *
     * @param string $ub_id
     */
    public function deleteAction($ub_id)
    {
        $ubicacion = Ubicacion::findFirstByub_id($ub_id);
        if (!$ubicacion) {
            $this->flash->error("ubicacion was not found");

            return $this->dispatcher->forward(array(
                "controller" => "ubicacion",
                "action" => "index"
            ));
        }

        if (!$ubicacion->delete()) {

            foreach ($ubicacion->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "ubicacion",
                "action" => "search"
            ));
        }

        $this->flash->success("ubicacion was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "ubicacion",
            "action" => "index"
        ));
    }

}
