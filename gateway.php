<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$form=ActiveForm::begin();
?>
<?= Html::submitButton('Submit',['class'=>'btn-lg btn-primary btn-block']) ?>
<?php ActiveForm::end();?>