<?php

namespace yiicod\auth;

use Yii;
use yii\base\Component;
use yii\console\Application;
use yii\helpers\ArrayHelper;

class Auth extends Component
{

    /**
     * @var ARRAY model settings
     */
    public $modelMap = array();

    /**
     * @var array Controllers settings
     */
    public $controllers = array();

    /**
     *
     * @var type 
     */
    public $authUserBehavior = null;

    /**
     *
     * @var type 
     */
    public $condition = array();

    public function init()
    {
        parent::init();

        //Merge main extension config with local extension config
        $config = include(dirname(__FILE__) . '/config/main.php');
        foreach ($config as $key => $value) {
            if (is_array($value)) {
                $this->{$key} = ArrayHelper::merge($value, $this->{$key});
            } elseif (null === $this->{$key}) {
                $this->{$key} = $value;
            }
        }

        if (!Yii::$app instanceof Application) {
            //Merge controllers map
            $route = Yii::$app->getRequest()->getPathInfo();
            $route = empty($route) ? Yii::$app->getRequest()->getQueryParam('r', '') : $route;
            $module = substr($route, 0, strpos($route, '/'));
            if (Yii::$app->hasModule($module) && isset($this->controllers['controllerMap'][$module])) {
                Yii::$app->getModule($module)->controllerMap = ArrayHelper::merge($this->controllers['controllerMap'][$module], Yii::$app->getModule($module)->controllerMap);
            } elseif (isset($this->controllers['controllerMap']['default'])) {
                Yii::$app->controllerMap = ArrayHelper::merge($this->controllers['controllerMap']['default'], Yii::$app->controllerMap);
            }
        }

        Yii::setAlias('@yiicod', realpath(dirname(__FILE__) . '/..'));
    }

}
