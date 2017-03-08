<?php

namespace yiicod\auth\models;

use Yii;
use yii\base\Model;

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
        $targetClass = Yii::$app->get('auth')->modelMap['user']['class'];

        return [
            ['username', 'filter', 'filter' => 'trim'],
            [['username', 'confirm'], 'required'],
            [
                'username',
                'unique',
                'targetClass' => $targetClass,
                'targetAttribute' => $targetClass::attributesMap()['fieldUsername'],
                'message' => 'This username has already been taken.',
            ],
            [['password'], 'compare', 'compareAttribute' => 'confirm', 'operator' => '==', 'skipOnEmpty' => false],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass' => $targetClass,
                'targetAttribute' => $targetClass::attributesMap()['fieldEmail'],
                'message' => 'This email address has already been taken.',
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
                $userClass = Yii::$app->get('auth')->modelMap['user']['class'];
                $user = new $userClass();
            }
            $user->username = $this->username;
            $user->email = $this->email;
            $user->generatePassword($this->password);
            $user->generateAuthKey();
            $user->status = 1;
            $user->save();

            return $user;
        }

        return null;
    }
}
