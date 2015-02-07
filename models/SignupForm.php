<?php

namespace yiicod\auth\models;

use yiicod\auth\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{

    public $username;
    public $email;
    public $password;
    public $confirm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            [['username', 'confirm'], 'required'],
            [
                'username',
                'unique',
                'targetClass' => Yii::$app->get('auth')->modelMap['User']['class'],
                'targetAttribute' => Yii::$app->get('auth')->modelMap['User']['fieldUsername'],
                'message' => 'This username has already been taken.'
            ],
            [['password'], 'compare', 'compareAttribute' => 'confirm', 'operator' => '==', 'skipOnEmpty' => false],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass' => Yii::$app->get('auth')->modelMap['User']['class'],
                'targetAttribute' => Yii::$app->get('auth')->modelMap['User']['fieldEmail'],
                'message' => 'This email address has already been taken.'
            ],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup($user = null)
    {
        if ($this->validate()) {
            if (null === $user) {
                $userClass = Yii::$app->get('auth')->modelMap['User']['class'];
                $user = new $userClass();
            }
            $user->username = $this->username;
            $user->email = $this->email;
            $user->generatePassword($this->password);
            $user->generateAuthKey();
            $user->save();
            return $user;
        }

        return null;
    }

}
