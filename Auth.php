<?php

namespace yiicod\auth;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class Auth extends Component implements BootstrapInterface
{
    /**
     * @var array Models settings
     */
    public $modelMap = [];

    public function bootstrap($qpp)
    {
        //Merge main extension config with local extension config
        $config = include(dirname(__FILE__) . '/config/main.php');
        foreach ($config as $key => $value) {
            if (is_array($value)) {
                $this->{$key} = ArrayHelper::merge($value, $this->{$key});
            } elseif (null === $this->{$key}) {
                $this->{$key} = $value;
            }
        }

        Yii::setAlias('@yiicod', realpath(dirname(__FILE__) . '/..'));
        // Namespace for migration
        Yii::setAlias('@yiicod_auth_migrations', realpath(dirname(__FILE__) . '/migrations'));
    }
}
