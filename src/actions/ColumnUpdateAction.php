<?php
/**
 * @copyright Copyright (c) 2015 Yiister
 * @license https://github.com/yiister/yii2-advanced-grid/blob/master/LICENSE.md
 * @link http://yiister.ru/projects/advanced-grid
 */

namespace yiister\grid\actions;

use Yii;
use yii\base\Action;
use yii\db\ActiveRecord;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ColumnUpdateAction
 * @package yiister\grid\actions
 */
class ColumnUpdateAction extends Action
{
    /**
     * @var array of allowed classes with attributes in the next format
     * [
     *    'app\models\Page' => ['is_active'],
     *    'app\models\OrderStatus' => ['is_system', 'is_active', 'sort_order'],
     * ]
     */
    public $allowedAttributes = [];

    /**
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function run()
    {
        $className = Yii::$app->request->post('model');
        $attribute = Yii::$app->request->post('attribute');
        $id = Yii::$app->request->post('id');
        $value = Yii::$app->request->post('value');
        if ($className === null || $attribute === null || $id === null || $value === null) {
            throw new BadRequestHttpException('Missing required parameters: model, attribute, id, value');
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (isset($this->allowedAttributes[$className]) === false
            || in_array($attribute, $this->allowedAttributes[$className]) === false
        ) {
            throw new BadRequestHttpException;
        }
        if (null === ($model = $className::find()->where(['id' => $id])->one())) {
            throw new NotFoundHttpException;
        }
        /** @var ActiveRecord $model */
        $model->$attribute = $value;
        return [
            'status' => $model->save(true, [$attribute]),
            'message' => implode("\n", $model->getErrors($attribute)),
        ];
    }
}
