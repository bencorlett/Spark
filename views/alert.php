<?php
/**
 * Spark Fuel Package By Ben Corlett
 * 
 * Spark - Set your fuel on fire!
 * 
 * The Spark Fuel Package is an open-source
 * fuel package consisting of several 'widgets'
 * engineered to make developing various
 * web-based systems easier and quicker.
 * 
 * @package    Fuel
 * @subpackage Spark
 * @version    1.0
 * @author     Ben Corlett (http://www.bencorlett.com)
 * @license    MIT License
 * @copyright  (c) 2011 Ben Corlett
 * @link       http://spark.bencorlett.com
 */
namespace Spark;

// Counter
$i = 0;
?>
<script>
$(function()
{
	$("ul#notifications > li > a.close").click(function()
	{
		// Slideup and remove the list item
		$(this).parent().slideUp(200, function()
		{
			$(this).remove();
		});
		
		// Don't slide to the top of the screen
		return false;
	});
}, jQuery);
</script>
<ul class="notifications" id="notifications">
	<?php foreach ($notifications as $group => $list): ?>
		<?php foreach ($list as $notification): ?>
			<li class="<?php echo $group; ?> <?php echo (++$i == $count) ? 'last' : null; ?>">
				<?php echo $notification; ?>
				<a href="#" class="close">Close</a>
			</li>
		<?php endforeach; ?>
	<?php endforeach; ?>
</ul>