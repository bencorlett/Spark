<?php
/**
 * Spark Fuel Package By Ben Corlett
 * 
 * Spark - Set your fuel on fire!
 * 
 * The Spark Fuel Package is an open-source
 * fuel package consisting of several 'widgets'
 * engineered to make developing
 * administration systems easier and quicker.
 * 
 * @package    Fuel
 * @subpackage Spark
 * @author     Ben Corlett (http://www.bencorlett.com)
 * @license    MIT License
 * @copyright  (c) 2011 Ben Corlett
 * @link       http://www.github.com/bencorlett/bc
 */

namespace Spark;
?>
<?=\Asset::js('jquery-1.5.1.min.js')?>
<?=\Asset::js('jquery.form-2.69.js')?>
<style>

body { background: #f8f8f8; margin: 0; font: 14px/1.55 HelveticaNeue-Light, 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #3b3f49; }
grid { display: block; }

table.full-width { width: 100%; }

grid > table { border-collapse: collapse; }
grid > table th,
grid > table td { border: 1px solid #3b3f49; padding: 6px; }

</style>
<grid id="grid-<?=$grid?>">
	<?=(isset($table)) ? $table : false?>
</grid>

<script>
$(document).ready(function()
{
	// Make the form an AJAX form
	$("#grid-<?=$grid?>-form").ajaxForm({
		target	: '#grid-<?=$grid?>',
		success	: function()
		{
			$("#grid-<?=$grid?>-form").empty();
		}
	});
});
</script>
<?=\Form::open(array('id' => sprintf('grid-%s-form', $grid)))?>
<?=\Form::close()?>