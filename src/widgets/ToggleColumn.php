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
 * Class ToggleColumn
 * Usage example:
 * [
 *     'class' => \yiister\grid\widgets\ToggleColumn::className(),
 *     'attribute' => 'is_active',
 *     'updateAction' => '/projects/column-update',
 * ]
 * @package yiister\grid\widgets
 */
class ToggleColumn extends DataColumn
{
    /**
     * @var array|string the update action route
     */
    public $updateAction = ['/site/column-update'];

    /**
     * @var array of values to rendering
     * Data format:
     *  [
     *      'value_one' => 'The first label',
     *      'value_two' => 'The second label',
     *  ]
     */
    public $buttons = [
        0 => 'Off',
        1 => 'On',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        Asset::register($this->grid->view);
        $this->grid->view->registerJs("ToggleColumn.init();");
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $items = '';
        foreach ($this->buttons as $value => $label) {
            $items .= Html::label(
                Html::radio(null, $model->{$this->attribute} == $value, ['value' => $value]) . $label,
                $model->{$this->attribute} == $value,
                [
                    'class' => 'btn ' . ($model->{$this->attribute} == $value ? 'btn-primary' : 'btn-default'),
                ]
            );
        }
        return Html::tag(
            'div',
            $items,
            [
                'data-action' => 'toggle-column',
                'data-attribute' => $this->attribute,
                'data-id' => $model->id,
                'data-model' => get_class($model),
                'data-url' => Url::to($this->updateAction),
                'data-toggle' => 'buttons',
                'class' => 'btn-group-xs btn-group',
            ]
        );
    }
}
