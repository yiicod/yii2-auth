<?php

namespace yiicod\auth\actions\webUser;

use Yii;
use yiicod\auth\actions\BaseAction;
use yiicod\auth\actions\ActionEvent;

class SignupAction extends BaseAction
{

    public $view = '@yiicod/yii2-auth/views/webUser/signup';

    public function run()
    {        
        $signupFormClass = Yii::$app->get('auth')->modelMap['SignupForm']['class'];
        $userClass = Yii::$app->get('auth')->modelMap['User']['class'];        
        $model = new $signupFormClass($this->scenario);      
        $user = new $userClass($this->scenario);
        
        $isLoad = $model->load(Yii::$app->request->post());
        
        $this->controller->onBeforeSignup(new ActionEvent($this, ['params' => [
            'model' => $model,
            'user' => $user,
                ]
                ]));

        if ($isLoad) {
            if ($user = $model->signup($user)) {
                $this->controller->onAfterSignup(new ActionEvent($this, ['params' => [
                        'model' => $model,
                        'user' => $user
                    ]
                ]));
            } else {
                $this->controller->onErrorSignup(new ActionEvent($this, ['params' => [
                        'model' => $model,
                        'user' => $user
                    ]
                ]));
            }
        }

        return $this->controller->render($this->view, [
                    'model' => $model,
        ]);
    }

}
