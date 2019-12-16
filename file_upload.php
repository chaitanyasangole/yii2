<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html; 
$form=ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]);
/*$session=Yii::$app->session;
if($session->hasFlash('success'))
{
	echo '<div class="alert alert-success">'.$session->getFlash('success').'</div>';
	$session->removeFlash('sucess');
}*/
?>
<div class="row">
<div class="col-sm-4"></div>
<div class="col-sm-4">
<div class="panel panel-primary">
<div class="panel-heading text-center"><h1>Upload Form</h1></div>
<div class="panel-body">
<div class="form-group">
<?=$form->field($model,'img[]')->fileInput(['multiple'=>true])?>
<?= $form->field($model,'server_name')->textInput()?>
<?= $form->field($model,'invoice_number')->input('number')?>
<?= $form->field($model,'invoice_date')->input('date')?>
</div>
<div class="row">
<div class="col-sm-6">
<?=Html::submitButton('Submit',['class'=>'btn btn-primary btn-block']);?>
</div>
<div class="col-sm-6">
<?=Html::resetButton('Cancel',['class'=>'btn btn-danger btn-block']);?>
</div>
</div>
</div>
</div>
</div>
<div class="col-sm-4"></div>
</div>
<?php ActiveForm::end();?>