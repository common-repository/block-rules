<?php

abstract class Wordpress_Plugin_BlockRules_aRule implements Wordpress_Plugin_BlockRules_iRule 
{
	protected static $name;
	protected static $description;
	protected static $identifier;
	protected $scopes = array( );
	
	protected $observedBlocks = array( );
	
	protected $parameter;
	
	/**
	 * @see Wordpress_Plugin_BlockRules_iRule
	 */
	public function isBlockVisible( $blockIdentifier )
	{
		if ( $this->isBlockObserved( $blockIdentifier ) ) {
			return $this->checkBlockVisibility( $blockIdentifier );
		}
		return true;
	}
	
	/**
	 * @see Wordpress_Plugin_BlockRules_iRule
	 */
	public function getParameterName( $parameterIdentifier ) 
	{
		return $this->parameter[$parameterIdentifier]['name'];
	}
	
	abstract protected function checkBlockVisibility( $blockIdentifier );
	
	/**
	 * @see Wordpress_Plugin_BlockRules_iRule
	 */
	public function getParameterDefinitions( )
	{
		return $this->parameter;
	}
	
	/**
	 * @see Wordpress_Plugin_BlockRules_iRule
	 */
	public function observeBlock( Wordpress_Plugin_BlockRules_BlockRuleDefinition $blockRuleDefinition )
	{
		$this->observedBlocks[$blockRuleDefinition->getBlockIdentifier( )] = $blockRuleDefinition;
	}
	
	/**
	 * @see Wordpress_Plugin_BlockRules_iRule
	 */
	public function removeObservedBlock( Wordpress_Plugin_BlockRules_BlockRuleDefinition $blockRuleDefinition )
	{
		unset ( $this->observedBlocks[$blockRuleDefinition->getBlockIdentifier( )] );
	}
		
	/**
	 * This function returns true if the block, specified by the given identifier, is observed by this rule
	 *
	 * @param string $blockIdentifier
	 * @return bool
	 */
	protected function isBlockObserved( $blockIdentifier )
	{
		return array_key_exists( $blockIdentifier, $this->observedBlocks );
	}
	
	/**
	 * @see Wordpress_Plugin_BlockRules_iRule
	 */
	public function isValidScope( $scope )
	{
		return in_array( $scope, $this->scopes );
	}

	/**
	 * @see Wordpress_Plugin_BlockRules_iRule
	 */
	public function getObservedBlocks( )
	{
		return $this->observedBlocks;
	}
}

?>