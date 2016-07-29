<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class FechainventarioController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for fechainventario
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Fechainventario', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "f_id";

        $fechainventario = Fechainventario::find($parameters);
        if (count($fechainventario) == 0) {
            $this->flash->notice("The search did not find any fechainventario");

            return $this->dispatcher->forward(array(
                "controller" => "fechainventario",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $fechainventario,
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
     * Edits a fechainventario
     *
     * @param string $f_id
     */
    public function editAction($f_id)
    {
        if (!$this->request->isPost()) {

            $fechainventario = Fechainventario::findFirstByf_id($f_id);
            if (!$fechainventario) {
                $this->flash->error("fechainventario was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "fechainventario",
                    "action" => "index"
                ));
            }

            $this->view->f_id = $fechainventario->f_id;

            $this->tag->setDefault("f_id", $fechainventario->f_id);
            $this->tag->setDefault("f_fechainventariado", $fechainventario->f_fechainventariado);
            $this->tag->setDefault("f_comentario", $fechainventario->f_comentario);
            $this->tag->setDefault("i_id", $fechainventario->i_id);
            
        }
    }

    /**
     * Creates a new fechainventario
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "fechainventario",
                "action" => "index"
            ));
        }

        $fechainventario = new Fechainventario();

        $fechainventario->f_fechainventariado = $this->request->getPost("f_fechainventariado");
        $fechainventario->f_comentario = $this->request->getPost("f_comentario");
        $fechainventario->i_id = $this->request->getPost("i_id");
        

        if (!$fechainventario->save()) {
            foreach ($fechainventario->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "fechainventario",
                "action" => "new"
            ));
        }

        $this->flash->success("fechainventario was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "fechainventario",
            "action" => "index"
        ));
    }

    /**
     * Saves a fechainventario edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "fechainventario",
                "action" => "index"
            ));
        }

        $f_id = $this->request->getPost("f_id");

        $fechainventario = Fechainventario::findFirstByf_id($f_id);
        if (!$fechainventario) {
            $this->flash->error("fechainventario does not exist " . $f_id);

            return $this->dispatcher->forward(array(
                "controller" => "fechainventario",
                "action" => "index"
            ));
        }

        $fechainventario->f_fechainventariado = $this->request->getPost("f_fechainventariado");
        $fechainventario->f_comentario = $this->request->getPost("f_comentario");
        $fechainventario->i_id = $this->request->getPost("i_id");
        

        if (!$fechainventario->save()) {

            foreach ($fechainventario->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "fechainventario",
                "action" => "edit",
                "params" => array($fechainventario->f_id)
            ));
        }

        $this->flash->success("fechainventario was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "fechainventario",
            "action" => "index"
        ));
    }

    /**
     * Deletes a fechainventario
     *
     * @param string $f_id
     */
    public function deleteAction($f_id)
    {
        $fechainventario = Fechainventario::findFirstByf_id($f_id);
        if (!$fechainventario) {
            $this->flash->error("fechainventario was not found");

            return $this->dispatcher->forward(array(
                "controller" => "fechainventario",
                "action" => "index"
            ));
        }

        if (!$fechainventario->delete()) {

            foreach ($fechainventario->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "fechainventario",
                "action" => "search"
            ));
        }

        $this->flash->success("fechainventario was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "fechainventario",
            "action" => "index"
        ));
    }

}
