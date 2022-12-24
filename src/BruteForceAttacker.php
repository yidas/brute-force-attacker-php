<?php

namespace yidas;

/**
 * Brute Force Attacker
 * 
 * Generates string with all arrangement and gives the string to callback function to execute.
 * 
 * @author  Nick Tsai <myintaer@gmail.com>
 * @version 1.0.0
 */
class BruteForceAttacker
{
    /**
     * @var function Customized function for performing brute-force attack
     */
    private static $callback;

    /**
     * @var array Character map used to generate strings
     */
    private static $charMap = [];

    /**
     * @var string String for generating
     */
    private static $chars = '';

    /**
     * @var integer String length for skipping based on `skipCount` setting
     */
    private static $skipLength = 0;

    /**
     * @var integer Skip count of the `charMap` based on `skipLength`
     */
    private static $skipCount = 0;

    /**
     * @var integer Count
     */
    private static $count = 0;

    /**
     * @var boolean Found
     */
    private static $found = false;
    
    /**
     * @var array $charsRecorded to continue where it left of last time
     */
    private static $charsRecorded = [];

    /**
     * Run
     *
     * @param array $options
     * @return void
     */
    public static function run($options)
    {
        // Options
        $defaultOptions = [
            'length' => 1,
            'charMap' => array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9')),
            'callback' => function () {},
            'skipLength' => 0,
            'skipCount' => 0,
            'startsCount' => 0,
        ];
        $options = array_merge($defaultOptions, $options);

        // Config
        self::$charMap = $options['charMap'];
        self::$callback = $options['callback'];
        self::$skipLength = $options['skipLength'];
        self::$skipCount = $options['skipCount'];
        self::$count = $options['startsCount'];

        // Run
        $length = ($options['length'] >= 1) ?  intval(floor($options['length'])) : 1;
        self::recur($length);
    }


    public static function bootUp($charsSaved)
    {
        if (!empty($charsSaved)) {
            foreach (str_split($charsSaved) as $char) {
                self::$charsRecorded[] = $char;
            }
        }
    }

    /**
     * Recursive function
     *
     * @param integer $layer
     * @return void
     */
    private static function recur($length) {

        // Each charMap
        foreach (self::$charMap as $key => $value) {

            // Exit from loop if the value has been found.
            if(self::$found) {
                break;
            }

            if(!empty(self::$charsRecorded) && self::wasVerified($length, $key)) {
                continue;
            }

            // Skip mechanism
            if (self::$skipLength==$length && self::$skipCount > 0) {
                self::$skipCount--;
                continue;
            }

            // Assign char
            self::$chars[$length-1] = $value;

            if ($length <= 1) {

                // Counter
                self::$count ++;

                // Call user callback
                self::$found = call_user_func_array(self::$callback, [self::$chars, &self::$count]);
                self::$charsRecorded = [];

                if(self::$found) {
                    break;
                }

            } else {
                // Recur with reducing 1 length
                self::recur($length - 1);
            }
        }
    }

    private static function wasVerified($length, $key)
    {
        $data = self::$charsRecorded[$length - 1];
        if($length == 1 && $key <= array_search($data, self::$charMap))
            return true;
        if($key < array_search($data, self::$charMap))
            return true;
        return false;
    }
}
