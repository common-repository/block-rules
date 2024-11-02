<?php

class Wordpress_Plugin_BlockRules_Rule_General_ValidScope extends Wordpress_Plugin_BlockRules_aRule 
{
	protected static $name        = 'General: Valid Scope';
	protected static $description = 'This rule shows a block if a certain scope is active.';
	protected static $identifier  = 'ValidScope';
	
	protected $parameter = array( 
							'scope' => array(
												'type' => 'string', 
												'name' => 'Valid Scope',
												'description' => 'This parameter defines the valid scope.'
										      ) 
	                       );
	
	protected $scopes = array( 
							Wordpress_Plugin_BlockRules::SCOPE_POSTING, 
							Wordpress_Plugin_BlockRules::SCOPE_HOME,
							Wordpress_Plugin_BlockRules::SCOPE_ARCHIVE,
							Wordpress_Plugin_BlockRules::SCOPE_PAGE,
							Wordpress_Plugin_BlockRules::SCOPE_AUTHOR,
							Wordpress_Plugin_BlockRules::SCOPE_SEARCH,
							Wordpress_Plugin_BlockRules::SCOPE_CATEGORY
						);
	
	protected function checkBlockVisibility( $blockIdentifier )
	{
		$parameter = $this->observedBlocks[$blockIdentifier]->getParameter( );		
		$scope = $parameter['scope'];		
		return ( $scope == Wordpress_Plugin_BlockRules::getScope( ) );
	}

	public static function getName( )
	{
		return self::$name;
	}
	
	public static function getDescription( )
	{
		return self::$description;
	}
	
	public static function getIdentifier( )
	{
		return self::$identifier;
	}

	public static function isDescriptionValid( Wordpress_Plugin_BlockRules_BlockRuleDefinition $blockDefinition )
	{
		$parameter   = $blockDefinition->getParameter( );
		$scope       = $parameter['scope'];
		$validScopes = Wordpress_Plugin_BlockRules::getScopes( );
		
		if ( !in_array( $scope, $validScopes ) ) {
			$errorMsg = 'The given scope does not exist. <br /> Please select one of the following scopes: ';
			$errorMsg .= implode( ', ', $validScopes );
			return new ValidationResult( false, $errorMsg );			
		}
		return new ValidationResult( true );
	}
}

?>