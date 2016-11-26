Auth extensions
===============

If you want simple auth (login/signup/forgot), this is what you want! This extension
has simple action what have added in controller. Extension has events: 

[![Latest Stable Version](https://poser.pugx.org/yiicod/yii2-auth/v/stable)](https://packagist.org/packages/yiicod/yii2-auth) [![Total Downloads](https://poser.pugx.org/yiicod/yii2-auth/downloads)](https://packagist.org/packages/yiicod/yii2-auth) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiicod/yii2-auth/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiicod/yii2-auth/?branch=master)[![Code Climate](https://codeclimate.com/github/yiicod/yii2-auth/badges/gpa.svg)](https://codeclimate.com/github/yiicod/yii2-auth)

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yiicod/yii2-auth "*"
```

or add

```json
"yiicod/yii2-auth": "*"
```

to the require section of your composer.json.

run
```php
php yii migrate/up --migrationPath=@yiicod/auth/migrations
```
Please note that messages are wrapped with ```Yii::t()``` to support message translations, you should define default message source for them if you don't use i18n.
```php
'i18n' => [
    'translations' => [
        '*' => [
            'class' => 'yii\i18n\PhpMessageSource'
        ],
    ],
],
```
Config
------

```php
'components' => [
    'auth' => [
        'class' => 'yiicod\auth\Auth',
    ],
]

'bootstrap' => ['auth']
```

Using
-----

Copy yiicod\auth\controllers\WebUserController to controllers folder.
After this you can use actions
```php
    /**
     * Declares class-based actions.
     * For change functional use AuthUserBehavior.
     * Auth events:
     *
     * - beforeLogin(ActionEvent)
     * - afterLogin(ActionEvent)
     * - errorLogin(ActionEvent)
     *
     * - beforeSignup(ActionEvent)
     * - afterSignup(ActionEvent)
     * - errorSignup(ActionEvent)
     *
     * - beforeCheckRecoveryKey(ActionEvent)
     * - afterCheckRecoveryKey(ActionEvent)
     * - errorCheckRecoveryKey(ActionEvent)
     *
     * - beforeForgot(ActionEvent)
     * - afterForgot(ActionEvent)
     * - errorForgot(ActionEvent)
     *
     * - beforeLogout(ActionEvent)
     * - afterLogout(ActionEvent)
     *
     *
     * Global events
     * yiicod.auth.controllers.webUser.[Action class name].[Event name (beforeLogin)]
     * 
     *
     */
    public function actions()
    {

        return ArrayHelper::merge(parent::actions(), [
                'login' => [
                    'class' => \yiicod\auth\actions\webUser\LoginAction::className(),
                ],
                'requestPasswordReset' => [
                    'class' => \yiicod\auth\actions\webUser\RequestPasswordResetAction::className(),
                ],
                'logout' => [
                    'class' => \yiicod\auth\actions\webUser\LogoutAction::className(),
                ],
                'signup' => [
                    'class' => \yiicod\auth\actions\webUser\SignupAction::className(),
                ],
                'resetPassword' => [
                    'class' => \yiicod\auth\actions\webUser\ResetPasswordAction::className(),
                ],
            ]
        );
    }
```
