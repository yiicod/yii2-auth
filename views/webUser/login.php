<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?php echo Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'validateOnChange' => false,
                'validateOnBlur' => false
                ]); ?>
                <?php echo $form->field($model, 'username') ?>
                <?php echo $form->field($model, 'password')->passwordInput() ?>
                <?php echo $form->field($model, 'rememberMe')->checkbox() ?>
                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?php echo Html::a('reset it', yii\helpers\Url::toRoute('requestPasswordReset')) ?>.
                </div>
                <div class="form-group">
                    <?php echo Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
