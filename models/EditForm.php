<?php 

namespace app\models;

class EditForm extends \yii\base\Model
{
	public $name;
	public $pass;
	public $_user;

	public function init()
	{
		parent::init();
		$this->name=$this->_user->username;
	}
	public function attributeLabels()
	{
		return [
			'name'=>'логин',
			'pass'=>'пароль',
		];
	}

	public function rules()
	{
		return [
			['name','required'],
			['name','userexists'],
			['pass','safe'],
		];
	}
	// сохранение формы ...
	public function save($post,$user)
	{
		if ($this->load($post) && $this->validate()){
			if (isset($post['save-button'])){
				$user->username=$this->name;
				if ($this->pass)
					$user->password=$this->pass;
				$user->save();
				return true;
			}
			if (isset($post['kill-button'])){
				$user->delete();
				return true;
			}
		}
		return false;
	}

	public function userexists($attr,$params)
	{
		// изменён логин  и нашелся юзер с таким логином ...
		if ($this->name!=$this->_user->username && \app\models\User::find()->where(['username'=>$this->name])->count()) 		

			$this->addError($attr,'Ошибка! Логин занят');
	}
}