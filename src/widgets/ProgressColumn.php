<?php
/**
 * @copyright Copyright (c) 2015 Yiister
 * @license https://github.com/yiister/yii2-advanced-grid/blob/master/LICENSE.md
 * @link http://yiister.ru/projects/advanced-grid
 */

namespace yiister\grid\widgets;

use yii\grid\DataColumn;
use yii\helpers\Html;
use yiister\grid\assets\Asset;

/**
 * Class ProgressColumn
 * Example:
 * [
 *     'class' => \yiister\grid\widgets\ProgressColumn::className(),
 *     'attribute' => 'reserved',
 *     'size' => \yiister\grid\widgets\ProgressColumn::SIZE_LARGE,
 *     'isStriped' => true,
 *     'progressBarClass' => function ($model, $column) {
 *         return $model->{$column->attribute} > 15
 *             ? \yiister\grid\widgets\ProgressColumn::STYLE_SUCCESS
 *             : \yiister\grid\widgets\ProgressColumn::STYLE_WARNING;
 *     },
 * ],
 * @package yiister\grid\widgets
 */
class ProgressColumn extends DataColumn
{
    /**
     * Size constants
     */
    const SIZE_LARGE = 'progress-large';
    const SIZE_DEFAULT = 'progress-default';
    const SIZE_MEDIUM = 'progress-medium';
    const SIZE_SMALL = 'progress-small';

    /**
     * Style constants
     */
    const STYLE_SUCCESS = 'progress-bar-success';
    const STYLE_INFO = 'progress-bar-info';
    const STYLE_WARNING = 'progress-bar-warning';
    const STYLE_DANGER = 'progress-bar-danger';

    /**
     * @var string the internal progress bar class
     */
    private $_progressBarClass = 'progress-bar';

    /**
     * @var bool whether to show a percents instead of an attribute value
     */
    public $percent = true;

    /**
     * @var int the minimum attribute value
     */
    public $minValue = 0;

    /**
     * @var int the maximum attribute value
     */
    public $maxValue = 100;

    /**
     * @var bool whether to show the text
     */
    public $showText = true;

    /**
     * @var string the progress bar size
     */
    public $size = 'progress-default';

    /**
     * @var string|callback the progress bar class
     * You may set a fixed progress bar class for all rows via string or a dynamic class via callback.
     * Callback function gets two parameters: ActiveRecord model and GridView column.
     * Static class example:
     * 'progressBarClass' => \yiister\grid\widgets\ProgressColumn::STYLE_DANGER,
     *
     * Dynamic class example:
     * 'progressBarClass' => function ($model, $column) {
     *      return $model->{$column->attribute} > 15
     *          ? \yiister\grid\widgets\ProgressColumn::STYLE_SUCCESS
     *          : \yiister\grid\widgets\ProgressColumn::STYLE_WARNING;
     * },
     */
    public $progressBarClass;

    /**
     * @var bool whether to stripe the progress bar
     */
    public $isStriped = false;

    /**
     * @var bool whether to animate the progress bar
     */
    public $isAnimated = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        Asset::register($this->grid->view);
        Html::addCssClass($this->options, ['progress', $this->size]);
        if ($this->isAnimated) {
            $this->_progressBarClass .= ' active';
            $this->isStriped = true;
        }
        if ($this->isStriped) {
            $this->_progressBarClass .= ' progress-bar-striped';
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $percents = ($model->{$this->attribute} - $this->minValue) * 100 / ($this->maxValue - $this->minValue);
        $progressBarClass = $this->_progressBarClass . ' ' . (is_callable($this->progressBarClass)
            ? call_user_func($this->progressBarClass, $model, $this)
            : $this->progressBarClass);
        return Html::tag(
            'div',
            Html::tag(
                'div',
                $this->showText ? Html::tag('span', $this->percent ? $percents . '%' : $model->{$this->attribute}) : '',
                [
                    'class' => $progressBarClass,
                    'style' => [
                        'width' => $percents . '%',
                    ],
                ]
            ),
            $this->options
        );
    }
}
