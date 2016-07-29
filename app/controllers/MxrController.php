<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class MxrController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for mxr
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Mxr', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "r_id";

        $mxr = Mxr::find($parameters);
        if (count($mxr) == 0) {
            $this->flash->notice("The search did not find any mxr");

            return $this->dispatcher->forward(array(
                "controller" => "mxr",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $mxr,
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
     * Edits a mxr
     *
     * @param string $r_id
     */
    public function editAction($r_id)
    {
        if (!$this->request->isPost()) {

            $mxr = Mxr::findFirstByr_id($r_id);
            if (!$mxr) {
                $this->flash->error("mxr was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "mxr",
                    "action" => "index"
                ));
            }

            $this->view->r_id = $mxr->r_id;

            $this->tag->setDefault("r_id", $mxr->r_id);
            $this->tag->setDefault("m_id", $mxr->m_id);
            
        }
    }

    /**
     * Creates a new mxr
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "mxr",
                "action" => "index"
            ));
        }

        $mxr = new Mxr();

        $mxr->r_id = $this->request->getPost("r_id");
        $mxr->m_id = $this->request->getPost("m_id");
        

        if (!$mxr->save()) {
            foreach ($mxr->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "mxr",
                "action" => "new"
            ));
        }

        $this->flash->success("mxr was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "mxr",
            "action" => "index"
        ));
    }

    /**
     * Saves a mxr edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "mxr",
                "action" => "index"
            ));
        }

        $r_id = $this->request->getPost("r_id");

        $mxr = Mxr::findFirstByr_id($r_id);
        if (!$mxr) {
            $this->flash->error("mxr does not exist " . $r_id);

            return $this->dispatcher->forward(array(
                "controller" => "mxr",
                "action" => "index"
            ));
        }

        $mxr->r_id = $this->request->getPost("r_id");
        $mxr->m_id = $this->request->getPost("m_id");
        

        if (!$mxr->save()) {

            foreach ($mxr->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "mxr",
                "action" => "edit",
                "params" => array($mxr->r_id)
            ));
        }

        $this->flash->success("mxr was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "mxr",
            "action" => "index"
        ));
    }

    /**
     * Deletes a mxr
     *
     * @param string $r_id
     */
    public function deleteAction($r_id)
    {
        $mxr = Mxr::findFirstByr_id($r_id);
        if (!$mxr) {
            $this->flash->error("mxr was not found");

            return $this->dispatcher->forward(array(
                "controller" => "mxr",
                "action" => "index"
            ));
        }

        if (!$mxr->delete()) {

            foreach ($mxr->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "mxr",
                "action" => "search"
            ));
        }

        $this->flash->success("mxr was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "mxr",
            "action" => "index"
        ));
    }

}
