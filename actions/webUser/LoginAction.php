<?php

namespace yiicod\auth\actions\webUser;

use Yii;
use yii\base\Action;
use yii\base\Event;
use yiicod\auth\actions\ActionEvent;

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

        $this->trigger(static::EVENT_BEFORE_LOGIN, new ActionEvent($this, ['params' => ['model' => $model]]));

        if ($isLoad) {
            if ($model->login()) {
                $this->trigger(static::EVENT_AFTER_LOGIN, new ActionEvent($this, ['params' => ['model' => $model]]));
            } else {
                $this->trigger(static::EVENT_ERROR_LOGIN, new ActionEvent($this, ['params' => ['model' => $model]]));
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }

    public function trigger($name, Event $event = null)
    {
        Yii::$app->trigger(sprintf('yiicod.auth.actions.webUser.LoginAction.%s', $name), $event);

        return parent::trigger($name, $event);
    }
}
