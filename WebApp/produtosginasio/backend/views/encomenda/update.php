<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Encomenda $model */

$this->title = 'Atualizar Encomenda Nº ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Encomendas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="encomenda-update">

    <?= $this->render('_form', [
        'model' => $model,
        'status' => $status,
    ]) ?>

</div>
