<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Usuarios extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $u_id;

    /**
     *
     * @var string
     */
    public $u_nombre;

    /**
     *
     * @var string
     */
    public $u_apellido;

    /**
     *
     * @var string
     */
    public $u_lanid;

    /**
     *
     * @var string
     */
    public $u_contrasena;

    /**
     *
     * @var string
     */
    public $u_creacion;

    /**
     *
     * @var string
     */
    public $u_modificacion;

    /**
     *
     * @var integer
     */
    public $u_estado;

    /**
     *
     * @var string
     */
    public $u_codigo;

    /**
     *
     * @var string
     */
    public $u_eid;

    /**
     *
     * @var string
     */
    public $d_id;

    /**
     *
     * @var integer
     */
    public $u_tipo;

    /**
     *
     * @var string
     */
    public $r_id;

    /**
     *
     * @var string
     */
    public $email;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );

        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('u_id', 'Departamento', 'u_id', array('alias' => 'Departamento'));
        $this->hasMany('u_id', 'Formulario', 'f_uanterior', array('alias' => 'Formulario'));
        $this->hasMany('u_id', 'Formulario', 'f_autorizadopor', array('alias' => 'Formulario'));
        $this->hasMany('u_id', 'Formulario', 'f_personaconta', array('alias' => 'Formulario'));
        $this->hasMany('u_id', 'Formulario', 'f_elaboradopor', array('alias' => 'Formulario'));
        $this->hasMany('u_id', 'Formulario', 'f_unuevo', array('alias' => 'Formulario'));
        $this->hasMany('u_id', 'Inventario', 'u_id', array('alias' => 'Inventario'));
        $this->hasMany('u_id', 'Solicitudes', 'usuario', array('alias' => 'Solicitudes'));
        $this->hasMany('u_id', 'Solicitudes', 'tecnico', array('alias' => 'Solicitudes'));
        $this->belongsTo('d_id', 'Departamento', 'd_id', array('alias' => 'Departamento'));
        $this->belongsTo('r_id', 'Roles', 'r_id', array('alias' => 'Roles'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'usuarios';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Usuarios[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Usuarios
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
