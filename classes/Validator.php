<?php

class Validator {

	private $_passed = false,
			$_errors = [],
			$_db 	 = null;

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	public function check($source, $items = []) {
		// $item will be e.g. 'username' $rules would be the corresponding array
		foreach($items as $item => $rules) { 
			foreach($rules as $rule => $rule_value) {
				//echo "{$item} {$rule} must be {$rule_value} <br>";
				$value = $source[$item];
				$item = escape($item);

				if($rule === 'required' && empty($value)) {
					$this->addError("{$item} is required");

				} else if(!empty($value)) {
					switch ($rule) {
						case 'min':
							if(strlen($value) < $rule_value) {
								$this->addError("{$item} must have a minimum of {$rule_value} characters");
							}	
							break;
						case 'max':							
							if(strlen($value) > $rule_value) {
								$this->addError("{$item} must have a maximum of {$rule_value} characters");
							}	
							break;
						case 'matches':
							if($value != $source[$rule_value]) {
								$this->addError("{$rule_value} must match {$item}");
							}
							break;
						case 'unique':
							$check = $this->_db->get($rule_value, [$item,'=', $value]);
							if($check->count()) {
								$this->addError("{$item} already exists");
							}
							break;
						
					}
				}
			}
		}
		if(empty($this->_errors)) {
			$this->_passed = true;
		}
		return $this;
	}

	private function addError($error) {
		$this->_errors[] = $error;
	}

	public function errors() {
		return $this->_errors;
	}

	public function passed() {
		return $this->_passed;
	}
}