<?php

class Mxr extends \Phalcon\Mvc\Model
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
    public $m_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('m_id', 'Menu', 'm_id', array('alias' => 'Menu'));
        $this->belongsTo('r_id', 'Roles', 'r_id', array('alias' => 'Roles'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'mxr';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Mxr[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Mxr
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
