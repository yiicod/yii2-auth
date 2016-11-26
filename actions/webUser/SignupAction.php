<?php

namespace yiicod\auth\actions\webUser;

use Yii;
use yii\base\Action;
use yii\base\Event;
use yiicod\auth\actions\ActionEvent;

class SignupAction extends Action
{
    const EVENT_BEFORE_SIGNUP = 'beforeSignup';
    const EVENT_AFTER_SIGNUP = 'afterSignup';
    const EVENT_ERROR_SIGNUP = 'errorSignup';

    public $view = '@yiicod/yii2-auth/views/webUser/signup';

    /**
     * Model scenario
     *
     * @var
     */
    public $scenario;

    public function run()
    {
        $signupFormClass = Yii::$app->get('auth')->modelMap['signupForm']['class'];
        $userClass = Yii::$app->get('auth')->modelMap['user']['class'];
        $model = new $signupFormClass($this->scenario);
        $user = new $userClass($this->scenario);

        $isLoad = $model->load(Yii::$app->request->post());

        $this->trigger(static::EVENT_BEFORE_SIGNUP, new ActionEvent($this, [
            'params' => [
                'model' => $model,
                'user' => $user,
            ],
        ]));

        if ($isLoad) {
            if ($user = $model->signup($user)) {
                $this->trigger(static::EVENT_AFTER_SIGNUP, new ActionEvent($this, [
                    'params' => [
                        'model' => $model,
                        'user' => $user,
                    ],
                ]));
            } else {
                $this->trigger(static::EVENT_ERROR_SIGNUP, new ActionEvent($this, [
                    'params' => [
                        'model' => $model,
                        'user' => $user,
                    ],
                ]));
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }

    public function trigger($name, Event $event = null)
    {
        Yii::$app->trigger(sprintf('yiicod.auth.actions.webUser.SignupAction.%s', $name), $event);

        return parent::trigger($name, $event);
    }
}
