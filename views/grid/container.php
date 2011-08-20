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
?>
<div class="grid-container" id="grid-<?php echo $container->get_grid()->get_identifier()?>-container">
	<?php if ($container->get_buttons()->count()): ?>
		<div class="buttons">
			<?php foreach ($container->get_buttons() as $button): ?>
				<?php echo $button; ?>
			<?php endforeach ?>
		</div>
	<?php endif ?>
	<?php echo isset($grid) ? $grid : null; ?>
</div>