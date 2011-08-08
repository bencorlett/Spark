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
<div class="range">
	<div class="line">
		<?=\Form::label('From', \Str::f('grid-%s-filter-%s-min', $filter->get_grid()->get_identifier(), $filter->get_column()->get_identifier()))?>
		<?=\Form::input($filter->get_column()->get_identifier() . '[min]',
						$filter->get_user_value() ? $filter->get_user_value()->get_min() : null,
						array(
							'class'	=> 'filter',
							'id'	=> \Str::f('grid-%s-filter-%s-min', $filter->get_grid()->get_identifier(), $filter->get_column()->get_identifier()),
						)
		)?>
	</div>
	<div class="line">
		<?=\Form::label('To', \Str::f('grid-%s-filter-%s-max', $filter->get_grid()->get_identifier(), $filter->get_column()->get_identifier()))?>
		<?=\Form::input($filter->get_column()->get_identifier() . '[max]',
						$filter->get_user_value() ? $filter->get_user_value()->get_max() : null,
						array(
							'class'	=> 'filter',
							'id'	=> \Str::f('grid-%s-filter-%s-max', $filter->get_grid()->get_identifier(), $filter->get_column()->get_identifier()),
						)
		)?>
	</div>
</div>