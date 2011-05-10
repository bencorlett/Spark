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
<?=$grid->get_assets()?>
<div class="buttons" style="margin: 0 0 10px; text-align: right;">
	<?php if (($button = $grid->get_add_button()) != null): ?>
		<?=\Html::anchor($button->get_action(), $button->get_label(), array('class' => 'small button'))?>
	<?php endif ?>
</div>
<div class="grid" id="grid-<?=$grid?>">
	<?=(isset($table)) ? $table : false?>
</div>
<?=\Form::open(array('id' => sprintf('grid-%s-massactions-form', $grid), 'style' => 'display: none;'))?>
<?=\Form::close()?>
<?=\Form::open(array('id' => sprintf('grid-%s-ajax-form', $grid), 'style' => 'display: none;'))?>
<?=\Form::close()?>