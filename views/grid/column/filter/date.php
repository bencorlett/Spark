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
<?php

$from_id	= sprintf('grid-%s-filters-%s-from',  $filter->get_column()->get_grid(), $filter->get_column());
$to_id		= sprintf('grid-%s-filters-%s-to',  $filter->get_column()->get_grid(), $filter->get_column());

?>
<script>
$(document).ready(function()
{
	$("#<?php echo $from_id; ?>").datepicker({
		dateFormat	: 'dd/mm/yy'
	});
	
	$("#<?php echo $to_id; ?>").datepicker({
		dateFormat	: 'dd/mm/yy'
	});
});
</script>
<table class="filter date">
	<tbody>
		<tr>
			<td align="right" class="label">
				<?php echo \Form::label('From', $from_id); ?>
			</td>
			<td align="left" class="input">
				<?php echo \Form::input(sprintf('grid[%s][filters][%s][from]', $filter->get_column()->get_grid(), $filter->get_column()),
								($filter->get_frontend_values()) ? $filter->get_frontend_values()->get_from() : null,
								array(
									'class' => 'filter date from',
									'id'	=> $from_id,
									'style' => ($filter->get_column()->get_width()) ? sprintf('width: %dpx;', ($filter->get_column()->get_width() - 45)) : null,
								)); 
				?>
			</td>
		</tr>
		<tr>
			<td align="right" class="label">
				<?php echo \Form::label('To', $to_id); ?>
			</td>
			<td align="left" class="input">
				<?php echo \Form::input(sprintf('grid[%s][filters][%s][to]', $filter->get_column()->get_grid(), $filter->get_column()),
								($filter->get_frontend_values()) ? $filter->get_frontend_values()->get_to() : null,
								array(
									'class' => 'filter date to',
									'id'	=> $to_id,
									'style' => ($filter->get_column()->get_width()) ? sprintf('width: %dpx;', ($filter->get_column()->get_width() - 45)) : null,
								));
				?>
			</td>
		</tr>
	</tbody>
</table>