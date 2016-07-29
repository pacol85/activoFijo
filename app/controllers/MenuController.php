<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class MenuController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for menu
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Menu', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "m_id";

        $menu = Menu::find($parameters);
        if (count($menu) == 0) {
            $this->flash->notice("The search did not find any menu");

            return $this->dispatcher->forward(array(
                "controller" => "menu",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $menu,
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
     * Edits a menu
     *
     * @param string $m_id
     */
    public function editAction($m_id)
    {
        if (!$this->request->isPost()) {

            $menu = Menu::findFirstBym_id($m_id);
            if (!$menu) {
                $this->flash->error("menu was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "menu",
                    "action" => "index"
                ));
            }

            $this->view->m_id = $menu->m_id;

            $this->tag->setDefault("m_id", $menu->m_id);
            $this->tag->setDefault("m_label", $menu->m_label);
            $this->tag->setDefault("m_href", $menu->m_href);
            $this->tag->setDefault("m_parent", $menu->m_parent);
            
        }
    }

    /**
     * Creates a new menu
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "menu",
                "action" => "index"
            ));
        }

        $menu = new Menu();

        $menu->m_id = $this->request->getPost("m_id");
        $menu->m_label = $this->request->getPost("m_label");
        $menu->m_href = $this->request->getPost("m_href");
        $menu->m_parent = $this->request->getPost("m_parent");
        

        if (!$menu->save()) {
            foreach ($menu->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "menu",
                "action" => "new"
            ));
        }

        $this->flash->success("menu was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "menu",
            "action" => "index"
        ));
    }

    /**
     * Saves a menu edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "menu",
                "action" => "index"
            ));
        }

        $m_id = $this->request->getPost("m_id");

        $menu = Menu::findFirstBym_id($m_id);
        if (!$menu) {
            $this->flash->error("menu does not exist " . $m_id);

            return $this->dispatcher->forward(array(
                "controller" => "menu",
                "action" => "index"
            ));
        }

        $menu->m_id = $this->request->getPost("m_id");
        $menu->m_label = $this->request->getPost("m_label");
        $menu->m_href = $this->request->getPost("m_href");
        $menu->m_parent = $this->request->getPost("m_parent");
        

        if (!$menu->save()) {

            foreach ($menu->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "menu",
                "action" => "edit",
                "params" => array($menu->m_id)
            ));
        }

        $this->flash->success("menu was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "menu",
            "action" => "index"
        ));
    }

    /**
     * Deletes a menu
     *
     * @param string $m_id
     */
    public function deleteAction($m_id)
    {
        $menu = Menu::findFirstBym_id($m_id);
        if (!$menu) {
            $this->flash->error("menu was not found");

            return $this->dispatcher->forward(array(
                "controller" => "menu",
                "action" => "index"
            ));
        }

        if (!$menu->delete()) {

            foreach ($menu->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "menu",
                "action" => "search"
            ));
        }

        $this->flash->success("menu was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "menu",
            "action" => "index"
        ));
    }

}
