<?php

namespace yiicod\auth\events;

use yii\base\ActionEvent;

class ResetPasswordEvent extends ActionEvent
{
    public $token;

    public function __construct($action, $token, array $config = [])
    {
        $this->action = $action;
        $this->token = $token;

        parent::__construct($action, $config);
    }
}
