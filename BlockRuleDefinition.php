<?php

class Wordpress_Plugin_BlockRules_BlockRuleDefinition
{
	private $blockIdentifier;
	private $ruleIdentifier;
	private $parameter;
	private $id; 
	
	/**
	 * The construcotr is used to initialze the object with valid parameters. These parameters are
	 * used to define a complete rule that can be used in the templates.
	 *
	 * @param string $blockIdentifier
	 * @param string $ruleIdentifier
	 * @param array $parameter
	 * @param int $id
	 */
	public function __construct( $blockIdentifier, $ruleIdentifier, $parameter, $id = null )
	{
		$this->blockIdentifier = $blockIdentifier;
		$this->ruleIdentifier  = $ruleIdentifier;
		$this->parameter       = $parameter;
		$this->id              = $id;
	}
	
	/**
	 * This function returns a string respresenting the block this description is valid for
	 * 
	 * @return string
	 */
	public function getBlockIdentifier( )
	{
		return $this->blockIdentifier;
	}	
	
	/**
	 * This function returns a string respresenting the rule this description is valid for
	 * 
	 * @return string
	 */
	public function getRuleIdentifier( )
	{
		return $this->ruleIdentifier;
	}	
	
	/**
	 * This function returns the parameters this rule has stored
	 *
	 * @return array
	 */
	public function getParameter( )
	{
		return $this->parameter;
	}

	/**
	 * This function is used to return the unique id of this block rule
	 *
	 * @return int
	 */
	public function getId( )
	{
		return $this->id;
	}
	
	/**
	 * This function is used to set the database id of this rule definition. In most cases  
	 * this id is already set within the constructor
	 *
	 * @param int $id
	 */
	public function setId( $id )
	{
		$this->id = $id;
	}
}
	
?>