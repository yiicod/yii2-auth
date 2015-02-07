<?php

namespace yiicod\auth\actions\webUser;

use Yii;
use yiicod\auth\actions\BaseAction;
use yiicod\auth\actions\ActionEvent;

class ResetPasswordAction extends BaseAction
{

    public $view = '@yiicod/yii2-auth/views/webUser/requestPasswordResetToken';

    public function run($token)
    {
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

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            $this->controller->onAfterResetPassword(new ActionEvent($this, ['params' => ['model' => $model]]));            
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

}
