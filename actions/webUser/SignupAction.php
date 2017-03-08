<?php

namespace yiicod\auth\actions\webUser;

use Yii;
use yii\base\Action;
use yii\base\Event;
use yiicod\auth\events\SignupEvent;

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

        Event::trigger(self::class, static::EVENT_BEFORE_SIGNUP, new SignupEvent($this, $user, $model, ['sender' => $this]));

        if ($isLoad) {
            if ($user = $model->signup($user)) {
                Event::trigger(self::class, static::EVENT_AFTER_SIGNUP, new SignupEvent($this, $user, $model, ['sender' => $this]));
            } else {
                Event::trigger(self::class, static::EVENT_ERROR_SIGNUP, new SignupEvent($this, $user, $model, ['sender' => $this]));
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
            'user' => $user,
        ]);
    }
}
