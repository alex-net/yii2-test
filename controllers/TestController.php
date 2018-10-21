<?php 

namespace app\controllers;


use Yii;
use yii\web\Controller;

use app\models\TestModel;

class TestController extends Controller 
{
	public function actionIndex(){
		$m=new TestModel();

		return $this->render('test-form',['m'=>$m]);
	}
}

?>