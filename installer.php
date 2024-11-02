<?php

include_once 'Block.php';
include_once '../../../wp-config.php';

$query="CREATE TABLE IF NOT EXISTS `".Wordpress_Plugin_BlockRules::DATABASE_TABLE."` (
  `id` int(11) NOT NULL auto_increment,
  `rule_identifier` varchar(255) NOT NULL,
  `block_identifier` varchar(255) NOT NULL,
  `parameter` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;";

$link   = mysql_connect( DB_HOST, DB_USER, DB_PASSWORD );
mysql_select_db(DB_NAME, $link);
$result = mysql_query( $query );

if ( $result != 0 ) die( 'Database ("'.Wordpress_Plugin_BlockRules::DATABASE_TABLE.'"") has been created. You can now activate Block Rules.' ); # show error, unformatted

?>