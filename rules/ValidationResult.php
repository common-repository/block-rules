<?php

	class ValidationResult
	{
		private $isValid;
		private $validationError;
		
		/**
		 * Ths constructor is used to define whether the validation has succeeded or not. If the validation
		 * failed the second paramater defines the error message.
		 *
		 * @param bool $isValid
		 * @param string $validationError
		 */
		public function __construct( $isValid, $validationError = null )
		{
			$this->isValid         = $isValid;
			$this->validationError = $validationError;
		} 
		
		/**
		 * This function returns a string representing the error message if an error has occured. If no
		 * error occured this function returns an empty string.
		 *
		 * @return string
		 */
		public function getValidationError( )
		{
			return $this->validationError;	
		}
		
		/**
		 * This function returns true if no error has the validation succedded otherwise false
		 *
		 * @return boolean
		 */
		public function isValid( )
		{
			return $this->isValid;
		}
	}

?>