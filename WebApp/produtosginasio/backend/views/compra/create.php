<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Compra $model */

$this->title = 'Criar Compra';
$this->params['breadcrumbs'][] = ['label' => 'Compras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="compra-create">

    <?= $this->render('_form', [
        'model' => $model,
        'fornecedores' => $fornecedores,
    ]) ?>

</div>
