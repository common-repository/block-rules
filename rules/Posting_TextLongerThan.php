<?php

class Wordpress_Plugin_BlockRules_Rule_Posting_TextLongerThan extends Wordpress_Plugin_BlockRules_aRule 
{
	protected static $name        = 'Posting: Text longer than';
	protected static $description = 'This rule shows a block if a certain scope is active.';
	protected static $identifier  = 'PostingTextLongerThan';
	
	protected $parameter = array( 
							'length' => array(
												'type' => 'string', 
												'name' => 'Length (chars)',
												'description' => 'This parameter defines the length in characters the text must have before the block is visible'
										      ) 
	                       );
	
	protected $scopes = array( 
							Wordpress_Plugin_BlockRules::SCOPE_POSTING, 
							Wordpress_Plugin_BlockRules::SCOPE_HOME,
							Wordpress_Plugin_BlockRules::SCOPE_PAGE,
						);
	
	protected function getParameter( $blockIdentifier )
	{
		return $this->observedBlocks[$blockIdentifier]->getParameter( );						
	}

	protected function getTextLength( )
	{
		ob_start( );
		the_content( );
		$content = ob_get_contents( );
		ob_end_clean( );
		return strlen( $content );
	}
	
	protected function checkBlockVisibility( $blockIdentifier )
	{
		$parameter = $this->getParameter( $blockIdentifier );		
		$minLength = $parameter['length'];
		return ( $minLength < $this->getTextLength( ) );
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
		$parameter = $blockDefinition->getParameter( );
		$length = $parameter['length'];
		if ( !is_numeric( $length ) ) {
			return new ValidationResult( false, 'The given parameter is not a numeric value' );
		}
		return new ValidationResult( true );
	}
}

?>