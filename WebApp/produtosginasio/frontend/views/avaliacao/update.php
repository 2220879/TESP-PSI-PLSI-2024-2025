<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Avaliacao $model */

$this->title = 'Atualizar Avaliacao: ';
?>
<div class="container">
    <div class="avaliacao-update">

        <h1><?= Html::encode($this->title) ?></h1>

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>
