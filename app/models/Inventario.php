<?php

class Inventario extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $i_id;

    /**
     *
     * @var string
     */
    public $i_correlativo;

    /**
     *
     * @var string
     */
    public $i_descripcion;

    /**
     *
     * @var string
     */
    public $i_uanterior;

    /**
     *
     * @var string
     */
    public $i_fingreso;

    /**
     *
     * @var string
     */
    public $i_vadquisicion;

    /**
     *
     * @var string
     */
    public $i_vresidual;

    /**
     *
     * @var string
     */
    public $i_dacumulada;

    /**
     *
     * @var string
     */
    public $i_penddepreciar;

    /**
     *
     * @var integer
     */
    public $i_encontrado;

    /**
     *
     * @var string
     */
    public $i_observaciones;

    /**
     *
     * @var string
     */
    public $i_ubicacion;

    /**
     *
     * @var string
     */
    public $u_id;

    /**
     *
     * @var integer
     */
    public $i_activo;

    /**
     *
     * @var string
     */
    public $i_serie;

    /**
     *
     * @var string
     */
    public $i_modelo;

    /**
     *
     * @var string
     */
    public $i_marca;
    
    /**
     *
     * @var string
     */
    public $i_tipo;
    
    /**
     *
     * @var string
     */
    public $i_color;
    
    /**
     *
     * @var string
     */
    public $i_otros;
    
    /**
     *
     * @var integer
     */
    public $i_estado;
    
    /**
     *
     * @var string
     */
    public $i_proveedor;
    
    /**
     *
     * @var integer
     */
    public $i_asignadou;
    
    /**
     *
     * @var string
     */
    public $i_accion;
    
    /**
     *
     * @var integer
     */
    public $ubicid;
    
    /**
     *
     * @var integer
     */
    public $depto;

    /**
     *
     * @var integer
     */
    public $adepto;
    
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('i_id', 'Fechainventario', 'i_id', array('alias' => 'Fechainventario'));
        $this->belongsTo('u_id', 'Usuarios', 'u_id', array('alias' => 'Usuarios'));
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'inventario';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Inventario[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Inventario
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
