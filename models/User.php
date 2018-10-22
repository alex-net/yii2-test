<?php

namespace app\models;

use Yii;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /*public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    */

    public function beforeSave($ins)
    {
        if (!parent::beforeSave($ins))
            return false;
        // новая запись = надо сгенерить ключ 
        if ($this->isNewRecord)
            $this->authKey=Yii::$app->security->generateRandomString(15);
        // если пароль не пустой .. надо его захешить ... 
        if ($this->password)
            //$this->isNewRecord
            if ($this->isNewRecord || !$this->isNewRecord && !empty($this->oldAttributes['password']) && $this->oldAttributes['password']!=$this->password)
            $this->password=Yii::$app->security->generatePasswordHash($this->password);

        return true;
    }
   
    // регистрация юзера из соцсети ..
    static function UloginRegister($name)
    {
        // проверка наличия юзера  в  базе . 
        if (self::find()->where(['username'=>$name,'fromulogin'=>1])->count())
            return false;

        $u= new self();
        $u->username=$name;
        $u->fromulogin=1;
        $u->save();

        // логиним сразу .. 
        return $u;
        ///$u->

    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        Yii::info($id,'user id');
        return self::find()->where(['id'=>$id])->limit(1)->one();
        //return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::find()->where(['username'=>$username])->limit(1)->one();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
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
        return $this->password && Yii::$app->security->validatePassword($password,$this->password);
    }
}
