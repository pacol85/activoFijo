<?php

class Formulario extends \Phalcon\Mvc\Model
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
    public $f_fecha;

    /**
     *
     * @var string
     */
    public $i_id;

    /**
     *
     * @var integer
     */
    public $f_movimiento;

    /**
     *
     * @var string
     */
    public $f_documento;

    /**
     *
     * @var string
     */
    public $f_uanterior;

    /**
     *
     * @var string
     */
    public $f_fechacompra;

    /**
     *
     * @var string
     */
    public $f_adept;

    /**
     *
     * @var string
     */
    public $f_ndept;

    /**
     *
     * @var string
     */
    public $f_unuevo;

    /**
     *
     * @var string
     */
    public $f_fechamov;

    /**
     *
     * @var string
     */
    public $f_motivobaja;

    /**
     *
     * @var string
     */
    public $f_elaboradopor;

    /**
     *
     * @var string
     */
    public $f_autorizadopor;

    /**
     *
     * @var string
     */
    public $f_personaconta;

    /**
     *
     * @var string
     */
    public $f_fecharevisado;

    /**
     *
     * @var integer
     */
    public $f_correlativo;

    /**
     *
     * @var integer
     */
    public $f_tipoinventario;
    
    /**
     *
     * @var string
     */
    public $f_fechaaprobado;
    
    /**
     *
     * @var integer
     */
    public $ubicanterior;
    
    /**
     *
     * @var integer
     */
    public $ubicnueva;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'formulario';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Formulario[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Formulario
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
