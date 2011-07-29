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
<script>
$(function()
{
	$("div.tabs > ul > li").click(function()
	{
		$("div.tabs > ul > li").removeClass('active');
		$(this).addClass('active');
		
		var target_id = $(this).attr('target-id');
		
		$("div.tabs > div.inner").hide(0, function()
		{
			$("div#" + target_id).show();
		})
	});
});
</script>
<div class="tabs">
	<ul>
		<?php $i = 0 ?>
		<?php foreach ($tabs as $tab): ?>
			<li class="<?=$tab?><?=$i++ == 0 ? ' active' : null?>" target-id="<?=$tab->get_css_id()?>">
				<?=$tab->get_label()?>
			</li>
		<?php endforeach ?>
	</ul>
	
	<?php $i = 0 ?>
	<?php foreach ($tabs as $tab): ?>
		<div class="inner" id="<?=$tab->get_css_id()?>" style="display: <?=$i++ == 0 ? 'block' : 'none'?>; min-height:<?=count($tabs) * 37?>px;">
			<?=$tab->get_content()?>
		</div>
	<?php endforeach ?>
</div>