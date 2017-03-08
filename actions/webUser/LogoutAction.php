<?php

namespace yiicod\auth\actions\webUser;

use Yii;
use yii\base\Action;
use yii\base\ActionEvent;
use yii\base\Event;

/**
 * Login action
 *
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
        Event::trigger(self::class, static::EVENT_BEFORE_LOGOUT, new ActionEvent($this, ['sender' => $this]));

        Yii::$app->user->logout();

        Event::trigger(self::class, static::EVENT_AFTER_LOGOUT, new ActionEvent($this, ['sender' => $this]));

        return $this->controller->goHome();
    }
}
