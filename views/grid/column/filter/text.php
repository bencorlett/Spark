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
<?php \Form::input(sprintf('grid[%s][filters][%s][value]', $filter->get_column()->get_grid(), $filter->get_column()), ($filter->get_frontend_values()) ? $filter->get_frontend_values()->get_value() : null, array('class' => 'filter text')); ?>