<h1 style="text-align:center">
    yii2-quickstatus
    <hr />
</h1>

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

To install, either run

```
$ php composer.phar require isavon/yii2-quickstatus "@dev"
```

or add

```
"isavon/yii2-quickstatus": "@dev"
```

to the ```require``` section of your `composer.json` file.

## Changes

> NOTE: Refer the [CHANGE LOG](https://github.com/isavon/yii2-quickstatus/blob/master/CHANGE.md) for details on changes to various releases.

## Usage

Add QuickStatusAction to your controller.

```php
public function actions()
{
    return [
        'active' => [
            'class'     => QuickStatusAction::className(),
            'modelName' => Model::className(),
        ],
        'hidden' => [
            'class'     => QuickStatusAction::className(),
            'modelName' => Model::className()
        ]
    ];
}
```

Add QuickStatusBehavior to your model.

```php
public function behaviors()
{
    return [
        [
            'class' => QuickStatusBehavior::className(),
        ]
    ];
}
```

And add ```isavon\grid\ActionColumn``` with ```{active} {hidden}``` template to GriwView widget in your view file.

```php
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'title',
        [
            'class' => 'isavon\grid\ActionColumn',
            'template' => '{active} {hidden} {update} {delete}',
            'visibleButtons' => [
                'hidden' => function ($model) {
                    return $model->status !== $model->statusHidden;
                },
                'active' => function ($model) {
                    return $model->status !== $model->statusActive;
                }
            ],
        ]
    ]
]) ?>
```

Done!