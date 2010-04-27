<!-- s:<?= __FILE__ ?> -->
<script type="text/javascript">
/*<![CDATA[*/
/*]]>*/
</script>
<style type="text/css">
/*<![CDATA[*/
.wf-variable-form textarea { width: 90%; height: 8em; }
.wf-variable-form input.input-string { width: 90%; }
/*]]>*/
</style>
<h2>Variable Data</h2>
<?php echo $variable->cv_description ?>
<ul>
	<li>
		Id: <strong><?php echo $variable->cv_id ?></strong> <sup><small><a href="http://community.wikia.com/wiki/Special:WhereIsExtension?var=<?php echo $variable->cv_id ?>&val=2">Where is...</a></small></sup>
	</li>
	<li>
		Name: <strong><?php echo $variable->cv_name ?></strong> <sup><small>Manual:
		&nbsp;<a href="http://www.mediawiki.org/wiki/Manual:$<?php echo $variable->cv_name ?>" title='link to manual page at mediawiki.org'>MediaWiki</a>
		&nbsp;<a href="http://contractor.wikia-inc.com/wiki/Manual:$<?php echo $variable->cv_name ?>" title='link to manual page at contractor.wikia'>Wikia</a></small></sup>
	</li>
	<li>
		Type: <strong><?php echo $variable->cv_variable_type ?></strong>
	</li>
	<li>
		Access Level: <strong><?php echo $variable->cv_access_level ?></strong>
		(<strong><?php echo $accesslevels[ $variable->cv_access_level ] ?></strong>)
	</li>
	<li>
		<a href='#' id="wk-variable-change" onclick='javascript:$Factory.Variable.change(this, [ "wk-variable-select", 1]);return false;'>Click here to edit this variable</a>
	</li>
</ul>
<h2>
    Variable value
    <span style="font-size: small;">
        <!-- [<a id="wk-var-remove" title="Remove value" href="#">remove value</a>] -->
    </span>
</h2>
Current value:
<?php if( !isset( $variable->cv_value ) || is_null( $variable->cv_value ) ): ?>
    <strong><em>Value is not set</em></strong>
<?php else: ?>
    <pre><?php echo var_export( unserialize( $variable->cv_value ) ) ?></pre>
<?php endif ?>
<div>
<?php if( $variable->cv_access_level > 1 ): ?>
<form id="wf-variable-form" name="wf-variable-form" class="wf-variable-form">
	<input type="hidden" name="cityid" value="<?php echo $cityid ?>" />
	<input type="hidden" name="varCityid" value="<?php echo $variable->cv_city_id ?>" />
	<input type="hidden" name="varType" value="<?php echo $variable->cv_variable_type ?>" />
	<input type="hidden" name="varName" value="<?php echo $variable->cv_name ?>" />
	<input type="hidden" name="varId" value="<?php echo $variable->cv_variable_id ?>" />

<?php if( $variable->cv_variable_type === "boolean" ): ?>

	<select name="varValue" id="varValue">
	<?php   if( unserialize( $variable->cv_value === true ) ): ?>
		<option value="1" selected="selected">true</option>
		<option value="0">false</option>
	<?php   else: ?>
		<option value="1">true</option>
		<option value="0" selected="selected">false</option>
	<?php   endif ?>
	</select>

<?php elseif( $variable->cv_variable_type == "integer"): ?>

	<input type="text" name="varValue" id="varValue" value="<?php echo unserialize( $variable->cv_value ) ?>" size="40" maxlength="255" />

<?php elseif( $variable->cv_variable_type == "string"): ?>

	<textarea name="varValue" id="varValue"><?php if( isset( $variable->cv_value ) ) echo unserialize( $variable->cv_value ) ?></textarea><br />

<?php elseif ($variable->cv_variable_type == "array" && !empty($wgDevelEnvironment)): ?>

	<textarea name="varValue" id="varValue"><?php if( isset( $variable->cv_value ) ) echo var_export( unserialize( $variable->cv_value ), 1) ?></textarea><br />

