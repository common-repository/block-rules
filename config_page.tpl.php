<style>
.phm_tb_parameter {
	font-size: 10px;
	float: left;
	font-weight: bold;
	padding-right: 10px;
	padding-left: 10px;
	width: 80px;
}
</style>

<script type="text/javascript">
	ruleDescriptions = new Object;
	<?php foreach( $blockRules_block->getRules( ) as $rule ) { /* @var $rule Wordpress_Plugin_BlockRules_iRule */ ?>
		ruleDescriptions["<?= $rule->getIdentifier( ); ?>"] = new Object;
		ruleDescriptions["<?= $rule->getIdentifier( ); ?>"]['description'] = "<?= $rule->getDescription( ); ?>";
		ruleDescriptions["<?= $rule->getIdentifier( ); ?>"]['parameter'] = new Array( );
		<? foreach ( $rule->getParameterDefinitions( ) as $key => $parameter ) { ?>
			nextIndex = ruleDescriptions["<?= $rule->getIdentifier( ); ?>"]['parameter'].length;
			ruleDescriptions["<?= $rule->getIdentifier( ); ?>"]['parameter'][nextIndex] = new Object;
			ruleDescriptions["<?= $rule->getIdentifier( ); ?>"]['parameter'][nextIndex]['id'] = '<?= htmlentities( $key ) ?>';
			ruleDescriptions["<?= $rule->getIdentifier( ); ?>"]['parameter'][nextIndex]['type'] = '<?= htmlentities( $parameter['type'] ) ?>';
			ruleDescriptions["<?= $rule->getIdentifier( ); ?>"]['parameter'][nextIndex]['name'] = '<?= htmlentities( $parameter['name'] ) ?>';
			ruleDescriptions["<?= $rule->getIdentifier( ); ?>"]['parameter'][nextIndex]['description'] = '<?= htmlentities( $parameter['description'] ) ?>';
		<?php } ?>
	<?php } ?>

	function updateRuleInformation( ruleIdentifier )
	{
		if ( ruleIdentifier == '' ) {
			document.getElementById( 'rule_information' ).innerHTML = "";
			document.getElementById( 'rule_parameter' ).innerHTML   = "";
			return;
		}
		document.getElementById( 'rule_information' ).innerHTML = ruleDescriptions[ruleIdentifier]['description'];
		
		parameter = ruleDescriptions[ruleIdentifier]['parameter'];
		for( i = 0; i < parameter.length; i ++ ) {
			parameterInnerHtml = '<div class="phm_tb_parameter">' + parameter[i]['name'] + '</div>'; 
			parameterInnerHtml = parameterInnerHtml + '<div class="phm_tb_parameterInput"><input type="text" name="rule[parameter][' + parameter[i]['id'] + ']"></div>';
			parameterInnerHtml = parameterInnerHtml + '<div class="phm_tb_parameterDescription">' + parameter[i]['description'] + '</div>';
		}
		
		document.getElementById( 'rule_parameter' ).innerHTML = parameterInnerHtml; 
	}
</script>

<div style="padding: 20px;">
	<a href="http://www.phphatesme.com" target="_blank">php hates me</a> - but that's ok
	<h1 style="margin-top:3px;">
		Block Rules
	</h1>
	<div style="padding-bottom: 30px; width:900px; text-align: justify">
		Using the BlockRules plugin you are able to assign a certain rule to a userdefined template block. This 
		plugin was written with the idea in mind to add a adsense block only if a given posting is older than seven 
		days. 
		To add a new rule to the system just enter a name that indentifies the block you want to manipulate, select 
		a rule and fill in the needed parameters. For more information please have a look at the 
		<a href="http://www.phphatesme.com/block-rules/" target="_blank">official plugin page</a>. If you
		are a php programmer please have a look at the rules how to, as it is very easy to extend the system with
		new rules.
	</div>
	<?php if ( count ( $blockRules_errors ) > 0 ) { ?>
	<div class="error" style="width: 868px">
		Sorry the rule could not be stored because of the following problems:
		<?php foreach ( $blockRules_errors as $error ) { ?>
			<li style="margin-left: 20px;"><?= $error ?></li>
		<?php } ?>
	</div>
	<?php } ?>
	
	<div style="clear: both">
		<div style="width: 900px">
			<form method="post">
				<div class="tablenav" style="margin-bottom: 10px;">
					<div style="float: left; padding-right:20px;">
						Block Identifier
						<input type="text" style="margin-right: 30px; margin-left:10px;" name="rule[blockIdentifier]" />	
						Rule
						<select onChange="updateRuleInformation( this.value )" id="rule" name="rule[id]" style="margin-left:10px;">
							<option value="">&lt;&lt;Please choose a rule to add&gt;&gt;</option>  
						<?php foreach( $blockRules_block->getRules( ) as $rule ) { /* @var $rule Wordpress_Plugin_BlockRules_iRule */ ?>
							<option value="<?= htmlentities ( $rule->getIdentifier( ) ); ?>"><?= htmlentities( $rule->getName( ) ); ?></option>
						<?php } ?> 
						</select> 
						<input value="Add Rule" name="AddRule" class="button-secondary" type="submit" style="margin-left:205px;">
						<div id="rule_information" style="width: 230px; padding-left: 360px; padding-top: 10px; padding-bottom:20px; font-size: 10px; float:left;">
						</div>					
						<div id="rule_parameter" style="padding-top: 10px; padding-bottom:20px; font-size: 10px;">						
						</div>
					</div>
				</div>
			</form>
			<form method="post">
				<table class="widefat" >
					<thead>
						<tr>
							<th scope="col"></th>
							<th scope="col">Block Identifier</th>			
							<th scope="col">Rule</th>
							<th scope="col">Parameter</th>
						</tr>
					</thead>
					<tbody>					
						<?php foreach( $blockRules_block->getRules( ) as $rule ) { /* @var $rule Wordpress_Plugin_BlockRules_iRule */			
								foreach( $rule->getObservedBlocks( ) as $observedBlock ) { $i++;  /* @var $observedBlock Wordpress_Plugin_BlockRules_BlockRuleDefinition */ ?> 										      
									<tr class='<?php if ( $i % 2 == 0) { ?>alternate<?php } ?>'>
										<td width="1px"><input type="checkbox" name="ruleSelector[<?= htmlentities( $observedBlock->getId( ) ) ?>]"></td>
										<td valign="top" width="150px"><?= htmlentities( $observedBlock->getBlockIdentifier( ) ) ?></td>
										<td valign="top" width="150px;"><?= htmlentities( $rule->getName( ) ) ?></td>
										<td valign="top">Parameter						
										<?php foreach( $observedBlock->getParameter( ) as $key => $parameter ) { ?>
											<div style="float:left; width: 120px;"><?= htmlentities( $rule->getParameterName( $key ) ) ?></div><div><strong><?= htmlentities( $parameter ) ?></strong></div> 				 
										<?php } ?>
										</td>
									</tr>			
								<?php } ?> 
						<?php } ?> 
					</tbody>				
				</table>
				<input value="<?= _e('Delete'); ?>" name="Delete" class="button-secondary" type="submit" style=margin-top:20px;">
			</form>
		</div>
	</div>
</div>