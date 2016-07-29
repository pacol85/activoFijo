<?php

class Ubicacion extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $ub_id;

    /**
     *
     * @var string
     */
    public $ub_nombre;

    /**
     *
     * @var string
     */
    public $ub_descripcion;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'ubicacion';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Ubicacion[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Ubicacion
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
