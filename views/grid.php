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
<style>

table {
	width: 100%;
}

.controls .pager {
	
}

.controls .filter-actions {
	text-align: right;
}


</style>

<div class="grid" id="grid-<?php echo $grid->get_identifier()?>">
	<script type="text/javascript">
	
	$(document).ready(function()
	{
		$("#grid-<?php echo $grid->get_identifier()?>").sparkGrid({
			url		: '<?php echo $grid->get_url(); ?>',
			vars	: {
				limit		: '<?php echo $grid->get_var_name_limit(); ?>',
				page		: '<?php echo $grid->get_var_name_page(); ?>',
				sort		: '<?php echo $grid->get_var_name_sort(); ?>',
				direction	: '<?php echo $grid->get_var_name_direction(); ?>',
				filters		: '<?php echo $grid->get_var_name_filters(); ?>'
			},
			ajax	: <?php echo (int) $grid->get_uses_ajax(); ?>
		});
	});
	
	</script>
	<table class="controls">
		<tbody>
			<tr>
				<td class="pager">
					<?=\Html::nbs()?>
				</td>
				<td class="filter-actions">
					<?php echo \Form::button(null, 'Reset Filters', array('class' => 'filters-reset')); ?>
					<?php echo \Form::button(null, 'Search', array('class' => 'filters-apply')); ?>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="grid">
		<thead>
			<tr>
				<?php foreach ($grid->get_columns() as $column): ?>
					<th>
						<?php echo $column->get_header(); ?>
					</th>
				<?php endforeach ?>
			</tr>
			<tr class="filters">
				<?php foreach ($grid->get_columns() as $column): ?>
					<th>
						<?php echo $column->get_filter(); ?>
					</th>
				<?php endforeach ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($grid->get_rows() as $row): ?>
				<tr>
					<?php foreach ($row as $cell): ?>
						<td>
							<?php echo $cell; ?>
						</td>
					<?php endforeach ?>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
</div>