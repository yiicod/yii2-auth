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
change events. Use yii2cod\auth\controllers\behaviors\AuthUserBehavior like parent
if you want extend behavior.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yii2cod/auth "*"
```

or add

```json
"yii2cod/auth": "*"
```

to the require section of your composer.json.

Config ( This is all config for extensions )
---------------------------------------------

```php
'components' => [
        'auth' => [
            'class' => 'yii2cod\auth\Auth',
            'modelMap' => [
                'User' => [
                    'class' => 'yii2cod\auth\models\UserModel',
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
                    'class' => 'yii2cod\auth\models\PasswordResetRequestForm',
                ],
                'ResetPasswordForm' => [
                    'class' => 'yii2cod\auth\models\ResetPasswordForm',
                ],
                'SignupForm' => [
                    'class' => 'yii2cod\auth\models\SignupForm',
                ],
                'LoginForm' => [
                    'class' => 'yii2cod\auth\models\LoginForm',
                ]
            ],
            'controllers' => [
                'controllerMap' => [
                    'default' => [
                        'web-user' => 'yii2cod\auth\controllers\WebUserController',
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
                                'class' => 'yii2cod\auth\controllers\behaviors\AuthUserBehavior'
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
