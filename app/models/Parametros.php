<?php

class Parametros extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $p_id;

    /**
     *
     * @var string
     */
    public $p_parametro;

    /**
     *
     * @var string
     */
    public $p_valor;

    /**
     *
     * @var string
     */
    public $p_descripcion;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'parametros';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Parametros[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Parametros
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