<?php else: ?>

	 <textarea name="varValue" id="varValue"><?php if( isset( $variable->cv_value ) ) echo var_export( unserialize( $variable->cv_value ), 1) ?></textarea><br />

<?php endif ?>
	<input type="button" id="wk-submit" name="submit" value="Parse &amp; Save changes" onclick="YAHOO.Wiki.Factory.Variable.tagCheck();" />
	<input type="button" id="wk-submit-remove" name="remove-submit" value="Remove value" onclick="YAHOO.Wiki.Factory.Variable.tagCheck('remove');" />
	&nbsp;<span id="wf-variable-parse">&nbsp;</span>
	&nbsp;&nbsp;Apply change to all wikis by tag:
	<input type="text" name="tagName" id="tagName" value="" style="width: 100px;" />
	&nbsp;<span id="wf-tag-parse">&nbsp;</span>
</form>
<?php else: ?>
<em>read only</em>
<?php endif ?>
</div>

<?php if (!empty($related)): ?>
<h2>Related Variables</h2>
<div id="wf-related-variables">
<?php $form_id = 0; ?>
<?php foreach ($related as $rel_var): ?>
<?php $form_id++; ?>
<h3><?= $rel_var->cv_name ?></h3>
<?php echo $rel_var->cv_description ?>

<div>
<?php if( $rel_var->cv_access_level > 1 ): ?>
<form id="wf-variable-form-<?= $form_id ?>" name="wf-variable-form-<?= $form_id ?>" class="wf-variable-form">
	<input type="hidden" name="formId" value="<?= $form_id ?>" />
	<input type="hidden" name="cityid" value="<?php echo $cityid ?>" />
	<input type="hidden" name="varCityid" value="<?php echo $rel_var->cv_city_id ?>" />
	<input type="hidden" name="varType" value="<?php echo $rel_var->cv_variable_type ?>" />
	<input type="hidden" name="varName" value="<?php echo $rel_var->cv_name ?>" />
	<input type="hidden" name="varId" value="<?php echo $rel_var->cv_variable_id ?>" />

<?php if( $rel_var->cv_variable_type === "boolean" ): ?>

	<select name="varValue" id="varValue">
	<?php   if( unserialize( $rel_var->cv_value === true ) ): ?>
		<option value="1" selected="selected">true</option>
		<option value="0">false</option>
	<?php   else: ?>
		<option value="1">true</option>
		<option value="0" selected="selected">false</option>
	<?php   endif ?>
	</select>

<?php elseif( $rel_var->cv_variable_type == "integer"): ?>

	<input type="text" name="varValue" id="varValue" value="<?php echo unserialize( $rel_var->cv_value ) ?>" size="40" maxlength="255" />

<?php elseif( $rel_var->cv_variable_type == "string"): ?>

	<input type="text" name="varValue" id="varValue" value="<?php echo unserialize( $rel_var->cv_value ) ?>" size="160" class="input-string" />

<?php else: ?>

	 <textarea name="varValue" id="varValue"><?php if( isset( $rel_var->cv_value ) ) echo var_export( unserialize( $rel_var->cv_value ), 1) ?></textarea><br />

<?php endif ?>
	<input type="button" id="wk-submit" name="submit" value="Parse &amp; Save changes" onclick="YAHOO.Wiki.Factory.Variable.submit($(this).parent().attr('id'));" />
<!--	<input type="button" id="wk-submit-remove" name="remove-submit" value="Remove value" onclick="YAHOO.Wiki.Factory.Variable.remove_submit();" /> -->
	&nbsp;<span id="wf-variable-parse-<?= $form_id ?>">&nbsp;</span>
</form>
<?php else: ?>
<em>read only</em>
<?php endif ?>
</div>

<?php endforeach; ?>
</div>
<?php endif; ?>

<!-- e:<?= __FILE__ ?> -->
