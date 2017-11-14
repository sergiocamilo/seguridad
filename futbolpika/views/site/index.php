<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Futbol seguridad';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Bienvenido!</h1>
        <p>
            <?= Html::a('Ir a equipos', ['equipo/index'], ['class' => 'btn btn-success']) ?>
        </p>
        <p>
            <?= Html::a('Recuperar contraseña', ['equipo/index'], ['class' => 'btn btn-warning']) ?>
        </p>
        
        
    </div>

    
</div>
