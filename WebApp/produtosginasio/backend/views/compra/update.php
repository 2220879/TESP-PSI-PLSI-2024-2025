<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Compra $model */

$this->title = 'Atualizar Compra';
$this->params['breadcrumbs'][] = ['label' => 'Compras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="compra-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'fornecedores' => $fornecedores,
    ]) ?>

</div>
