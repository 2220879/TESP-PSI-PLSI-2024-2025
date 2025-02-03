<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Fornecedor $model */

$this->title = 'Criar Fornecedor';
$this->params['breadcrumbs'][] = ['label' => 'Fornecedors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fornecedor-create">

    <?= $this->render('_form', [
        'model' => $model,
        'marcas' => $marcas,
    ]) ?>

</div>
