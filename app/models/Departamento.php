<?php

class Departamento extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $d_id;

    /**
     *
     * @var string
     */
    public $d_nombre;

    /**
     *
     * @var string
     */
    public $d_descripcion;

    /**
     *
     * @var string
     * Se refiere al jefe del area quien es un usuario a la vez, servira
     * para dar permisos o aprobar solicitudes.
     */
    public $u_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('d_id', 'Usuarios', 'd_id', array('alias' => 'Usuarios'));
        $this->belongsTo('u_id', 'Usuarios', 'u_id', array('alias' => 'Usuarios'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'departamento';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Departamento[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Departamento
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
