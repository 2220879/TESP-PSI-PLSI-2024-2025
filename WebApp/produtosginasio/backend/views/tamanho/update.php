<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Tamanho $model */

$this->title = 'Atualizar Tamanho: ' . $model->referencia;
$this->params['breadcrumbs'][] = ['label' => 'Tamanhos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->referencia, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tamanho-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
