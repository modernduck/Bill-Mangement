<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Template */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="template-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Document', ['newdoc', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $documentProvider,
        'filterModel' => $documentSearchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            
            'name',
            // 'balance',
            // 'create_time',
            // 'update_time',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $item , $key)
                    {
                        
                            return  Html::a('<span class="glyphicon  glyphicon-list-alt" aria-hidden="true"></span> VIEW ', ['document/viewdoc', 'id' => $item->id ]);
                        return '';
                    },
                    'update' => function ($url, $item, $key)
                    {
                        
                            return  Html::a('<span class="glyphicon  glyphicon glyphicon-th-list" aria-hidden="true"></span> EDIT', ['document/view', 'id' => $item->id ]);
                        return '';
                    }
                ],
                'template' => "{view} {update}",

            ],
        ],
    ]); ?>

</div>
