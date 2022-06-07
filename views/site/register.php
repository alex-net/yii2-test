<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use rmrevin\yii\ulogin\ULogin;
//rmrevin\yii\ulogin\ULogin

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-registr">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста, заполните поля для регистрации:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'pass')->passwordInput() ?>
        <?= $form->field($model, 'pass_repeat')->passwordInput() ?>


        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <?= Ulogin::widget([
        'display' => Ulogin::D_SMALL,
        'fields' => [Ulogin::F_NICKNAME],
        'providers' => [Ulogin::P_GOOGLE],
        'redirectUri' => ['site/ulogin', 'type' => 'register'],
        'hidden' => [],
        'language' => ULogin::L_RU,
        ]); ?>

    
</div>