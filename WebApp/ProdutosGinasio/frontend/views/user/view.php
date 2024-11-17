<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Meu Perfil';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="site-view-user">
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="site-view-user-data">
            <h5><label for="user-name">Username:</label></h5>
            <p><?= $user->username ?></p>

            <h5><label for="user-name">Email:</label></h5>
            <p><?= $user->email ?></p>
        </div>
        <div class="site-view-profile">
            <h5><label for="user-name">Nif:</label></h5>
            <p><?= $profile->nif ?></p>

            <h5><label for="user-name">Morada:</label></h5>
            <p><?= $profile->morada ?></p>

            <h5><label for="user-name">Telefone:</label></h5>
            <p><?= $profile->telefone ?></p>
        </div>
    </div>
    <br>
    <?= Html::a('Atualizar Dados', ['update', 'id' => $user->id], ['class' => 'btn btn-primary']) ?>
</div>