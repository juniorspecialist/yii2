<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Chunk */

$this->title = 'Create Chunk';
$this->params['breadcrumbs'][] = ['label' => 'Chunks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chunk-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
