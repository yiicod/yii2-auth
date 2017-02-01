<?php

namespace yiicod\auth\controllers\behaviors;

/*
 * Auth behavior with event for controller action
 * @author Orlov Alexey <aaorlov88@gmail.com>
 */

use Yii;
use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\base\Event;
use yii\web\BadRequestHttpException;
use yiicod\auth\actions\webUser\LoginAction;
use yiicod\auth\actions\webUser\LogoutAction;
use yiicod\auth\actions\webUser\RequestPasswordResetAction;
use yiicod\auth\actions\webUser\ResetPasswordAction;
use yiicod\auth\actions\webUser\SignupAction;
use yiicod\auth\events\LoginEvent;
use yiicod\auth\events\RequestPasswordResetEvent;
use yiicod\auth\events\ResetPasswordEvent;
use yiicod\auth\events\SignupEvent;

class AuthUserBehavior extends Behavior
{
    /**
     * Assign all events
     */
    public function init()
    {
        // Signup
        Event::on(SignupAction::class, SignupAction::EVENT_BEFORE_SIGNUP, [$this, 'beforeSignup']);
        Event::on(SignupAction::class, SignupAction::EVENT_AFTER_SIGNUP, [$this, 'afterSignup']);
        Event::on(SignupAction::class, SignupAction::EVENT_ERROR_SIGNUP, [$this, 'errorSignup']);

        // Login
        Event::on(LoginAction::class, LoginAction::EVENT_BEFORE_LOGIN, [$this, 'beforeLogin']);
        Event::on(LoginAction::class, LoginAction::EVENT_AFTER_LOGIN, [$this, 'afterLogin']);
        Event::on(LoginAction::class, LoginAction::EVENT_ERROR_LOGIN, [$this, 'errorLogin']);

        // Logout
        Event::on(LogoutAction::class, LogoutAction::EVENT_BEFORE_LOGOUT, [$this, 'beforeLogout']);
        Event::on(LogoutAction::class, LogoutAction::EVENT_AFTER_LOGOUT, [$this, 'afterLogout']);

        // ResetPassword
        Event::on(ResetPasswordAction::class, ResetPasswordAction::EVENT_BEFORE_RESET_PASSWORD, [$this, 'beforeResetPassword']);
        Event::on(ResetPasswordAction::class, ResetPasswordAction::EVENT_AFTER_RESET_PASSWORD, [$this, 'afterResetPassword']);
        Event::on(ResetPasswordAction::class, ResetPasswordAction::EVENT_ERROR_RESET_PASSWORD, [$this, 'errorResetPassword']);

        // RequestPasswordReset
        Event::on(RequestPasswordResetAction::class, RequestPasswordResetAction::EVENT_BEFORE_REQUEST_PASSWORD_RESET, [$this, 'beforeRequestPasswordReset']);
        Event::on(RequestPasswordResetAction::class, RequestPasswordResetAction::EVENT_AFTER_REQUEST_PASSWORD_RESET, [$this, 'afterRequestPasswordReset']);
        Event::on(RequestPasswordResetAction::class, RequestPasswordResetAction::EVENT_ERROR_REQUEST_PASSWORD_RESET, [$this, 'errorRequestPasswordReset']);
    }

    /**
     * After login action event
     *
     * @param LoginEvent $event
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
     * @param SignupEvent $event
     */
    public function afterSignUp($event)
    {
        if (Yii::$app->getUser()->login($event->user)) {
            $event->action->controller->goHome();

            Yii::$app->getResponse()->send();
            Yii::$app->end();
        }
    }

    /**
     * After RequestPasswordReset action event
     *
     * @param RequestPasswordResetEvent $event
     */
    public function afterRequestPasswordReset($event)
    {
        $mailerViewPath = Yii::$app->mailer->viewPath;

        Yii::$app->mailer->viewPath = '@yiicod/yii2-auth/mail';
        Yii::$app->mailer->compose('passwordResetToken', ['action' => $event->action, 'user' => $event->model->findUser()])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($event->model->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();

        Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions.');

        Yii::$app->mailer->viewPath = $mailerViewPath;

        $event->action->controller->goHome();

        Yii::$app->getResponse()->send();
        Yii::$app->end();
    }

    /**
     * After ResetPassword action event
     *
     * @param ResetPasswordEvent $event
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
     * @param LoginEvent $event
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
     * @param SignupEvent $event
     */
    public function beforeSignup($event)
    {
    }

    /**
     * Before RequestPasswordReset action event
     *
     * @param RequestPasswordResetEvent $event
     */
    public function beforeRequestPasswordReset($event)
    {
    }

    /**
     * Before ResetPassword action event
     *
     * @param ResetPasswordEvent $event
     */
    public function beforeResetPassword($event)
    {
    }

    /**
     * error ResetPassword action event
     *
     * @param ResetPasswordEvent $event
     *
     * @throws BadRequestHttpException
     */
    public function errorResetPassword($event)
    {
        throw new BadRequestHttpException($event->e->getMessage());
    }

    /**
     * Error RequestPasswordReset action event
     *
     * @param RequestPasswordResetEvent $event
     */
    public function errorRequestPasswordReset($event)
    {
        Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
    }

    /**
     * @param ActionEvent $event
     */
    public function afterLogout($event)
    {
    }

    /**
     * @param ActionEvent $event
     */
    public function beforeLogout($event)
    {
    }

    /**
     * @param LoginEvent $event
     */
    public function errorLogin($event)
    {
    }

    /**
     * @param SignupEvent $event
     */
    public function errorSignup($event)
    {
    }
}
