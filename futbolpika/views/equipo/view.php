<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\captcha\Captcha;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $this yii\web\View */
/* @var $model app\models\Equipo */

$comment = new  app\models\Comentario();


$this->title = $model->nombre;

/*$this->params['breadcrumbs'][] = ['label' => 'Equipos', 'url' => ['index']];*/
/*$this->params['breadcrumbs'][] = $this->title;*/
?>

<div class="equipo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'fundacion',
            'ciudad',
            'color_camiseta',
        ],
    ]) ?>

</div>

<h3>Comentarios de <?= Html::encode($this->title) ?> </h3>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            'username',    
            'texto',
        ],
    ]); ?>


<div class="comentario-form">

    <?php $form = ActiveForm::begin(['action' =>['comentario/create'], 'method' => 'post',]); ?>

    <?= $form->field($comment, 'idequipo')->hiddenInput(['value'=> $model->id])->label(false); ?>

    <?= $form->field($comment, 'texto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($comment, 'idusuario')->hiddenInput(['value'=> Yii::$app->user->identity->id])->label(false); ?>

    <?= $form->field($comment, 'captcha')->widget(Captcha::className()) ?>

    <div class="form-group">
        <?= Html::submitButton('Comentar', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
