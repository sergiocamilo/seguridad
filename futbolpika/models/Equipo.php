<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_equipo".
 *
 * @property integer $id
 * @property string $nombre
 * @property integer $fundacion
 * @property string $ciudad
 * @property string $color_camiseta
 *
 * @property TblComentario[] $tblComentarios
 */
class Equipo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_equipo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'fundacion', 'ciudad', 'color_camiseta'], 'required'],
            [['fundacion'], 'integer'],
            [['nombre', 'ciudad', 'color_camiseta'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'fundacion' => 'Fundacion',
            'ciudad' => 'Ciudad',
            'color_camiseta' => 'Color Camiseta',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblComentarios()
    {
        return $this->hasMany(TblComentario::className(), ['idequipo' => 'id']);
    }
}
