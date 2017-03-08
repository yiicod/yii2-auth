<?php

namespace yiicod\auth\tests\data;

/**
 * Class Controller
 *
 * @package yiicod\auth\tests\data
 */
class Controller extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function render($view, $params = [])
    {
        return [
            'view' => $view,
            'params' => $params,
        ];
    }
}
