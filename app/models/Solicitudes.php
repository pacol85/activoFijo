<?php

class Solicitudes extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $id;

    /**
     *
     * @var string
     */
    public $tipo;

    /**
     *
     * @var string
     */
    public $resumen;

    /**
     *
     * @var string
     */
    public $descripcion;

    /**
     *
     * @var integer
     */
    public $estado;

    /**
     *
     * @var string
     */
    public $usuario;

    /**
     *
     * @var string
     */
    public $tecnico;

    /**
     *
     * @var string
     */
    public $fapertura;

    /**
     *
     * @var string
     */
    public $fcierre;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Imagenes', 'solicitud', array('alias' => 'Imagenes'));
        $this->belongsTo('usuario', 'Usuarios', 'u_id', array('alias' => 'Usuarios'));
        $this->belongsTo('tecnico', 'Usuarios', 'u_id', array('alias' => 'Usuarios'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'solicitudes';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Solicitudes[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Solicitudes
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
