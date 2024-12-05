<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Linhacompra $model */

$this->title = 'Atualizar Linha: ';
$this->params['breadcrumbs'][] = ['label' => 'Linhacompras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="linhacompra-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'compra' => $compra,
        'produtos' => $produtos,
    ]) ?>

</div>
