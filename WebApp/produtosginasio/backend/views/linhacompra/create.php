<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Linhacompra $model */

$this->title = 'Criar Linha';
$this->params['breadcrumbs'][] = ['label' => 'Linhacompras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="linhacompra-create">

    <?= $this->render('_form', [
        'model' => $model,
        'compra' => $compra,
        'produtos' => $produtos,
        'tamanhos' => $tamanhos,
    ]) ?>

</div>
