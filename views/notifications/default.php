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
 * @link       http://www.github.com/bencorlett/spark
 */
namespace Spark;
?>
<?php $i = 0 ?>
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
	});
}, jQuery);
</script>
<ul class="notifications" id="notifications">
	<?php foreach ($notifications as $group => $list): ?>
		<?php foreach ($list as $notification): ?>
			<li class="<?=$group?> <?=(++$i == $count) ? 'last' : null?>">
				<?=$notification?>
				<a href="#" class="close">Close</a>
			</li>
		<?php endforeach ?>
	<?php endforeach ?>
</ul>