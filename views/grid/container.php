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
<?=\Asset::css('core.css')?>
<?=\Asset::css('buttons.css')?>
<?=\Asset::css('grid.css')?>
<?=\Asset::js('jquery-1.5.1.min.js')?>
<?=\Asset::js('jquery.form-2.69.js')?>
<?=\Asset::js('grid.js')?>
<grid id="grid-<?=$grid?>">
	<?=(isset($table)) ? $table : false?>
</grid>
<?=\Form::open(array('id' => sprintf('grid-%s-massactions-form', $grid), 'style' => 'display: none;'))?>
<?=\Form::close()?>
<?=\Form::open(array('id' => sprintf('grid-%s-ajax-form', $grid), 'style' => 'display: none;'))?>
<?=\Form::close()?>