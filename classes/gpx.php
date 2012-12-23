<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2012 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace GPX;

class GPX
{

    protected static $_instance = false;
    protected static $_defaults;

    public static function forge(array $custom = array()) {

        $custom = ! is_array($custom) ? array() : $custom;
        static::$_defaults = \Arr::merge(static::$_defaults, $custom);

        static::$_instance = true;
    }

    /**
     * Init, config loading.
     */
    public static function _init()
    {
        \Config::load('gpx', true);
        static::$_defaults = \Config::get('gpx');
    }

    /**
     * Prevent instantiation
     */
    final private function __construct() {}

    public static function import($filename) {

        if (! static::$_instance) {
            static::forge();
        }

        return Importer::import($filename);
    }
}
