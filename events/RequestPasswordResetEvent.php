<?php

namespace yiicod\auth\events;

use yii\base\ActionEvent;
use yiicod\auth\models\PasswordResetRequestForm;

class RequestPasswordResetEvent extends ActionEvent
{
    /**
     * @var PasswordResetRequestForm
     */
    public $model;

    public function __construct($action, $model, array $config = [])
    {
        $this->action = $action;
        $this->model = $model;

        parent::__construct($action, $config);
    }
}
