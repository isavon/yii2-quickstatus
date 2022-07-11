<?php

namespace isavon\quickstatus;

use yii\base\Action;
use yii\base\InvalidConfigException;

/**
 * QuickStatus action that can be used to change the status of the item quickly.
 *
 * ```php
 *
 * // inside the controller
 *
 * public function actions()
 * {
 *    return [
 *       'active' => [
 *          'class' => QuickStatusAction::className(),
 *          'modelName' => Model::className(),
 *       ],
 *      'hidden' => [
 *          'class' => QuickStatusAction::className(),
 *          'modelName' => Model::className(),
 *      ],
 *   ];
 * }
 * ```
 *
 * @author Ivan Savon <isavon.we@gmail.com>
 */
class QuickStatusAction extends Action
{
    /**
     * @var string the model name
     */
    public $modelName;

    /**
     * Runs the action.
     *
     * @param int $id the id of record
     * @throws InvalidConfigException
     */
    public function run($id)
    {
        $model = new $this->modelName;
        if (!$model->hasMethod('changeStatus')) {
            throw new InvalidConfigException(
                'Not found right `QuickStatusBehavior` behavior in `' . $this->modelName . '`.'
            );
        }
        $model->changeStatus($id);
    }
}
