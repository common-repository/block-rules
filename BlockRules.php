<?php
/*
	Plugin Name: BlockRules
	Plugin URI: http://www.phphatesme.com/block-rules/
	Description: Using BlockRules you are able to attach some visibility rules to user defined blocks in your templates.
	Version: 1.0.0.1
	Author: Nils Langner
	Author URI: http://www.phphatesme.com/archives/author/nils
*/

// @todo installer
// @nth multi language support

include_once "Block.php";

function showBlock( $identifier )
{
	global $blockRules_block;
	/* @var $blockRules Wordpress_Plugin_BlockRules */
	return $blockRules_block->isBlockVisible( $identifier );
}

function blockRules_init( )
{
	add_action( 'admin_menu', 'blockRules_config_page' );
}

function blockRules_config_page( )
{
   if ( function_exists('add_submenu_page') ) {
	   add_submenu_page('plugins.php', 'BlockRules', 'BlockRules', 'manage_options', 'blockRules', 'blockRules_conf_page');
   }
}

function blockRules_conf_page( )
{
	global $blockRules_block;	
	global $blockRules_errors;
	include "config_page.tpl.php";
}

function blockRules_install( )
{
    global $wpdb;
    echo 'test';
    include(dirname(__FILE__).'/includes/installer.php');
}

register_activation_hook('BlockRules/BlockRules.php', 'blockRules_install' );

add_action('init', 'blockRules_init');
	
$blockRules_block = new Wordpress_Plugin_BlockRules( );

$blockRules_errors = array( );

if ( is_admin( ) ) {
	if ( isset( $_REQUEST['AddRule'] ) ) {		
		$rule = $_REQUEST['rule'];
		if ( $rule['blockIdentifier'] == '' ) {
			$blockRules_errors[] = 'No block identifier set';
		}else if ( $rule['id'] == '' ) {
			$blockRules_errors[] = 'No rule has been selected';
		}else{
			$result = $blockRules_block->addBlockRule( new Wordpress_Plugin_BlockRules_BlockRuleDefinition( 
								  				   		   $rule['blockIdentifier'],
													   	   $rule['id'],
													   	   $rule['parameter'] 
													   	  )
										     );
			if ( !$result->isValid( ) ) {
				$blockRules_errors[] = $result->getValidationError( );
			}
		}
	}
							   
	if ( isset( $_REQUEST['Delete'] ) ) {
		$blockRules_block->removeBlockRules( $_REQUEST['ruleSelector'] );					   
	}
}
?>