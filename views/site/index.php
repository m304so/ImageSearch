<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\SeacrhForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\WebFilesUploader;
use app\models\GSearch;

$this->title = 'Search page';
?>


<?php $form = ActiveForm::begin(['id' => 'search-form', 'method' => 'post']); ?>

<?= $form->field($model, 'search')->textInput(['autofocus' => true])->label('Enter your search query') ?>
<?= Html::submitButton('Search', ['class' => 'btn btn-primary', 'name' => 'search-button']) ?>

<?php ActiveForm::end(); ?>