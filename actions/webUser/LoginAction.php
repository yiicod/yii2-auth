<?php

namespace yiicod\auth\actions\webUser;

use Yii;
use yii\base\Action;
use yii\base\Event;
use yiicod\auth\events\LoginEvent;

class LoginAction extends Action
{
    const EVENT_BEFORE_LOGIN = 'beforeLogin';
    const EVENT_AFTER_LOGIN = 'afterLogin';
    const EVENT_ERROR_LOGIN = 'errorLogin';

    public $view = '@yiicod/yii2-auth/views/webUser/login';

    /**
     * Model scenario
     *
     * @var
     */
    public $scenario;

    public function run()
    {
        $loginFormClass = Yii::$app->get('auth')->modelMap['loginForm']['class'];
        $model = new $loginFormClass($this->scenario);

        $isLoad = $model->load(Yii::$app->request->post());

        Event::trigger(self::class, static::EVENT_BEFORE_LOGIN, new LoginEvent($this, $model, ['sender' => $this]));

        if ($isLoad) {
            if ($model->login()) {
                Event::trigger(self::class, static::EVENT_AFTER_LOGIN, new LoginEvent($this, $model, ['sender' => $this]));
            } else {
                Event::trigger(self::class, static::EVENT_ERROR_LOGIN, new LoginEvent($this, $model, ['sender' => $this]));
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }

//    public function trigger($name, Event $event = null)
//    {
//        Yii::$app->trigger(sprintf('yiicod.auth.actions.webUser.LoginAction.%s', $name), $event);

//        return parent::trigger($name, $event);
//    }
}
