<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = yii\helpers\Url::toRoute(['reset-password', 'token' => $user->passwordResetToken], true);
?>

Hello,

Follow the link below to reset your password:

<?php echo Html::a('Reset password', $resetLink); ?>
