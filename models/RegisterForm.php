<?php 

namespace app\models;

use Yii;
use app\models\User;

class RegisterForm extends \yii\base\Model
{
    public $username;
    public $pass;
    public $pass_repeat;

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'pass' => 'Пароль',
            'pass_repeat' => 'Пароль ещё раз',
        ];
    }

    public function rules()
    {
        return [
            [['username', 'pass', 'pass_repeat'], 'required'],
            [['pass'], 'compare', 'operator' => '==='],
            ['username', 'checkexistsuser'],
        ];
    }
    // проверка существования юзера ... 
    public function checkexistsuser($attr, $parms)
    {
        // проверяем есть ли такой юзер .. 
        $u = User::find()->where(['username' => $this->username])->limit(1)->one();
        if ($u) { // если кто-то нашелся .. 
            $this->addError($attr, 'Ошибка! Юзер уже существует');
        }
    }

    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        $u = new User();
        $u->username = $this->username;
        $u->password = $this->pass;
        $u->save();

        return Yii::$app->user->login($u);
    }
}