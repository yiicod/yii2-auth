<?php

namespace yiicod\auth\actions\webUser;

use Yii;
use yii\base\Action;
use yii\base\Event;
use yiicod\auth\actions\ActionEvent;

class RequestPasswordResetAction extends Action
{
    const EVENT_AFTER_REQUEST_PASSWORD_RESET = 'afterRequestPasswordReset';
    const EVENT_BEFORE_REQUEST_PASSWORD_RESET = 'beforeRequestPasswordReset';
    const EVENT_ERROR_REQUEST_PASSWORD_RESET = 'errorRequestPasswordReset';

    public $view = '@yiicod/yii2-auth/views/webUser/requestPasswordResetToken';

    /**
     * Model scenario
     *
     * @var
     */
    public $scenario;

    public function run()
    {
        $passwordResetRequestFormClass = Yii::$app->get('auth')->modelMap['passwordResetRequestForm']['class'];
        $model = new $passwordResetRequestFormClass($this->scenario);

        $isLoad = $model->load(Yii::$app->request->post());

        $this->trigger(static::EVENT_BEFORE_REQUEST_PASSWORD_RESET, new ActionEvent($this, ['params' => ['model' => $model]]));

        if ($isLoad) {
            if ($model->validate()) {
                if ($model->resetPassword()) {
                    $this->trigger(static::EVENT_AFTER_REQUEST_PASSWORD_RESET, new ActionEvent($this, ['params' => ['model' => $model]]));
                } else {
                    $this->trigger(static::EVENT_ERROR_REQUEST_PASSWORD_RESET, new ActionEvent($this, ['params' => ['model' => $model]]));
                }
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }

    public function trigger($name, Event $event = null)
    {
        Yii::$app->trigger(sprintf('yiicod.auth.actions.webUser.RequestPasswordResetAction.%s', $name), $event);

        return parent::trigger($name, $event);
    }
}
