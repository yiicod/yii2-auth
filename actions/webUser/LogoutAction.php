<?php

namespace yiicod\auth\actions\webUser;

use Yii;
use yiicod\auth\actions\ActionEvent;
use yiicod\auth\actions\BaseAction;

/**
 * Login action 
 * @author Orlov Alexey <aaorlov@gmail.com>
 */
class LogoutAction extends BaseAction
{

    public $view = '';

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function run()
    {
        $this->controller->onBeforeLogout(new ActionEvent($this));

        Yii::$app->user->logout();

        $this->controller->onAfterLogout(new ActionEvent($this));

        return Yii::$app->controller->goHome();
    }

}
