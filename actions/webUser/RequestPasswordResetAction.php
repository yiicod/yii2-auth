<?php

namespace yiicod\auth\actions\webUser;

use Yii;
use yii\base\Action;
use yii\base\Event;
use yiicod\auth\events\RequestPasswordResetEvent;

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

        Event::trigger(self::class, static::EVENT_BEFORE_REQUEST_PASSWORD_RESET, new RequestPasswordResetEvent($this, $model, ['sender' => $this]));

        if ($isLoad) {
            if ($model->validate()) {
                if ($model->resetPassword()) {
                    Event::trigger(self::class, static::EVENT_AFTER_REQUEST_PASSWORD_RESET, new RequestPasswordResetEvent($this, $model, ['sender' => $this]));
                } else {
                    Event::trigger(self::class, static::EVENT_ERROR_REQUEST_PASSWORD_RESET, new RequestPasswordResetEvent($this, $model, ['sender' => $this]));
                }
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}
