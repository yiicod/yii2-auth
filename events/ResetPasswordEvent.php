<?php

namespace yiicod\auth\events;

use yii\base\ActionEvent;

class ResetPasswordEvent extends ActionEvent
{
    public $model;

    public function __construct($action, $model, array $config = [])
    {
        $this->action = $action;
        $this->model = $model;

        parent::__construct($action, $config);
    }
}
