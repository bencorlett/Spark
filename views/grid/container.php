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
<?php echo \View::factory('grid/assets'); ?>
<div class="buttons" style="margin: 0 0 10px; text-align: right;">
	<?php if (($button = $grid->get_add_button()) != null): ?>
		<?php echo \Html::anchor($button->get_action(), $button->get_label(), array('class' => 'small button')); ?>
	<?php endif; ?>
</div>
<div class="grid" id="grid-<?php echo $grid; ?>">
	<?php echo (isset($table)) ? $table : false; ?>
</div>
<?php echo \Form::open(array('id' => sprintf('grid-%s-massactions-form', $grid), 'style' => 'display: none;')); ?>
<?php echo \Form::close(); ?>
<?php echo \Form::open(array('id' => sprintf('grid-%s-ajax-form', $grid), 'style' => 'adisplay: none;')); ?>
<?php echo \Form::close(); ?>