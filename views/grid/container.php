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
<style>

body { background: #f8f8f8; margin: 0; font: 14px/1.55 HelveticaNeue-Light, 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #3b3f49; }
grid { display: block; }

grid > table { border-collapse: collapse; }
grid > table th,
grid > table td { border: 1px solid #3b3f49; padding: 6px; }

</style>
<script>
$(document).ready(function()
{
	// When the user is entering a filter and presses enter
	$("#grid-<?=$grid?> > table > thead > tr.filters > th > input.filter").keypress(function(e)
	{
		switch (e.keyCode)
		{
			case 13:
				// Loop through filters and create
				// Form based on their name / value
				$("#grid-<?=$grid?> > table > thead > tr.filters > th > input.filter").each(function()
				{
					$("#grid-<?=$grid?>-form").append('<input type="hidden" name="' + $(this).attr('name') + '" value="' + $(this).val() + '" />');
				});
				
				$("#grid-<?=$grid?>-form").submit();
		}
	});
	
	// When the user clicks on a column header
	$("#grid-<?=$grid?> > table > thead > tr.labels > th > span").click(function()
	{
		$("#grid-<?=$grid?>-form").append('<input type="hidden" name="grid[<?=$grid?>][sort]" value="' + $(this).attr('column') + '" />');
		
		$("#grid-<?=$grid?>-form").submit();
	});
});
</script>
<grid id="grid-<?=$grid?>">
	<?=(isset($table)) ? $table : false?>
</grid>