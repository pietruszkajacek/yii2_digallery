<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'Digallery',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            
            $menuItems = [
                ['label' => 'Home', 'url' => ['/site/index']],
            ];
            
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Signup', 'url' => ['/user/signup']];
                $menuItems[] = ['label' => 'Login', 'url' => ['/user/login']];
            } else {
                $menuItems[] = [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/user/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            
            $menuItems[] = [
              'label' => 'Masz konto? <strong>Zaloguj się...</strong>',
              'encode' => false,
              'items' => [
                  '<li><strong>Test</strong></li>',
                   ['label' => 'Level 1 - Dropdown A', 'url' => '#'],
                   '<li class="divider"></li>',
                   '<li class="dropdown-header">Dropdown Header</li>',
                   ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
              ],
          ];
            
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
