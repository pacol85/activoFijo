<?php

class Imagenes extends \Phalcon\Mvc\Model
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
    public $nombre;

    /**
     *
     * @var string
     */
    public $direccion;

    /**
     *
     * @var string
     */
    public $solicitud;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('solicitud', 'Solicitudes', 'id', array('alias' => 'Solicitudes'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'imagenes';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Imagenes[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Imagenes
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
