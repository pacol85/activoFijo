<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class UsuariosController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for usuarios
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Usuarios', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "u_id";

        $usuarios = Usuarios::find($parameters);
        if (count($usuarios) == 0) {
            $this->flash->notice("The search did not find any usuarios");

            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $usuarios,
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
     * Edits a usuario
     *
     * @param string $u_id
     */
    public function editAction($u_id)
    {
        if (!$this->request->isPost()) {

            $usuario = Usuarios::findFirstByu_id($u_id);
            if (!$usuario) {
                $this->flash->error("usuario was not found");

                return $this->dispatcher->forward(array(
                    "controller" => "usuarios",
                    "action" => "index"
                ));
            }

            $this->view->u_id = $usuario->u_id;

            $this->tag->setDefault("u_id", $usuario->u_id);
            $this->tag->setDefault("u_nombre", $usuario->u_nombre);
            $this->tag->setDefault("u_apellido", $usuario->u_apellido);
            $this->tag->setDefault("u_lanid", $usuario->u_lanid);
            $this->tag->setDefault("u_contrasena", $usuario->u_contrasena);
            $this->tag->setDefault("u_creacion", $usuario->u_creacion);
            $this->tag->setDefault("u_modificacion", $usuario->u_modificacion);
            $this->tag->setDefault("u_estado", $usuario->u_estado);
            $this->tag->setDefault("u_codigo", $usuario->u_codigo);
            $this->tag->setDefault("u_eid", $usuario->u_eid);
            $this->tag->setDefault("d_id", $usuario->d_id);
            $this->tag->setDefault("u_tipo", $usuario->u_tipo);
            
        }
    }

    /**
     * Creates a new usuario
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "index"
            ));
        }

        $usuario = new Usuarios();

        $usuario->u_nombre = $this->request->getPost("u_nombre");
        $usuario->u_apellido = $this->request->getPost("u_apellido");
        $usuario->u_lanid = $this->request->getPost("u_lanid");
        $usuario->u_contrasena = $this->request->getPost("u_contrasena");
        $usuario->u_creacion = $this->request->getPost("u_creacion");
        $usuario->u_modificacion = $this->request->getPost("u_modificacion");
        $usuario->u_estado = $this->request->getPost("u_estado");
        $usuario->u_codigo = $this->request->getPost("u_codigo");
        $usuario->u_eid = $this->request->getPost("u_eid");
        $usuario->d_id = $this->request->getPost("d_id");
        $usuario->u_tipo = $this->request->getPost("u_tipo");
        

        if (!$usuario->save()) {
            foreach ($usuario->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "new"
            ));
        }

        $this->flash->success("usuario was created successfully");

        return $this->dispatcher->forward(array(
            "controller" => "usuarios",
            "action" => "index"
        ));
    }

    /**
     * Saves a usuario edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "index"
            ));
        }

        $u_id = $this->request->getPost("u_id");

        $usuario = Usuarios::findFirstByu_id($u_id);
        if (!$usuario) {
            $this->flash->error("usuario does not exist " . $u_id);

            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "index"
            ));
        }

        $usuario->u_nombre = $this->request->getPost("u_nombre");
        $usuario->u_apellido = $this->request->getPost("u_apellido");
        $usuario->u_lanid = $this->request->getPost("u_lanid");
        $usuario->u_contrasena = $this->request->getPost("u_contrasena");
        $usuario->u_creacion = $this->request->getPost("u_creacion");
        $usuario->u_modificacion = $this->request->getPost("u_modificacion");
        $usuario->u_estado = $this->request->getPost("u_estado");
        $usuario->u_codigo = $this->request->getPost("u_codigo");
        $usuario->u_eid = $this->request->getPost("u_eid");
        $usuario->d_id = $this->request->getPost("d_id");
        $usuario->u_tipo = $this->request->getPost("u_tipo");
        

        if (!$usuario->save()) {

            foreach ($usuario->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "edit",
                "params" => array($usuario->u_id)
            ));
        }

        $this->flash->success("usuario was updated successfully");

        return $this->dispatcher->forward(array(
            "controller" => "usuarios",
            "action" => "index"
        ));
    }

    /**
     * Deletes a usuario
     *
     * @param string $u_id
     */
    public function deleteAction($u_id)
    {
        $usuario = Usuarios::findFirstByu_id($u_id);
        if (!$usuario) {
            $this->flash->error("usuario was not found");

            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "index"
            ));
        }

        if (!$usuario->delete()) {

            foreach ($usuario->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "usuarios",
                "action" => "search"
            ));
        }

        $this->flash->success("usuario was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => "usuarios",
            "action" => "index"
        ));
    }

}
