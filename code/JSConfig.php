<?php

/**
 * JSConfig
 * For interfacing with any frontend by sending data via JS in to a global JS object called JSConfig.
 * We use json_encode with JSON_FORCE_OBJECT instead of the Convert class to force empty data arrays to be objects.
 */
class JSConfig
{

    /**
     * @var boolean
     */
    protected static $has_inserted;

    /**
     * @var array
     */
    protected static $data = array();

    /**
     * Add a datum to the data array under the name of $key.
     * If we have already inserted, then directly insert to the DOM.
     * @param string $key
     * @param mixed $data
     * @param bool $forceObject
     * @return void
     */
    public static function add($key, $datai, $forceObject = true)
    {
        self::$data[$key] = $data;
        if (self::$has_inserted) {
		if ($forceObject) {
			$json = json_encode($data, JSON_FORCE_OBJECT);
		} else {
			$json = json_encode($data);
		}
		Requirements::insertHeadTags('<script charset="utf-8">var JSCONFIG = JSCONFIG || {}; JSCONFIG[\''.$key.'\'] = ' . $json .';</script>');
	}
    }

    /**
     * Clear a key from the data
     * @param string key
     * @return void
     */
    public static function clear($key)
    {
        unset(self::$data[$key]);
        if (self::$has_inserted) {
            Requirements::insertHeadTags('<script charset="utf-8">var JSCONFIG = JSCONFIG || {}; JSCONFIG[\''.$key.'\'] = undefined;</script>');
        }
    }

    /**
     * Insert the data to pass to JS in to the DOM
     * @return void
     */
    public static function insert()
    {
        if (!self::$has_inserted) {
            self::$has_inserted = true;
            Requirements::insertHeadTags(self::get_script_tag());
        }
    }

    /**
     * @return string
     */
    protected static function get_script_tag()
    {
        $script = '<script charset="utf-8">var JSCONFIG = JSCONFIG || {};';
        foreach (self::$data as $key => $value) {
            $script .= "JSCONFIG['$key'] = " . json_encode($value, JSON_FORCE_OBJECT) . ';';
        }
        $script .= '</script>';
        return $script;
    }
}
