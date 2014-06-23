<?php

/**
 * JSConfig
 * For interfacing with any frontend by sending data via JS in to a global JS object called JSConfig.
 * We use json_encode with JSON_FORCE_OBJECT instead of the Convert class to force empty data arrays to be objects.
 */
class JSConfig {

	/**
	 * @var boolean
	 */
	static protected $has_inserted;

	/**
	 * @var array
	 */
	static protected $data = array();

	/**
	 * Add a datum to the data array under the name of $key.
	 * If we have already inserted, then directly insert to the DOM.
	 * @param string $key
	 * @param mixed $data
	 * @return void
	 */
	static public function add($key, $data) {
		self::$data[$key] = $data;
		if(self::$has_inserted) {
			Requirements::insertHeadTags('<script charset="utf-8">var JSCONFIG = JSCONFIG || {}; JSCONFIG[\''.$key.'\'] = ' . json_encode($data, JSON_FORCE_OBJECT) .';</script>');
		}
	}

	/**
	 * Clear a key from the data
	 * @param string key
	 * @return void
	 */
	static public function clear($key) {
		unset(self::$data[$key]);
	}

	/**
	 * Insert the data to pass to JS in to the DOM
	 * @return void
	 */
	static public function insert() {
		if(!self::$has_inserted) {
			self::$has_inserted = true;
			Requirements::insertHeadTags(self::get_script_tag());
		}
	}

	/**
	 * @return string
	 */
	static protected function get_script_tag() {
		return '<script charset="utf-8">var JSCONFIG = JSCONFIG || {}; JSCONFIG = ' . json_encode(self::$data, JSON_FORCE_OBJECT) .';</script>';
	}

}
