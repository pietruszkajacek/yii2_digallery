<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$activationLink = Yii::$app->urlManager->createAbsoluteUrl(['user/account-activation', 'key' => $user->activation_key]);
?>

Hello <?= Html::encode($user->username) ?>,

Follow the link below to activation your account:

<?= Html::a(Html::encode($activationLink), $activationLink) ?>
