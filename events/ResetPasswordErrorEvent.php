<?php

namespace yiicod\auth\events;

use yii\base\ActionEvent;

class ResetPasswordErrorEvent extends ActionEvent
{
    public $model;

    public $token;

    public $e;

    public function __construct($action, $token, $e, array $config = [])
    {
        $this->e = $e;
        $this->token = $token;
        $this->action = $action;

        parent::__construct($action, $config);
    }
}
