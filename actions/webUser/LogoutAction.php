<?php

namespace yiicod\auth\actions\webUser;

use Yii;
use yii\base\Action;
use yii\base\Event;
use yiicod\auth\actions\ActionEvent;

/**
 * Login action
 * @author Orlov Alexey <aaorlov@gmail.com>
 */
class LogoutAction extends Action
{

    const EVENT_BEFORE_LOGOUT = 'beforeLogout';
    const EVENT_AFTER_LOGOUT = 'afterLogout';

    public $view = '';

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function run()
    {
        $this->trigger(static::EVENT_BEFORE_LOGOUT, new ActionEvent($this));

        Yii::$app->user->logout();

        $this->trigger(static::EVENT_AFTER_LOGOUT, new ActionEvent($this));

        return Yii::$app->controller->goHome();
    }

    public function trigger($name, Event $event = null)
    {
        Yii::$app->trigger(sprintf('yiicod.auth.actions.webUser.LogoutAction.%s', $name), $event);
        return parent::trigger($name, $event);
    }
}
