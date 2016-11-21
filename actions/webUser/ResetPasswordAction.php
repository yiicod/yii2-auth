<?php

namespace yiicod\auth\actions\webUser;

use Yii;
use yii\base\Action;
use yii\base\Event;
use yii\base\InvalidParamException;
use yiicod\auth\actions\ActionEvent;

class ResetPasswordAction extends Action
{
    const EVENT_BEFORE_RESET_PASSWORD = 'beforeResetPassword';
    const EVENT_AFTER_RESET_PASSWORD = 'afterResetPassword';
    const EVENT_ERROR_RESET_PASSWORD = 'errorResetPassword';

    public $view = '@yiicod/yii2-auth/views/webUser/resetPassword';

    public function run($token)
    {
        $model = null;
        $resetPasswordFormClass = Yii::$app->get('auth')->modelMap['resetPasswordForm']['class'];

        $this->trigger(static::EVENT_BEFORE_RESET_PASSWORD, new ActionEvent($this, ['params' => ['model' => $model]]));
        try {
            $model = new $resetPasswordFormClass($token);
        } catch (InvalidParamException $e) {
            $this->trigger(static::EVENT_ERROR_RESET_PASSWORD, new ActionEvent($this, [
                'params' => [
                    'token' => $token,
                    'e' => $e
                ]
            ]));
        }

        if ($model instanceof $resetPasswordFormClass &&
            $model->load(Yii::$app->request->post()) &&
            $model->validate() &&
            $model->resetPassword()
        ) {
            $this->trigger(static::EVENT_AFTER_RESET_PASSWORD, new ActionEvent($this, ['params' => ['model' => $model]]));
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }

    public function trigger($name, Event $event = null)
    {
        Yii::$app->trigger(sprintf('yiicod.auth.actions.webUser.ResetPasswordAction.%s', $name), $event);
        return parent::trigger($name, $event);
    }
}
