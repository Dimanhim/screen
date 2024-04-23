<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\Html as Helper;
use common\models\User;
use common\components\AccessesComponent;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>
<div class="loader-block"></div>
<header>
    <?php
    NavBar::begin([
        'brandLabel' => Helper::img('/img/logo.svg', ['class' => 'logo']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);

    $menuItems = [];
    if(Yii::$app->accesses->hasAccess(AccessesComponent::TYPE_USERS) or User::isAdmin()) {
        $menuItems[] = ['label' => 'Пользователи', 'url' => ['/user/index']];
    }
    /*if(Yii::$app->accesses->hasAccess(AccessesComponent::TYPE_BUILDING)) {
        $menuItems[] = ['label' => 'Корпуса', 'url' => ['/building/index']];
    }*/
    if(Yii::$app->accesses->hasAccess(AccessesComponent::TYPE_CABINET, null, null, true)) {
        $menuItems[] = ['label' => 'Кабинеты', 'url' => ['/cabinet/index']];
    }
    if(Yii::$app->accesses->hasAccess(AccessesComponent::TYPE_TICKETS, null, null, true)) {
        $menuItems[] = ['label' => 'Талоны', 'url' => ['/ticket/index']];
    }

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    }
    //echo Helper::img('/img/logo.png');
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Login',['/site/login'],['class' => ['btn btn-link login text-decoration-none']]),['class' => ['d-flex']]);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout text-decoration-none']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'homeLink' => false
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<?= $this->render('//layouts/blocks/alert_modal') ?>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container container-footer">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-end">Разработано <a href="https://rnova.org" target="_blank">MadeForMed</a></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
