<?php

namespace yiicod\auth\actions\webUser;

use Yii;
use yiicod\auth\actions\BaseAction;
use yiicod\auth\actions\ActionEvent;

class LoginAction extends BaseAction
{

    public $view = '@yiicod/yii2-auth/views/webUser/login';

    public function run()
    {
        $loginFormClass = Yii::$app->get('auth')->modelMap['LoginForm']['class'];
        $model = new $loginFormClass($this->scenario);

        $isLoad = $model->load(Yii::$app->request->post());
        
        $this->controller->onBeforeLogin(new ActionEvent($this, ['params' => ['model' => $model]]));
        if ($isLoad) {
            if ($model->login()) {
                $this->controller->onAfterLogin(new ActionEvent($this, ['params' => ['model' => $model]]));
            } else {
                $this->controller->onErrorLogin(new ActionEvent($this, ['params' => ['model' => $model]]));
            }
        } else {
            return $this->controller->render($this->view, [
                        'model' => $model,
            ]);
        }
    }

}
