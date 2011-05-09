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
<?=\Asset::css('spark/buttons.css')?>
<?=\Asset::css('spark/grid/grid.css')?>
<?=\Asset::css('jquery.ui-1.8.11.css')?>
<?=\Asset::js('jquery-1.5.1.min.js')?>
<?=\Asset::js('jquery.ui-1.8.11.js')?>
<?=\Asset::js('spark/jquery.form-2.69.js')?>
<?=\Asset::js('spark/grid/grid.js')?>
<div class="buttons" style="margin: 0 0 10px; text-align: right;">
	<?php if (($button = $grid->get_add_button()) != null): ?>
		<?=\Html::anchor($button->get_action(), $button->get_label(), array('class' => 'small button'))?>
	<?php endif ?>
</div>
<grid id="grid-<?=$grid?>">
	<?=(isset($table)) ? $table : false?>
</grid>
<?=\Form::open(array('id' => sprintf('grid-%s-massactions-form', $grid), 'style' => 'display: none;'))?>
<?=\Form::close()?>
<?=\Form::open(array('id' => sprintf('grid-%s-ajax-form', $grid), 'style' => 'display: none;'))?>
<?=\Form::close()?>