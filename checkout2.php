<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$form=ActiveForm::begin(['action'=>'index.php?r=site/requesthandler']);
?>
<?= $form->field($model,'merchant_id')->hiddenInput()->label(false)?>
<?= $form->field($model,'order_id')->hiddenInput()->label(false)?>
<?= $form->field($model,'language')->hiddenInput()->label(false)?>
<?= $form->field($model,'amount')->hiddenInput()->label(false)?>
<?= $form->field($model,'currency')->hiddenInput()->label(false)?>
<?= $form->field($model,'redirect_url')->hiddenInput()->label(false)?>
<?= $form->field($model,'cancel_url')->hiddenInput()->label(false)?>
<?= Html::submitButton('Submit',['class'=>'btn-lg btn-primary'])?>
<?php ActiveForm::end();?>