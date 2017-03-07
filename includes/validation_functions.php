<?php
	//stores the errors in an array
	//the array is empty because no errors exist yet
	$errors=array();
	
	//displays a field as the correct formatted text
	function field_name_as_text($fieldname){
		//replaces all underscore with a space
		$fieldname=str_replace("_", " ", $fieldname);
		//converts the first letter of the string to upper case
		$fieldname =ucfirst($fieldname);
		//returns the formatted field name
		return $fieldname;
	}
	//* presence
	//use trim() so empty spaces dont count
	//use === to avoid false positives
	//empty would consider "0" to be empty
	function has_presence($value){
		//makes sure the current field is set and is not blank
		//returns a true or false value
		return isset($value) && $value !== "";
	}
	
	//validates that a field has presence
	function validate_presences($required_fields){
		//import errors from the global scope
		global $errors;
		
		//each field is evaluated for presence, if blank an error message is blank
		foreach ($required_fields as $field){
			//trims all spaces
			$value =trim($_POST[$field]);
			//check for presence
			if (!has_presence($value)){
				//if no presence is detected, generate error
				$errors[$field]=field_name_as_text($field). " can't be blank";
			}
		}
	}
	
	//checks that the string length does not exceed the stated length
	//takes in a max length parameter to detect what length it should be
	function has_max_length($value,$max){
		return strlen($value)<=$max;
	}
	
	//validates that the field is of the correct max length
	function validate_max_lengths($fields_with_max_lengths){
		//imports the errors array from the global scope
		global $errors;
		//Expects an assoc array
		foreach($fields_with_max_lengths as $field =>$max){
			//trim spaces
			$value = trim($_POST[$field]);
			//validate the length
			if(!has_max_length($value,$max)){
				//generate error message if too long
				$errors[$field] = field_name_as_text($field)." is too long";
			}
		}
	}
	
	//inclusion in a set
	function has_inclusion_in($value,$set){
		return in_array($value,$set);
	}
	
?>