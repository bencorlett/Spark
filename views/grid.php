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
	
	$(document).ready(function() {
		
		$("#grid-<?php echo $grid->get_identifier()?>").sparkGrid({
			identifier		: '<?php echo $grid->get_identifier(); ?>',
			url				: '<?php echo $grid->get_url(); ?>',
			vars			: {
				limit			: '<?php echo $grid->get_var_name_limit(); ?>',
				page			: '<?php echo $grid->get_var_name_page(); ?>',
				sort			: '<?php echo $grid->get_var_name_sort(); ?>',
				direction		: '<?php echo $grid->get_var_name_direction(); ?>',
				filters			: '<?php echo $grid->get_var_name_filters(); ?>'
			},
			ajax			: <?php echo (int) $grid->get_uses_ajax(); ?>
			<?php if (($params = $grid->get_current_params_json()) !== false): ?>
				, currentParams: <?php echo $params; ?>
			<?php endif ?>
		});
	});
	
	</script>
	<table class="controls">
		<tbody>
			<tr>
				<td class="pager">
					Page
					<span class="previous">&laquo;</span>
					<?php echo \Form::input(null, $grid->get_page(), array('class' => 'page', 'style' => 'width: 30px;')); ?>
					<span class="next">&raquo;</span>
					of <?php echo $grid->get_total_pages(); ?>
					<span class="separator">|</span>
					View <?php echo \Form::select(null, $grid->get_limit(), $grid->get_limit_options(), array('class' => 'limit')); ?> per page
					<span class="separator">|</span>
					Total <?php echo $grid->get_total_records(); ?> record<?php echo $grid->get_total_records() != 1 ? 's' : null; ?> found
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
			<tr class="headers">
				<?php foreach ($grid->get_columns() as $column): ?>
					<th>
						<span class="header" column="<?php echo $column->get_index(); ?>">
							<?php echo $column->get_header(); ?>
						</span>
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