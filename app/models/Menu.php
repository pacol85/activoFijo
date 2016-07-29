<?php

class Menu extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $m_id;

    /**
     *
     * @var string
     */
    public $m_label;

    /**
     *
     * @var string
     */
    public $m_href;

    /**
     *
     * @var string
     */
    public $m_parent;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('m_id', 'Menu', 'm_parent', array('alias' => 'Menu'));
        $this->hasMany('m_id', 'Mxr', 'm_id', array('alias' => 'Mxr'));
        $this->belongsTo('m_parent', 'Menu', 'm_id', array('alias' => 'Menu'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'menu';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Menu[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Menu
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
