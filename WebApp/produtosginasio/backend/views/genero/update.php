<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Genero $model */

$this->title = 'Atualizar Genero: ' . $model->referencia;
$this->params['breadcrumbs'][] = ['label' => 'Generos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->referencia, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="genero-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
