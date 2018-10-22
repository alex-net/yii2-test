<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Редактирование пользователя '.$model->name;
$this->params['breadcrumbs'][]=['label'=>'Пользователи','url'=>['site/users']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

   

    <?php $form = ActiveForm::begin([
        'id' => 'edit-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'pass')->passwordInput(['placeholder'=>'для сохранения пароля - оставить пустым']) ?>



        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
            	<?php if (!$model->_user->fromulogin):?>
                	<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
                <?php endif;?>
                <?php if ($model->_user->username!=='admin'):?>
                	<?= Html::submitButton('Удалить', ['class' => 'btn btn-danger', 'name' => 'kill-button']) ?>
            	<?php endif;?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    
</div>