<?php
/**
 * @copyright Copyright (c) 2015 Yiister
 * @license https://github.com/yiister/yii2-advanced-grid/blob/master/LICENSE.md
 * @link http://yiister.ru/projects/advanced-grid
 */

namespace yiister\grid\widgets;

use yii\grid\DataColumn;

/**
 * Class DifferenceColumn
 * Usage example:
 * [
 *     'class' =>\yiister\grid\widgets\DifferenceColumn::className(),
 *     'attribute' => 'price',
 *     'difference' => function ($model, $column) {
 *         return $model->price - $model->old_price;
 *     },
 *     'differenceInPercents' => true,
 *     'template' => '{value} <sup class="label label-info">{difference}%</sup>',
 * ],
 * @package yiister\grid\widgets
 */
class DifferenceColumn extends DataColumn
{
    /**
     * @var string|callable the string output template or the render callable
     * The string allows `{value}` and `{difference}` placeholders that will be replaced to real values.
     * Example:
     * 'template' => '{value} <sup class="label label-info">{difference}%</sup>'
     *
     * The callable gets two parameters (`$model` and `$column`) and has to return output string.
     * Example:
     * 'template' => function ($model, $column) {
     *     $diff = $model->price - $model->old_price;
     *     return $model->price . \yii\helpers\Html::tag(
     *         'span',
     *         $diff,
     *         ['class' => 'label label-' . ($diff < 0 ? 'success' : 'warning')]
     *     );
     * },
     */
    public $template = '{value} <small>{difference}</small>';

    /**
     * @var string|callable the difference attribute name or the calculation callable
     * Attribute example:
     * 'difference' => 'price_diff',
     *
     * Callable example:
     * 'difference' => function ($model, $column) {
     *      return $model->price - $model->old_price;
     *  },
     */
    public $difference;

    /**
     * @var bool whether to show difference in percents
     */
    public $differenceInPercents = false;

    /**
     * Render the difference value
     * @param $value float
     * @return string
     */
    protected function renderDifference($value)
    {
        return ($value < 0 ? '&downarrow;' : '&uparrow;') . ' ' . (int) $value;
    }

    /**
     * Calculate difference
     * @param $model mixed
     * @param $difference float
     * @return float
     */
    protected function getDifferenceInPercents($model, $difference)
    {
        $value = $model->{$this->attribute};
        $oldValue = $value - $difference;
        return $value * 100 / $oldValue - 100;
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $differenceValue = is_callable($this->difference)
            ? call_user_func($this->difference, $model, $this)
            : $model->{$this->difference};
        if (is_callable($this->template)) {
            return call_user_func($this->template, $model, $this);
        }
        return strtr(
            $this->template,
            [
                '{value}' => $model->{$this->attribute},
                '{difference}' => $this->renderDifference(
                    $this->differenceInPercents
                        ? $this->getDifferenceInPercents($model, $differenceValue)
                        : $differenceValue
                ),
            ]
        );
    }
}
