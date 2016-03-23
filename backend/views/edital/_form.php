<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\SwitchInput;
use yii\widgets\MaskedInput;

?>

<div class="edital-form">
	<div class="grid">
	    <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>

	    	<input type='hidden' id = 'form_mestrado' value =<?= $model->mestrado ?> />
	    	<input type='hidden' id = 'form_doutorado' value =<?= $model->doutorado?> />

		    <div class="row">
            <?= $form->field($model, 'numero', ['options' => ['class' => 'col-md-4']])->widget(MaskedInput::className(), [
        'mask' => '9999-9999'])->hint('Ex.: 0001-2016, sendo o <b>\'0001\'</b> o número do edital e <b>\'2016\'</b> o ano')->textInput()->label("<font color='#FF0000'>*</font> <b>Número:</b>") ?> 

		     </div>

		    <div class="row" style ="border:1px">
				<?= $form->field($model, 'documentoFile', ['options' => ['class' => 'col-md-4']])->FileInput(['accept' => '.pdf'])->label("<font color='#FF0000'>*</font> <b>Selecionar o Edital em formato PDF:</b>") ?>
		    </div>

		    <div class="row">
		        <?= $form->field($model, 'datainicio', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
		            'pluginOptions' => [
		                'format' => 'dd/mm/yyyy',
		                'autoclose'=>true
		            ]
		        ])->label("<font color='#FF0000'>*</font> <b>Data Inicial</b>")
		    ?>

		        <?= $form->field($model, 'datafim', ['options' => ['class' => 'col-md-4']])->widget(DatePicker::classname(), [
		            'pluginOptions' => [
		                'format' => 'dd/mm/yyyy',
		                'autoclose'=>true
		            ]
		        ])->label("<font color='#FF0000'>*</font> <b>Data Final</b>")
		    ?>
		    </div>
		    <div class="row">
			    <?= $form->field($model, 'cartarecomendacao', ['options' => ['class' => 'col-md-2']])->widget(SwitchInput::classname(), ['pluginOptions' => [
			        'onText' => 'Sim',
			        'offText' => 'Não',
			    ]])->label("<font color='#FF0000'>*</font> <b>Carta de Recomendação?</b>") ?>
			</div>
		    <div class="row">
			    <?= $form->field($model, 'mestrado', ['options' => ['class' => 'col-md-2']])->widget(SwitchInput::classname(), [
			    	'pluginOptions' => [
				        'onText' => 'Sim',
				        'offText' => 'Não',
			    ]])->label("<font color='#FF0000'>*</font> <b>Mestrado?</b>") ?>
			</div>
			<div class="row" id="divVagasMestrado" style="display:none">
		    	<?= $form->field($model, 'vagas_mestrado', ['options' => ['class' => 'col-md-3']])->textInput(['type' => 'number', 'maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Vagas para Mestrado:</b>") ?>

		    	<?= $form->field($model, 'cotas_mestrado', ['options' => ['class' => 'col-md-3']])->textInput(['type' => 'number'])->label("<font color='#FF0000'>*</font> <b>Vagas para Regime de Cotas:</b>") ?>
		    </div>
			<div class="row">
			    <?= $form->field($model, 'doutorado', ['options' => ['class' => 'col-md-2']])->widget(SwitchInput::classname(), [
			    	'pluginOptions' => [
			        'onText' => 'Sim',
			        'offText' => 'Não',
			    ]])->label("<font color='#FF0000'>*</font> <b>Doutorado?</b>") ?>
			</div>

		    <div class="row" id="divVagasDoutorado" style="display:none">
		    	<?= $form->field($model, 'vagas_doutorado', ['options' => ['class' => 'col-md-3']])->textInput(['type' => 'number', 'maxlength' => true])->label("<font color='#FF0000'>*</font> <b>Vagas para Doutorado:</b>") ?>

		    	<?= $form->field($model, 'cotas_doutorado', ['options' => ['class' => 'col-md-3']])->textInput(['type' => 'number'])->label("<font color='#FF0000'>*</font> <b>Vagas para Regime de Cotas:</b>") ?>
		     </div>

		    <div class="form-group">
		        <?= Html::submitButton($model->isNewRecord ? 'Criar' : 'Alterar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		    </div>

	    <?php ActiveForm::end(); ?>

	</div>


</div>
