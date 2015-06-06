<?php

namespace yiicod\auth\actions\webUser;

use Yii;
use yii\base\InvalidParamException;
use yiicod\auth\actions\ActionEvent;
use yiicod\auth\actions\BaseAction;

class ResetPasswordAction extends BaseAction
{

    public function run($token)
    {
        $model = null;
        $resetPasswordFormClass = Yii::$app->get('auth')->modelMap['ResetPasswordForm']['class'];        

        $this->controller->onBeforeResetPassword(new ActionEvent($this, ['params' => ['model' => $model]]));
        try {
            $model = new $resetPasswordFormClass($token, ['scenario' => $this->scenario]);
        } catch (InvalidParamException $e) {
            $this->controller->onErrorResetPassword(new ActionEvent($this, ['params' => [
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
            $this->controller->onAfterResetPassword(new ActionEvent($this, ['params' => ['model' => $model]]));
        }

        return;
    }

}
