<?php

include_once 'ValidationResult.php';

interface Wordpress_Plugin_BlockRules_iRule
{
	/**
	 * This static function returns the name of the rule
	 * @return string 
	 */
	public static function getName( );
	
	/**
	 * This static function returns the description of the rule
	 * @return string
	 */
	public static function getDescription( );
		
	/**
	 * This static function returns the unique identifier of this rule
	 * @return string  
	 */
	public static function getIdentifier( );
	
	/**
	 * This static function returns true if the given parameters, specified by the given BlockDefinitionRule,
	 * are valid for this rule
	 *
	 * @param Wordpress_Plugin_BlockRules_BlockRuleDefinition $blockDefinition
	 * @return ValidationResult
	 */
	public static function isDescriptionValid( Wordpress_Plugin_BlockRules_BlockRuleDefinition $blockDefinition );
	
	/**
	 * This function returns true if the rule is valid in the given scope
	 *
	 * @param string $scope
	 * @return bool
	 */
	public function isValidScope( $scope );
	
	/**
	 * This function returns true if the given block, that is specified by a block identifier
	 * is valid
	 *
	 * @param string $blockIdentifier
	 * @return bool
	 */
	public function isBlockVisible( $blockIdentifier );
	
	/**
	 * This function returns an array with the definition of all parameters of this rule
	 * 
	 * @return array
	 */
	public function getParameterDefinitions( );
	
	/**
	 * This function add a block to the observed blocks
	 *
	 * @param Wordpress_Plugin_BlockRules_BlockRuleDefinition $blockDefinition
	 */
	public function observeBlock( Wordpress_Plugin_BlockRules_BlockRuleDefinition $blockDefinition );

	/**
	 * This function returns the parameter name based on the given identifier
	 *
	 * @param string $parameterIdentifier
	 */
	public function getParameterName( $parameterIdentifier );
	
	/**
	 * This function returns an array containing all registred block
	 * 
	 * @return Wordpress_Plugin_BlockRules_BlockRuleDefinition[]
	 */
	public function getObservedBlocks( );
}

?>