<?php

namespace yiicod\auth\tests\actions;

use Yii;
use yiicod\auth\actions\webUser\ResetPasswordAction;
use yiicod\auth\models\UserModel;
use yiicod\auth\tests\TestCase;

class ResetPasswordActionTest extends TestCase
{
    public function testRun()
    {
        $model = UserModel::findOne(['email' => 'test@mail.com']);
        $model->generatePasswordResetToken();
        $model->save(false);

        Yii::$app->request->bodyParams = [
            'ResetPasswordForm' => [
                'password' => '123123',
            ],
        ];

        $action = new ResetPasswordAction('resetPasswordAction', $this->createController());
        $responce = $action->run($model->password_reset_token);

        $this->assertTrue(count($responce['params']['model']->getErrors()) <= 0);

        $model = UserModel::findOne(['email' => 'test@mail.com']);
        $this->assertTrue($model->validatePassword('123123'));
    }
}
