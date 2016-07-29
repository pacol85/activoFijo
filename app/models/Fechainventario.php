<?php

class Fechainventario extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $f_id;

    /**
     *
     * @var string
     */
    public $f_fechainventariado;

    /**
     *
     * @var string
     */
    public $f_comentario;

    /**
     *
     * @var string
     */
    public $i_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('i_id', 'Inventario', 'i_id', array('alias' => 'Inventario'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'fechainventario';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Fechainventario[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Fechainventario
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
