<?php

namespace yiicod\auth\controllers\behaviors;

/*
 * Auth behavior with event for controller action
 * @author Orlov Alexey <aaorlov88@gmail.com>
 */

use Yii;
use yii\base\Behavior;
use yii\web\BadRequestHttpException;
use yiicod\auth\actions\ActionEvent;
use yiicod\auth\actions\webUser\LogoutAction;
use yiicod\auth\actions\webUser\RequestPasswordResetAction;
use yiicod\auth\actions\webUser\ResetPasswordAction;
use yiicod\auth\actions\webUser\SignupAction;

class AuthUserBehavior extends Behavior
{
    public function init()
    {
        // Signup
        Yii::$app->on(sprintf('yiicod.auth.actions.webUser.SignupAction.%s', SignupAction::EVENT_BEFORE_SIGNUP), [$this, 'beforeSignup']);
        Yii::$app->on(sprintf('yiicod.auth.actions.webUser.SignupAction.%s', SignupAction::EVENT_AFTER_SIGNUP), [$this, 'afterSignup']);
        Yii::$app->on(sprintf('yiicod.auth.actions.webUser.SignupAction.%s', SignupAction::EVENT_ERROR_SIGNUP), [$this, 'errorSignup']);

        // Login
        Yii::$app->on(sprintf('yiicod.auth.actions.webUser.LoginAction.%s', SignupAction::EVENT_BEFORE_SIGNUP), [$this, 'beforeLogin']);
        Yii::$app->on(sprintf('yiicod.auth.actions.webUser.LoginAction.%s', SignupAction::EVENT_AFTER_SIGNUP), [$this, 'afterLogin']);
        Yii::$app->on(sprintf('yiicod.auth.actions.webUser.LoginAction.%s', SignupAction::EVENT_ERROR_SIGNUP), [$this, 'errorLogin']);

        // Logout
        Yii::$app->on(sprintf('yiicod.auth.actions.webUser.LoginAction.%s', LogoutAction::EVENT_BEFORE_LOGOUT), [$this, 'beforeLogout']);
        Yii::$app->on(sprintf('yiicod.auth.actions.webUser.LoginAction.%s', LogoutAction::EVENT_AFTER_LOGOUT), [$this, 'afterLogout']);

        // ResetPassword
        Yii::$app->on(sprintf('yiicod.auth.actions.webUser.ResetPasswordAction.%s', ResetPasswordAction::EVENT_BEFORE_RESET_PASSWORD), [$this, 'beforeResetPassword']);
        Yii::$app->on(sprintf('yiicod.auth.actions.webUser.ResetPasswordAction.%s', ResetPasswordAction::EVENT_AFTER_RESET_PASSWORD), [$this, 'afterResetPassword']);
        Yii::$app->on(sprintf('yiicod.auth.actions.webUser.ResetPasswordAction.%s', ResetPasswordAction::EVENT_ERROR_RESET_PASSWORD), [$this, 'errorResetPassword']);

        // RequestPasswordReset
        Yii::$app->on(sprintf('yiicod.auth.actions.webUser.RequestPasswordResetAction.%s', RequestPasswordResetAction::EVENT_BEFORE_REQUEST_PASSWORD_RESET), [$this, 'beforeRequestPasswordReset']);
        Yii::$app->on(sprintf('yiicod.auth.actions.webUser.RequestPasswordResetAction.%s', RequestPasswordResetAction::EVENT_AFTER_REQUEST_PASSWORD_RESET), [$this, 'afterRequestPasswordReset']);
        Yii::$app->on(sprintf('yiicod.auth.actions.webUser.RequestPasswordResetAction.%s', RequestPasswordResetAction::EVENT_ERROR_REQUEST_PASSWORD_RESET), [$this, 'errorRequestPasswordReset']);
    }

    /**
     * After login action event
     *
     * @param ActionEvent $event Object has next params sender -> LoginAction,
     * params -> array('model' => UserModel)
     */
    public function afterLogin($event)
    {
        $event->action->controller->goHome();

        Yii::$app->getResponse()->send();
        Yii::$app->end();
    }

    /**
     * After signup action event
     *
     * @param ActionEvent $event Object has next params sender -> LoginAction,
     * params -> array('model' => UserModel)
     */
    public function afterSignUp($event)
    {
        if (Yii::$app->getUser()->login($event->params['user'])) {
            $event->action->controller->goHome();

            Yii::$app->getResponse()->send();
            Yii::$app->end();
        }
    }

    /**
     * After RequestPasswordReset action event
     *
     * @param ActionEvent $event Object has next params sender -> LoginAction,
     * params -> array('model' => UserModel)
     */
    public function afterRequestPasswordReset($event)
    {
        $mailerViewPath = Yii::$app->mailer->viewPath;

        Yii::$app->mailer->viewPath = '@yiicod/yii2-auth/mail';
        Yii::$app->mailer->compose('passwordResetToken', ['action' => $event->action, 'user' => $event->params['model']->findUser()])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($event->params['model']->email)
            ->setSubject('Password reset for ' . Yii::$app->name);

        Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

        Yii::$app->mailer->viewPath = $mailerViewPath;

        $event->action->controller->goHome();

        Yii::$app->getResponse()->send();
        Yii::$app->end();
    }

    /**
     * After ResetPassword action event
     *
     * @param ActionEvent $event Object has next params sender -> LoginAction,
     * params -> array('model' => UserModel, 'password' => 'Not encrypt password')
     */
    public function afterResetPassword($event)
    {
        Yii::$app->getSession()->setFlash('success', 'New password was saved.');

        $event->action->controller->goHome();

        Yii::$app->getResponse()->send();
        Yii::$app->end();
    }

    /**
     * Before login action event
     *
     * @param ActionEvent $event Object has next params sender -> LoginAction,
     * params -> array('model' => UserModel)
     *
     * @return mixed
     */
    public function beforeLogin($event)
    {
        if (!Yii::$app->user->isGuest) {
            return $event->action->controller->goHome();
        }
    }

    /**
     * Before signup action event
     *
     * @param ActionEvent $event Object has next params sender -> LoginAction,
     * params -> array('model' => UserModel)
     */
    public function beforeSignup($event)
    {
    }

    /**
     * Before RequestPasswordReset action event
     *
     * @param ActionEvent $event Object has next params sender -> LoginAction,
     * params -> array('model' => UserModel)
     */
    public function beforeRequestPasswordReset($event)
    {
    }

    /**
     * Before ResetPassword action event
     *
     * @param ActionEvent $event Object has next params sender -> LoginAction,
     * params -> array('model' => UserModel, 'password' => 'Not encrypt password')
     */
    public function beforeResetPassword($event)
    {
    }

    /**
     * error ResetPassword action event
     *
     * @param ActionEvent $event Object has next params sender -> LoginAction,
     * params -> array('model' => UserModel)
     *
     * @throws BadRequestHttpException
     */
    public function errorResetPassword($event)
    {
        throw new BadRequestHttpException($event->params['e']->getMessage());
    }

    /**
     * Error RequestPasswordReset action event
     *
     * @param ActionEvent $event Object has next params sender -> LoginAction,
     * params -> array('model' => UserModel)
     */
    public function errorRequestPasswordReset($event)
    {
        Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
    }

    public function afterLogout($event)
    {
    }

    public function beforeLogout($event)
    {
    }

    public function errorLogin($event)
    {
    }

    public function errorSignup($event)
    {
    }
}
