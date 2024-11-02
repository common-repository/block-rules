<?php

class Wordpress_Plugin_BlockRules_Rule_OlderThan extends Wordpress_Plugin_BlockRules_aRule 
{
	protected static $name        = 'Posting: Older than';
	protected static $description = 'This rule shows a block if the given posting is older than a given number of days.';
	protected static $identifier  = 'OlderThan';
	
	protected $parameter = array( 
							'time_in_days' => array(
												'type' => 'int', 
												'name' => 'Time in Days',
												'description' => 'This value defines how old (in days) a posting must be, before the block is visible'
										      ) 
	                       );
	
	protected $scopes = array( 
							Wordpress_Plugin_BlockRules::SCOPE_POSTING, 
							Wordpress_Plugin_BlockRules::SCOPE_HOME 
						);
	
	private function getPostingsPublishDate( )
	{
		return apply_filters('the_time', get_the_time( 'Y-m-d' ), 'Y-m-d' );
	}
	
	protected function checkBlockVisibility( $blockIdentifier )
	{
		$parameter = $this->observedBlocks[$blockIdentifier]->getParameter();
		$timeInDays = $parameter['time_in_days'];
		 
		$publishDate = $this->getPostingsPublishDate( );
		 
		$startdate = date('Y-m-d', mktime( 0, 0, 0, date( 'm' ), date( 'd' )-$timeInDays, date( 'Y' ) ) );
	 	return $startdate > $publishDate;
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
		$timeInDays = $parameter['time_in_days'];
		if ( !is_numeric( $timeInDays ) ) {
			return new ValidationResult( false, 'The given time is not a numeric value' );
		}
		return new ValidationResult( true );
	}
}

?>