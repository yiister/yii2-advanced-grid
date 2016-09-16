<?php
/**
 * @copyright Copyright (c) 2015 Yiister
 * @license https://github.com/yiister/yii2-advanced-grid/blob/master/LICENSE.md
 * @link http://yiister.ru/projects/advanced-grid
 */

namespace yiister\grid\assets;

use yii\web\AssetBundle;

/**
 * Class Asset
 * @package yiister\grid\assets
 */
class Asset extends AssetBundle
{
    public $sourcePath = '@yiister/grid/assets/dist';
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\grid\GridViewAsset',
    ];
    public $css = [
        'advanced-grid.css',
    ];
    public $js = [
        'advanced-grid.js',
    ];
}
