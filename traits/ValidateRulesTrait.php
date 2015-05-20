<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 20.05.2015
 */
namespace skeeks\cms\traits;
/**
 * Class ValidateRulesTrait
 * @package skeeks\cms\traits
 */
trait ValidateRulesTrait
{
    public function validateServerName($attribute)
    {
        if(!preg_match('/^[а-яa-z0-9.]{2,255}$/', $this->$attribute))
        {
            $this->addError($attribute, 'Используйте только буквы в нижнем регистре и цифры. Пример site.ru (2-255 символов)');
        }
    }


    public function validateCode($attribute)
    {
        if(!preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9]{1,255}$/', $this->$attribute))
        {
            $this->addError($attribute, 'Используйте только буквы латинского алфавита в нижнем или верхнем регистре и цифры, первый символ буква (Пример code1)');
        }
    }

}