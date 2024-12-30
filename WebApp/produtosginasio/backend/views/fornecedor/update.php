<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Fornecedor $model */

$this->title = 'Atualizar: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Fornecedors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fornecedor-update">

    <?= $this->render('_form', [
        'model' => $model,
        'marcas' => $marcas,
    ]) ?>

</div>