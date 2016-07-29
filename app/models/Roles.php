<?php

class Roles extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $r_id;

    /**
     *
     * @var string
     */
    public $r_rol;

    /**
     *
     * @var string
     */
    public $r_descripcion;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('r_id', 'Mxr', 'r_id', array('alias' => 'Mxr'));
        $this->hasMany('r_id', 'Usuarios', 'r_id', array('alias' => 'Usuarios'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'roles';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Roles[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Roles
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
