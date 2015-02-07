<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = yii\helpers\Url::toRoute(['resetPassword', 'token' => $user->passwordResetToken], true);// Yii::$app->urlManager->createAbsoluteUrl( ['site/reset-password', 'token' => $user->password_reset_token]);
?>

Hello,

Follow the link below to reset your password:

<?php echo Html::a(Html::encode($resetLink), $resetLink) ?>
