<?php

namespace isavon\quickstatus;

use Yii;
use yii\helpers\Url;
use yii\base\Behavior;
use yii\db\Exception;

/**
 * Behavior for changing status of record quickly.
 *
 * ```php
 *
 * // inside the model
 *
 * public function behaviors()
 * {
 *    return [
 *       [
 *           'class' => QuickStatusBehavior::className(),
 *           'statusAttribute' => 'status',
 *           'statusActive'    => 'active',
 *           'statusHidden'    => 'hidden',
 *           'errorRoute'      => ['index'],
 *           'successRoute'    => ['index'],
 *           'callback'        => function() {
 *               // some callback actions...
 *           }
 *       ],
 *   ];
 * }
 * ```
 *
 * @author Ivan Savon <isavon.we@gmail.com>
 */
class QuickStatusBehavior extends Behavior
{
    /**
     * @var string name of the status column from model
     */
    public $statusAttribute = 'status';

    /**
     * @var mixed status of the active record
     */
    public $statusActive = 'active';

    /**
     * @var mixed status of the hidden record
     */
    public $statusHidden = 'hidden';

    /**
     * @var array url of redirect in error case
     */
    public $errorRoute = ['index'];

    /**
     * @var array url of redirect after the success. If you need set not simple url, like
     * ['work-image/index', 'workid' => $model->id_work] you can do it like this:
     * ['work-image/index', 'workid' => 'id_work']. If current model contain 'id_work' attributre,
     * then this attribute will be replace to value of it.
     */
    public $successRoute = ['index'];

    /**
     * @var null callback function. The callback function will be done after the success.
     *
     * Example:
     *
     * ```php
     * public function behaviors()
     * {
     *    return [
     *       [
     *           'class' => QuickStatusBehavior::className(),
     *           'callback' => function() {
     *               return Rubric::recount();
     *           }
     *       ],
     *   ];
     * }
     * ```
     */
    public $callback = NULL;

    /**
     * Changing the status of the record.
     *
     * @param int $id id of the record
     * @throws Exception
     * @throws yii\base\ExitException
     */
    public function changeStatus($id)
    {
        $model = $this->owner;

        If (!$model = $model::findOne($id)) {
            Yii::$app->getSession()->setFlash('error', Yii::t('yii', 'Page not found.'));
            Yii::$app->getResponse()->redirect(Url::to($this->errorRoute));
            Yii::$app->end();
        }

        if (!$model->hasAttribute($this->statusAttribute)) {
            throw new Exception('The model does not have a status column `' . $this->statusAttribute . '`.');
        }

        $model->updateAttributes([
            $this->statusAttribute => $this->getNewStatus($model->{$this->statusAttribute})
        ]);

        if ($this->callback !== NULL) {
            call_user_func($this->callback);
        }

        foreach ($this->successRoute as &$attr) {
            print_r($attr);echo '<br>';
            if ($model->hasAttribute($attr)) {
                $attr = $model->$attr;
            }
        }

        Yii::$app->getResponse()->redirect(Url::to($this->successRoute));
    }

    /**
     * Get a new status of the record
     *
     * @param mixed $current current status of the record
     * @return mixed
     */
    private function getNewStatus($current)
    {
        $statuses = [
            $this->statusActive => $this->statusHidden,
            $this->statusHidden => $this->statusActive
        ];

        return $statuses[$current];
    }
}
