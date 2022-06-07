<?php 

use Yii;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$f = ActiveForm::begin();
echo $f->field($m, 'testfield');
echo Html::submitButton();
ActiveForm::end();
?>