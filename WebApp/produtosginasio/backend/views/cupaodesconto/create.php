<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Cupaodesconto $model */

$this->title = 'Criar Cupaodesconto';
$this->params['breadcrumbs'][] = ['label' => 'Cupaodescontos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cupaodesconto-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
