<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_usuario".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $authKey
 * @property string $accessToken
 * @property integer $activate
 * @property integer $role
 * @property string $verification_code
 *
 * @property TblComentario[] $tblComentarios
 */
class Usuario extends \yii\db\ActiveRecord
{

    public static function getDb() {
        return Yii::$app->db; 
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_usuario';
    }

    /**
     * @inheritdoc
     */
    /*
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'authKey', 'accessToken', 'verification_code'], 'required'],
            [['activate', 'role'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 80],
            [['password', 'authKey', 'accessToken', 'verification_code'], 'string', 'max' => 250],
        ];
    }
    */

    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'authKey' => 'Auth Key',
            'accessToken' => 'Access Token',
            'activate' => 'Activate',
            'role' => 'Role',
            'verification_code' => 'Verification Code',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblComentarios()
    {
        return $this->hasMany(TblComentario::className(), ['idusuario' => 'id']);
    }
}
