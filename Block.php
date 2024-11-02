<?php

include_once 'rules/iRule.php';
include_once 'rules/aRule.php';
include_once 'rules/OlderThanRule.php';
include_once 'rules/Posting_TextLongerThan.php';
include_once 'rules/General_ValidScopeRule.php';

include_once 'BlockRuleDefinition.php';

class Wordpress_Plugin_BlockRules
{
	const DATABASE_TABLE = 'phm_br_blockrules';
	
	const SCOPE_CATEGORY = 'category';
	const SCOPE_POSTING  = 'posting';
	const SCOPE_ARCHIVE  = 'archive';
	const SCOPE_AUTHOR   = 'author';
	const SCOPE_HOME     = 'home';
	const SCOPE_SEARCH   = 'search';
	const SCOPE_PAGE     = 'page';
	
	private static $scopes = array(
								self::SCOPE_CATEGORY,
								self::SCOPE_ARCHIVE,
								self::SCOPE_AUTHOR,
								self::SCOPE_HOME,
								self::SCOPE_SEARCH,
								self::SCOPE_PAGE
	 						 );
	
	/**
	 * @var Wordpress_Plugin_BlockRules_iRule[]
	 */
	private $rules;
	 						 
	/**
	 * This static function returns an array with all valid scopes
	 * 
	 * @return string[]
	 */
	public static function getScopes( )
	{
		return self::$scopes;
	}
	
	/**
	 * The constructor initializes all known rules and the corresponding block rules
	 */
	public function __construct( )
	{
		$rules = $this->loadRules( );

		foreach( $rules as $rule ) {
			$this->addRule( $rule );
		}
	}

	/**
	 * This function is used to return a list of known rules with all defined block rules attached
	 *
	 * @return Wordpress_Plugin_BlockRules_iRule[]
	 */
	private function loadRules( )
	{
		$rules      = $this->getRuleList( );
		$blockRules = $this->getBlockRuleList( );
		
		foreach ( $this->getBlockRuleList( ) as $blockRuleDefinition ) {
			/* @var $blockRuleDefinition Wordpress_Plugin_BlockRules_BlockRuleDefinition */
			$rules[$blockRuleDefinition->getRuleIdentifier( )]->observeBlock( $blockRuleDefinition );
		}
		
		return $rules;
	}
	
	/**
	 * This function returns a list of all block rule definitions that are stores in the database
	 *
	 * @todo Abstract this function so the plug in can not only be used for wordpress 
	 * @return Wordpress_Plugin_BlockRules_BlockRuleDefinition[]
	 */
	private function getBlockRuleList( )
	{
		global $wpdb;
		$blockRules = array( );
		
		$dbResult = $wpdb->get_results( 'SELECT * FROM '.self::DATABASE_TABLE , ARRAY_A );
		
		if ( is_array( $dbResult ) ) {
			foreach( $dbResult as $result ) {
				$blockRules[] = new Wordpress_Plugin_BlockRules_BlockRuleDefinition( $result['block_identifier'], $result['rule_identifier'], unserialize( $result['parameter'] ), $result['id'] );
			}
		}
		return $blockRules;
	}
	
	/**
	 * This function returns a list of all known rules. 
	 *
	 * @todo Improve rule handling. It should be possible to activate a new rule just by copying it into a given
	 *       directory  
	 * @return Wordpress_Plugin_BlockRules_iRule[]
	 */
	private function getRuleList( )
	{
		$rules = array( );
		
		$rules[Wordpress_Plugin_BlockRules_Rule_General_ValidScope::getIdentifier( )] = new Wordpress_Plugin_BlockRules_Rule_General_ValidScope;
		$rules[Wordpress_Plugin_BlockRules_Rule_OlderThan::getIdentifier( )]  = new Wordpress_Plugin_BlockRules_Rule_OlderThan; 
		$rules[Wordpress_Plugin_BlockRules_Rule_Posting_TextLongerThan::getIdentifier( )] = new Wordpress_Plugin_BlockRules_Rule_Posting_TextLongerThan;
		
		return $rules;
	}
	
	/**
	 * This function adds a given rule to the object
	 *
	 * @param Wordpress_Plugin_BlockRules_iRule $rule
	 */
	private function addRule( Wordpress_Plugin_BlockRules_iRule $rule )
	{
		$this->rules[$rule->getIdentifier()] = $rule;
	}
	
	/**
	 * This function returns a block definition based on the given id
	 *
	 * @throws Exception
	 * @param int $id
	 * @return Wordpress_Plugin_BlockRules_BlockRuleDefinition
	 */
	private function getBlockDefinition( $id )
	{
		$blockList = $this->getBlockRuleList( );
		foreach( $blockList as $blockDefinition ) {
			if ( $blockDefinition->getId( ) == $id ) {
				return $blockDefinition;
			}
		}
		
		throw new Exception( 'The given block ID was not found' );
	}
	
