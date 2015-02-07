<?php

namespace yiicod\auth\models\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\InvalidParamException;

class UserBehavior extends Behavior
{

    /**
     *
     * @var type 
     */
    public $componentId = 'user';

    /**
     *
     * @var type 
     */
    public $modelId = 'user';

    /**
     * Returns a value indicating whether the model has an attribute with the specified name.
     * @param string $name the name of the attribute
     * @return boolean whether the model has an attribute with the specified name.
     */
    public function hasAttr($name)
    {
        $fieldAttr = 'field' . ucfirst($name);
        return isset(Yii::$app->get('auth')->modelMap['User'][$fieldAttr]) &&
                $this->owner->hasAttribute(Yii::$app->get('auth')->modelMap['User'][$fieldAttr]);
    }

    /**
     * Returns the named attribute value.
     * If this record is the result of a query and the attribute is not loaded,
     * null will be returned.
     * @param string $name the attribute name
     * @return mixed the attribute value. Null if the attribute is not set or does not exist.
     * @see hasAttribute()
     */
    public function getAttr($name)
    {
        $name = 'field' . ucfirst($name);
        return $this->owner->getAttribute(Yii::$app->get('auth')->modelMap['User'][$name]);
    }

    /**
     * Sets the named attribute value.
     * @param string $name the attribute name
     * @param mixed $value the attribute value.
     * @throws InvalidParamException if the named attribute does not exist.
     * @see hasAttribute()
     */
    public function setAttr($name, $value)
    { 
        if ($this->hasAttr($name)) {                       
            $fieldAttr = 'field' . ucfirst($name);
            $this->owner->{Yii::$app->get('auth')->modelMap['User'][$fieldAttr]} = $value;
        } else {
            throw new InvalidParamException(get_class($this) . ' has no attribute named "' . $name . '".');
        }
    }

    /**
     * 
     * @param type $name
     * @param type $value
     * @return type
     */
    public function __set($name, $value)
    {
        if ($this->hasAttr($name)) {
            return $this->setAttr($name, $value);
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * 
     * @param type $name
     * @return type
     */
    public function __get($name)
    {
        if (strpos($name, 'field') === 0 && $this->hasFieldByModelMap($name)) {
            return $this->getFieldByModelMap($name);
        }
        if ($this->hasAttr($name)) {
            return $this->getAttr($name);
        } else {
            return parent::__get($name);
        }
    }

    /**
     * 
     * @param type $name
     * @return boolean
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if (strpos($name, 'field') === 0 && $this->hasFieldByModelMap($name)) {
            return true;
        }
//        $fieldAttr = 'field' . ucfirst($name);
        if ($this->hasAttr($name)) {
            return true;
        } else {
            return parent::canGetProperty($name, $checkVars = true);
        }
    }

    /**
     * 
     * @param type $name
     * @return boolean
     */
    public function canSetProperty($name, $checkVars = true)
    {        
        if ($this->hasAttr($name)) {
            return true;
        } else {
            return parent::canSetProperty($name, $checkVars = true);
        }
    }

    /**
     * 
     * @param type $name
     * @return type
     */
    public function getFieldByModelMap($name)
    {
        if ($this->hasFieldByModelMap($name)) {
            return Yii::$app->get($this->componentId)->modelMap[$this->modelId][$name];
        }
        return null;
    }

    /**
     * 
     * @param type $name
     * @return type
     */
    public function hasFieldByModelMap($name)
    {
        return isset(Yii::app()->get($this->componentId)->modelMap[$this->modelId][$name]);
    }

}
