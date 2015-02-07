<?php

return [
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
];
