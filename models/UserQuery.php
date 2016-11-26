<?php

namespace yiicod\auth\models;

use yii\db\ActiveQuery;

class UserQuery extends ActiveQuery implements UserQueryInterface
{
    public function identity()
    {
        return $this;
    }

    public function byUsername()
    {
        return $this;
    }

    public function byPasswordResetToken()
    {
        return $this;
    }

    public function byPasswordResetRequest()
    {
        return $this;
    }
}
