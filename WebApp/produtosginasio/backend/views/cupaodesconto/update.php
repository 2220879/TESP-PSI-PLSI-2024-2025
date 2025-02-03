<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Cupaodesconto $model */

$this->title = 'Atualizar Cupão Desconto';
$this->params['breadcrumbs'][] = ['label' => 'Cupaodescontos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cupaodesconto-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
