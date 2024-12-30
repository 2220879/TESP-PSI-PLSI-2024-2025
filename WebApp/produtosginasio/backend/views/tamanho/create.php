<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Tamanho $model */

$this->title = 'Criar Tamanho';
$this->params['breadcrumbs'][] = ['label' => 'Tamanhos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tamanho-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
