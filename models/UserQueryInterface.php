<?php
namespace yiicod\auth\models;

interface UserQueryInterface
{
    public function identity();

    public function byUsername();

    public function byPasswordResetToken();

    public function byPasswordResetRequest();
}