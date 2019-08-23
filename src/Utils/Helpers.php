<?php


namespace Morrios\Sms\Utils;


use Morrios\Sms\Exception\ClientException;

trait Helpers
{
    /**
     * Validate params.
     *
     * @param array $needKeys
     * @param array $params
     * @throws ClientException
     */
    private function _validate(array $needKeys, array $params)
    {
        // Verify the number of parameters
        if (count($needKeys) !== count($params)) throw new ClientException('invalid params number', 500);

        // Conversion parameter
        $params = array_combine(array_keys($needKeys), $params);

        // Loop validation rule
        array_walk($needKeys, function ($item, $key) use ($params) {

            // Handling multiple validation rules
            foreach (explode('|', $item) as $rule) {

                // Compatible with multiple verification conditions
                if (strstr($rule, ':') !== false) {
                    list($method, $extParam) = explode(':', $rule);
                } else {
                    list($method, $extParam) = [$rule, false];
                }

                // Call verification implementation
                if (method_exists(Validate::class, $method)) {
                    if (!Validate::$method($params[$key], $extParam)) throw new ClientException('invalid params ' . $key, 500);
                }
            }

        });
    }
}