<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_comentario".
 *
 * @property integer $id
 * @property integer $idequipo
 * @property string $texto
 * @property integer $idusuario
 *
 * @property TblEquipo $idequipo0
 * @property TblUsuario $idusuario0
 */



class Comentario extends \yii\db\ActiveRecord
{
    public $captcha;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_comentario';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idequipo', 'texto', 'idusuario'], 'required'],
            [['idequipo', 'idusuario'], 'integer'],
            [['texto'], 'string', 'max' => 512],
            [['idequipo'], 'exist', 'skipOnError' => true, 'targetClass' => Equipo::className(), 'targetAttribute' => ['idequipo' => 'id']],
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['idusuario' => 'id']],
            ['captcha', 'captcha'],
            ['captcha', 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'idequipo' => 'Idequipo',
            'texto' => 'Texto',
            'idusuario' => 'Idusuario',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdequipo0()
    {
        return $this->hasOne(TblEquipo::className(), ['id' => 'idequipo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(TblUsuario::className(), ['id' => 'idusuario']);
    }
}
