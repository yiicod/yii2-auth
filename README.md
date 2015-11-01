Auth extensions
===============

If you want simple auth (login/signup/forgot), this is what you want! This extension
has simple action what have added in controller. Extension has events: 
- beforeLogin(ActionEvent)
- afterLogin(ActionEvent)
- errorLogin(ActionEvent)

- beforeSignup(ActionEvent)
- afterSignup(ActionEvent)
- errorSignup(ActionEvent)

- beforeCheckRecoveryKey(ActionEvent)
- afterCheckRecoveryKey(ActionEvent)
- errorCheckRecoveryKey(ActionEvent)

- beforeForgot(ActionEvent)
- afterForgot(ActionEvent)
- errorForgot(ActionEvent)

- beforeLogout(ActionEvent)
- afterLogout(ActionEvent)

You can create own behavior and use this events or extend exist behavior and 
change events. Use yiicod\auth\controllers\behaviors\AuthUserBehavior like parent
if you want extend behavior.

Installation
------------
```json
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/yiicod/yii2-auth.git"
    }
],
```
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yiicod/yii2-auth "*"
```

or add

```json
"yiicod/auth": "*"
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

Config ( This is all config for extension )
-------------------------------------------

```php
'components' => [
        'auth' => [
            'class' => 'yiicod\auth\Auth',
            'modelMap' => [
                'User' => [
                    'class' => 'yiicod\auth\models\UserModel',
                    'fieldLogin' => 'email', //requred
                    'fieldEmail' => 'email', //requred
                    'fieldPassword' => 'password', //requred
        //            'fieldConfirmPassword' => 'confirm', //requred
                    'fieldAuthKey' => 'authKey',
                    'fieldUsername' => 'username',
                    'fieldPasswordResetToken' => 'passwordResetToken', //requred
                    'fieldCreatedDate' => 'createdDate', //or null
                    'fieldUpdatedDate' => 'updatedDate', //or null            
                ],
                'PasswordResetRequestForm' => [
                    'class' => 'yiicod\auth\models\PasswordResetRequestForm',
                ],
                'ResetPasswordForm' => [
                    'class' => 'yiicod\auth\models\ResetPasswordForm',
                ],
                'SignupForm' => [
                    'class' => 'yiicod\auth\models\SignupForm',
                ],
                'LoginForm' => [
                    'class' => 'yiicod\auth\models\LoginForm',
                ]
            ],
            'controllers' => [
                'controllerMap' => [
                    'default' => [
                        'web-user' => 'yiicod\auth\controllers\WebUserController',
                    ],
                ],
                'default' => [
                    'web-user' => [
                        'layout' => '',
                        'behaviors' => [
                            'access' => [
                                'class' => yii\filters\AccessControl::className(),
                                'rules' => [
                                    [
                                        'allow' => true,
                                        'actions' => [ 'login', 'logout', 'requestPasswordReset', 'signup', 'resetPassword']
                                    ]
                                ]
                            ],
                            'authUserBehavior' => [
                                'class' => 'yiicod\auth\controllers\behaviors\AuthUserBehavior'
                            ]
                        ],
                    ],
                ]
            ],
            'condition' => [],  
        ],
]

'bootstrap' => ['auth']
```

Using
-----

After setting up the config better, but not necessarily to create a module **yiicod\auth**. 
If you create module, you can group your own code for auth functionality
You can set in config: "condition", this condition will be work for all
action: login, forgot, etc... Or you can set condition for only login action or forgot, etc...