	/**
	 * This function removes given rules, specified by an array of ids, from the database as well as from this object
	 *
	 * @param int[] $blockRuleIds
	 */
	public function removeBlockRules( $blockRuleIds )
	{
		if ( is_array( $blockRuleIds ) ) {
			foreach( array_keys( $blockRuleIds ) as $blockRuleId ) {
				$blockDescription = $this->getBlockDefinition( $blockRuleId );
				$this->removeBlockRuleFromDatabase( $blockDescription );
				$this->rules[$blockDescription->getRuleIdentifier( )]->removeObservedBlock( $blockDescription );	
			}
		}
	}
	
	/**
	 * This function removes a given rule from the database.
	 *
	 * @todo Abstract this function so the plug in can not only be used for wordpress 
	 * @param Wordpress_Plugin_BlockRules_BlockRuleDefinition $blockDefinition
	 */
	private function removeBlockRuleFromDatabase( Wordpress_Plugin_BlockRules_BlockRuleDefinition $blockDefinition )
	{
		global $wpdb;
		$wpdb->query( 'DELETE FROM '.self::DATABASE_TABLE .' WHERE id = '. (int)$blockDefinition->getId( ) );
	}
	
	/**
	 * This function adds a new rule to a specific block and stores it in the database
	 *
	 * @param Wordpress_Plugin_BlockRules_BlockRuleDefinition $blockDefinition
	 * @return ValidationResult
	 */
	public function addBlockRule( Wordpress_Plugin_BlockRules_BlockRuleDefinition $blockDefinition )
	{		
		$rule = $this->rules[$blockDefinition->getRuleIdentifier( )];
		/* @var $rule Wordpress_Plugin_BlockRules_iRule */
		
		$validationResult = $rule->isDescriptionValid( $blockDefinition );
		if ( $validationResult->isValid( ) ) {
			$rule->observeBlock( $blockDefinition );
			$this->addBlockRuleToDatabase( $blockDefinition );
		}	
		return $validationResult;
	}
	
	/**
	 * This function stores a single block rules in the database
	 *
	 * @todo Abstract this function so the plug in can not only be used for wordpress 
	 * @param Wordpress_Plugin_BlockRules_BlockRuleDefinition $blockDefinition
	 */
	private function addBlockRuleToDatabase( Wordpress_Plugin_BlockRules_BlockRuleDefinition $blockDefinition )
	{
		global $wpdb;
		$data = array( 'rule_identifier'  => $blockDefinition->getRuleIdentifier( ),
					   'block_identifier' => $blockDefinition->getBlockIdentifier( ),
					   'parameter'        => serialize( $blockDefinition->getParameter( ) ) );
		$id = $wpdb->insert( self::DATABASE_TABLE, $data );
		
		$blockDefinition->setId( $id );	
	}
	
	/**
	 * This function returns an array of all registered rules.
	 *
	 * @return Wordpress_Plugin_BlockRules_iRule[]
	 */
	public function getRules( )
	{
		return $this->rules;
	}

	/**
	 * This static functions returns the scope wordpress is currently in. If you are for example on the home 
	 * page of the block the current scope that will be returned is self::SCOPE_HOME
	 *
	 * @return string
	 */
	public static function getScope( )
	{
		if ( is_category( ) ) {
			return self::SCOPE_CATEGORY;
		}
		if ( is_archive( ) ) {
			return self::SCOPE_ARCHIVE;
		}
		if ( is_author( ) ) {
			return self::SCOPE_AUTHOR;
		}
		if ( is_home( ) ) {
			return self::SCOPE_HOME;
		}
		if ( is_search( ) ) {
			return self::SCOPE_SEARCH;
		}
		if ( is_page( ) ) {
			return self::SCOPE_PAGE;
		}
		return self::SCOPE_POSTING;
	}
	
	/**
	 * This functions returns true if all rules that are related to the given block identifier return true. 
	 * If the given rule is not valid in the current scope it will be skipped.
	 *
	 * @param string $identifier
	 * @return bool
	 */
	public function isBlockVisible( $identifier )
	{
		$scope = self::getScope( );
		
		$visible = true;
		
		foreach( $this->rules as $rule ) {
			/* @var $rule Wordpress_Plugin_BlockRules_iRule */
			if ( $rule->isValidScope( $scope ) && !$rule->isBlockVisible( $identifier ) ) {
				return false;
			}
		}
		return true;
	}
}

?>