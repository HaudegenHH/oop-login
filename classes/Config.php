<?php

class Config {
	public static function get($path = null) {
		// by setting the default to null we can make sure that a path is given to the fn
		if($path) {
			// define a variable config to define where the configs are coming from
			$config = $GLOBALS['config'];

			$path = explode('/', $path);

			foreach($path as $bit){
				if(isset($config[$bit])){
					// overwriting $config in 2nd loop 
					$config = $config[$bit];
				}
			}
			return $config;
		}
		
		return false;
	}
}