<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BancaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bancas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banca-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Banca', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'idusuario',
            'tipo',
            'dataSaida',
            'dataRetorno',
            'dataPedido',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    
</div>