<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BancaControleDefesas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banca-controle-defesas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status_banca')->textInput() ?>

    <?= $form->field($model, 'justificativa')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
