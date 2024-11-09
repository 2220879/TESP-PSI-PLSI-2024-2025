<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Genero $model */

$this->title = 'Update Genero: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Generos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="genero-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
