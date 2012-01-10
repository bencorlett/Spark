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
<div class="grid" id="grid-<?php echo $grid->get_identifier()?>">
	<script type="text/javascript">
	
	/**
	 * Initialise the grid
	 */
	$(document).ready(function() {
		
		$("#grid-<?php echo $grid->get_identifier()?>").sparkGrid({
			identifier			: '<?php echo $grid->get_identifier(); ?>',
			url					: '<?php echo $grid->get_url(); ?>',
			vars				: {
				limit				: '<?php echo $grid->get_var_name_limit(); ?>',
				page				: '<?php echo $grid->get_var_name_page(); ?>',
				sort				: '<?php echo $grid->get_var_name_sort(); ?>',
				direction			: '<?php echo $grid->get_var_name_direction(); ?>',
				filters				: '<?php echo $grid->get_var_name_filters(); ?>'
			},
			ajax				: <?php echo (int) $grid->get_uses_ajax(); ?>,
			showOverlay			: true,
			<?php
			/**
			 * See http://api.jquery.com/fadeIn/
			 * for fadeIn speed options, eg:
			 *    - 'fast'
			 *    - 'slow'
			 *    - 200
			 *    - 100
			 */
			?>
			fadeOverlaySpeed	: 100
			<?php if (($params = $grid->get_current_params_json()) !== false): ?>
				, currentParams: <?php echo $params; ?>
			<?php endif ?>
		});
	});
	
	</script>

	<?php
	/**
	 * The massactions view
	 */
	?>
	<?=$grid->get_massactions_view()?>

	<?php
	/**
	 * The grid controls - pagination
	 */
	if ($grid->get_uses_controls()):
	?>
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

					<?php if ($grid->get_uses_filters()): ?>
						<td class="filter-actions">
							<?php echo \Form::button(null, 'Reset Filters', array('class' => 'filters-reset')); ?>
							<?php echo \Form::button(null, 'Search', array('class' => 'filters-apply')); ?>
						</td>
					<?php endif ?>
				</tr>
			</tbody>
		</table>
	<?php endif ?>

	<?php
	/**
	 * The grid itself
	 */
	?>
	<table class="grid">

		<?php
		/**
		 * The head of the table
		 */
		?>
		<thead>
			
			<?php
			/**
			 * The headers for the grid
			 */
			?>
			<tr class="headers">
				<?php foreach ($grid->get_columns() as $column): ?>
					<th class="<?php echo $column->get_header()->get_class(); ?>" style="<?php echo $column->get_style(); ?>">
						<?php echo $column->get_header(); ?>
					</th>
				<?php endforeach ?>
			</tr>

			<?php
			/**
			 * The filters for the grid
			 */
			if ($grid->get_uses_filters()): 
			?>
				<tr class="filters">
					<?php foreach ($grid->get_columns() as $column): ?>
						<th class="<?php echo $column->get_class(); ?>" style="<?php echo $column->get_style(); ?>">
							<?php echo $column->get_filter(); ?>
						</th>
					<?php endforeach ?>
				</tr>
			<?php endif ?>
		</thead>

		<?php
		/**
		 * The body of the table
		 */
		?>
		<tbody>
			<?php foreach ($grid->get_rows() as $row): ?>
				<tr class="<?php echo $row->get_class(); ?>">
					<?php foreach ($row->get_cells() as $cell): ?>
						<td class="<?php echo $cell->get_class(); ?>" style="<?php echo $cell->get_style(); ?>" onclick="<?php echo $cell->get_onclick(); ?>">
							<?php echo $cell; ?>
						</td>
					<?php endforeach ?>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<?php
	/**
	 * The overlay div that's shown when the grid is loading
	 */
	?>
	<div class="overlay">
		<div class="loading">
			<h1>Loading</h1>
		</div>
	</div>
</div>