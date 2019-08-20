<?php


namespace Morrios\Sms\Utils;


class Validate
{
    /**
     * required
     * @param $value
     * @return bool
     */
    public static function required($value)
    {
        return isset($value) && (is_string($value) ? trim($value) : true);
    }

    /**
     * string
     * @param $value
     * @return bool
     */
    public static function string($value)
    {
        return is_string($value);
    }

    /**
     * array
     * @param $value
     * @return bool
     */
    public static function array($value)
    {
        return is_array($value);
    }

    /**
     * phone
     * @param $value
     * @return bool
     */
    public static function phone($value)
    {
        return preg_match('/^[1]([3-9])[0-9]{9}$/', $value) ? true : false;
    }

    /**
     * phones
     * @param $value
     * @return bool
     */
    public static function phones($value)
    {
        foreach ($value as $phone) {
            if (!self::phone($phone)) return false;
        }

        return true;
    }

    /**
     * max
     * @param $value
     * @param $length
     * @return bool
     */
    public static function max($value, $length)
    {
        if (self::string($value)) {
            return strlen($value) <= $length;
        } else {
            return count($value) <= $length;
        }
    }

    /**
     * max
     * @param $value
     * @param $length
     * @return bool
     */
    public static function min($value, $length)
    {
        if (self::string($value)) {
            return strlen($value) >= $length;
        } else {
            return count($value) >= $length;
        }
    }

    /**
     * config
     * @param $config
     * @return bool
     */
    public static function config($config)
    {
        // required fields
        foreach (['accessKeyId', 'accessSecret', 'signName'] as $key) {
            if (!isset($config[$key])) return false;
            if (!self::required($config[$key])) return false;
        }

        return true;
    }
}