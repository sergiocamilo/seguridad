<?php

namespace app\models;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $email;
    public $password;
    public $authKey;
    public $accessToken; public $activate;
    public $role;
    public $verification_code; 


    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];


    /**
     * @inheritdoc
     */
    /* busca la identidad del usuario a través de su $id */
    public static function findIdentity($id)
    {
        $user = Usuario::find()
            ->where("activate=:activate", [":activate" => 1]) ->andWhere("id=:id", ["id" => $id])
            ->one();
        return isset($user) ? new static($user) : null; 
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $users = AppUser::find()
            ->where("activate=:activate", [":activate" => 1]) ->andWhere("accessToken=:accessToken",
             [":accessToken" => $token]) ->all();
        
        foreach ($users as $user) {
            if ($user->accessToken === $token) {
                return new static($user); 
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    /* Busca la identidad del usuario a través del username */
    public static function findByUsername($username)
    {
        $users = Usuario::find()
            ->where("activate=:activate", ["activate" => 1]) 
            ->andWhere("username=:username", [":username" => $username]) 
            ->all();

        
        foreach ($users as $user) {
            if (strcasecmp($user->username, $username) === 0) {
                return new static($user); 
            }
        } 

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        /* Valida el password */
        if (crypt($password, $this->password) == $this->password) {
            return $password === $password;
        }
    }

    public static function isUserAdmin($id) {
        
        if (Usuario::findOne(['id' => $id, 'activate' => '1', 'role' => 2])){
            return true; 
        }
        else {
            return false; 
        }

        
    }
    public static function isUserSimple($id){

        if (Usuario::findOne(['id' => $id, 'activate' => '1', 'role' => 1])){
            return true;
        }
        else
        {
            return false;
        } 
    }

}
