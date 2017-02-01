<?php

namespace yiicod\auth\events;

use yii\base\ActionEvent;

class SignupEvent extends ActionEvent
{
    public $model;

    public $user;

    public function __construct($action, $user, $model, array $config = [])
    {
        $this->action = $action;
        $this->user = $user;
        $this->model = $model;

        parent::__construct($action, $config);
    }
}
