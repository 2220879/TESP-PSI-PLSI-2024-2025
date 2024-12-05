<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Usocupao $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Usocupaos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="usocupao-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'cupaodesconto_id',
            [
                'label' => 'Cupao de Desconto',
                'value' => function($model){
                    return $model->cupaodesconto->desconto;
                }
            ],
            //'profile_id',
            [
                'label' => 'Utilizador',
                'value' => function($model){
                    return $model->profile->user->username;
                }
            ],
            'dataUso',
        ],
    ]) ?>

</div>
