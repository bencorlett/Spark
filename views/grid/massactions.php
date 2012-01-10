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
<?php echo \Form::button(null, 'Select All', array(
	'type'  => 'button',
	'class' => 'massactions-select-button all',
)); ?>

<?php echo \Form::button(null, 'Deselect All', array(
	'type'  => 'button',
	'class' => 'massactions-select-button none',
)); ?>

With Selected:
<?php echo \Form::select(null, null, $options, array(
	'class' => 'massactions-select',
)); ?>

<?php echo \Form::button(null, 'Submit', array(
	'type'  => 'button',
	'class' => 'massactions-submit',
)); ?>

<?php echo \Form::open(array(
	'class' => 'massactions-form',
	'style' => 'display: none;',
)); ?>
<?php echo \Form::close(); ?>