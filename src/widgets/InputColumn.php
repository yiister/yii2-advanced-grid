<?php
/**
 * @copyright Copyright (c) 2015 Yiister
 * @license https://github.com/yiister/yii2-advanced-grid/blob/master/LICENSE.md
 * @link http://yiister.ru/projects/advanced-grid
 */

namespace yiister\grid\widgets;

use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yiister\grid\assets\Asset;

/**
 * Class InputColumn
 * Example:
 * [
 *     'class' => \yiister\grid\widgets\InputColumn::className(),
 *     'attribute' => 'price',
 *     'updateAction' => '/projects/column-update',
 * ],
 * @package yiister\grid\widgets
 */
class InputColumn extends DataColumn
{
    const SIZE_LARGE = 'input-lg';
    const SIZE_DEFAULT = '';
    const SIZE_SMALL = 'input-sm';

    /**
     * @var array|string the update action route
     */
    public $updateAction = ['/site/column-update'];

    /**
     * @var string the input size
     */
    public $size = 'input-sm';

    /**
     * @inheritdoc
     */
    public function init()
    {
        Asset::register($this->grid->view);
        $this->grid->view->registerJs("InputColumn.init();");
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        return Html::tag(
            'div',
            Html::textInput(
            Html::getInputName($model, $this->attribute),
                $model->{$this->attribute},
                [
                    'class' => 'form-control ' . $this->size,
                    'data-action' => 'input-column',
                    'data-attribute' => $this->attribute,
                    'data-id' => $model->id,
                    'data-model' => get_class($model),
                    'data-url' => $this->updateAction,
                ]
            ),
            [
                'class' => 'form-group input-column-form-group',
            ]
        );
    }
}
