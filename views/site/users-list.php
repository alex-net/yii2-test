<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use yii\grid\GridView;

$this->title = 'Список пользователей';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

 <?= GridView::widget([
    'dataProvider' => $p,
    'columns' => ['id', ['attribute' => 'username', 'label' => 'Логин', 'content' => [\app\controllers\SiteController::className(), 'userlink']]],
    ]); ?>
    
</div>