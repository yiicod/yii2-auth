<?php

namespace yiicod\auth\actions\webUser;

use Yii;
use yiicod\auth\actions\BaseAction;
use yiicod\auth\actions\ActionEvent;

class RequestPasswordResetAction extends BaseAction
{

    public $view = '@yiicod/yii2-auth/views/webUser/requestPasswordResetToken';

    public function run()
    {
        $passwordResetRequestFormClass = Yii::$app->get('auth')->modelMap['PasswordResetRequestForm']['class'];
        $model = new $passwordResetRequestFormClass($this->scenario);

        $isLoad = $model->load(Yii::$app->request->post());
        
        $this->controller->onBeforeRequestPasswordReset(new ActionEvent($this, ['params' => ['model' => $model]]));

        if ($isLoad) {
            if ($model->validate()) {
                if ($model->resetPassword()) {                    
                    $this->controller->onAfterRequestPasswordReset(new ActionEvent($this, ['params' => ['model' => $model]]));
                } else {
                    $this->controller->onErrorRequestPasswordReset(new ActionEvent($this, ['params' => ['model' => $model]]));
                }
            }
        }

        return $this->controller->render($this->view, [
                    'model' => $model,
        ]);
    }

}
