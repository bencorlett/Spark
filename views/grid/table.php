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
<script>
$(document).ready(function()
{
	$("#grid-<?php echo $grid; ?>").sparkGrid();
});
</script>
<div id="grid-<?php echo $grid; ?>-overlay" class="overlay">
</div>
<table class="<?php echo $grid->get_identifier(); ?>" cellpadding="0" cellspacing="0">
	<thead>
		<?php if ($grid->needs_select()): ?>
			<tr class="actions">
				<th colspan="<?php echo $grid->get_column_count(($grid->needs_select()) ? 1 : 0); ?>">
					<table class="full-width">
						<tbody>
							<tr>
								<td align="left" class="select">
									<?php echo \Form::button(null, 'Select All', array('class' => 'small orange button', 'id' => sprintf('grid-%s-select-all', $grid))); ?>
									<?php echo \Form::button(null, 'Unselect All', array('class' => 'small gray button', 'id' => sprintf('grid-%s-unselect-all', $grid))); ?>
								</td>
								<td align="right" class="massactions">
									<?php

									// Array for select
									$select = array();

									// Loop through massactions and add them
									foreach ($grid->get_massactions() as $massaction)
									{
										$select[\Uri::create($massaction->get_action())] = $massaction->get_label();
									}

									// Make a label
									echo \Form::label('With Selected ', sprintf('grid-%s-massactions-select', $grid));

									// Make a select
									echo \Form::select('massactions[select]', null, $select, array('id' => sprintf('grid-%s-massactions-select', $grid)));

									// And a submit
									echo \Form::button(null, 'Submit', array('class' => 'small white button', 'id' => sprintf('grid-%s-massactions-submit', $grid)));

									?>
								</td>
							</tr>
						</tbody>
					</table>
				</th>
			</tr>
		<?php endif; ?>
		<tr class="labels">
			<?php if ($grid->needs_select()): ?>
				<th>

				</th>
			<?php endif; ?>
			<?php foreach ($grid->get_columns() as $column): ?>
				<th <?php echo ($column->get_width()) ? sprintf('width=\'%u\'', $column->get_width()) : null; ?>>
					<span column="<?php echo $column; ?>">
						<?php echo $column->get_label(); ?>
						
						<?php $sort = $grid->get_sort(); ?>
						<?php if ($sort['column'] == $column): ?>
							<?php if ($sort['direction'] == 'asc'): ?>
								&uarr;
							<?php else: ?>
								&darr;
							<?php endif; ?>
						<?php endif; ?>
					</span>
				</th>
			<?php endforeach; ?>
		</tr>
		<?php if ($grid->get_uses_filters()): ?>
			<tr class="filters">
				<?php if ($grid->needs_select()): ?>
					<th>

					</th>
				<?php endif; ?>
				<?php foreach ($grid->get_columns() as $column): ?>
					<th <?php echo ($column->get_width()) ? sprintf('width=\'%u\'', $column->get_width()) : null; ?>>
						<?php echo $column->get_filter()->get_filter_html(); ?>
					</th>
				<?php endforeach; ?>
			</tr>
		<?php endif; ?>
	</thead>
	<tbody class="<?php echo ($grid->get_uses_bottom_labels()) ?: 'bottom'; ?>">
		<?php $i = 0; ?>
		<?php foreach ($grid->get_rows() as $row_id => $row): ?>
			<?php
			
			// Weird bug - somehow there can still
			// be rows even though this foreach
			// loop might never run (if there are no
			// results) We'll use a counter here to determine
			// how rows we've actually displayed.
			$i++;
			
			?>
			<tr class="cells">
				<?php if ($grid->needs_select()): ?>
					<td align="center" class="first">
					<?php echo \Form::checkbox(sprintf('ids[%u]', $row_id), true, array('row_id' => $row_id, 'class' => sprintf('select', $grid))); ?>
					</td>
				<?php endif; ?>
				<?php $x = 0; ?>
				<?php foreach ($grid->get_columns() as $column): ?>
					<td <?php echo ($column->get_width()) ? sprintf('width="%u"', $column->get_width()) : null; ?> <?php echo ($column->get_align()) ? sprintf('align="%s"', $column->get_align()) : null; ?> <?php echo ($grid->get_driver()->build_row_action($row)) ? sprintf('onclick="window.location.href=\'%s\'"', $grid->get_driver()->build_row_action($row)) : null; ?> class="<?php echo ($grid->needs_select()) ?: 'first'; ?> <?php echo ($x++ == $grid->get_column_count(-1)) ? 'last' : null; ?>">
						<?php echo $column->get_cell_for_row($row); ?>
					</td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
		<?php if ($i == 0): ?>
			<tr class="no-rows">
				<td colspan="<?php echo $grid->get_column_count(($grid->needs_select()) ? 1 : 0); ?>" align="center">
					<em>
						No rows to display.
					</em>
				</td>
			</tr>
		<?php endif; ?>
	</tbody>
	<?php if ($grid->get_uses_bottom_labels()): ?>
		<tfoot>
			<tr class="labels">
				<?php if ($grid->needs_select()): ?>
					<th>

					</th>
				<?php endif; ?>
				<?php foreach ($grid->get_columns() as $column): ?>
					<th <?php echo ($column->get_width()) ? sprintf('width=\'%u\'', $column->get_width()) : null; ?>>
						<span column="<?php echo $column; ?>">
							<?php echo $column->get_label(); ?>

							<?php $sort = $grid->get_sort(); ?>
							<?php if ($sort['column'] == $column): ?>
								<?php if ($sort['direction'] == 'asc'): ?>
									&uarr;
								<?php else: ?>
									&darr;
								<?php endif; ?>
							<?php endif; ?>
						</span>
					</th>
				<?php endforeach; ?>
			</tr>
		</tfoot>
	<?php endif; ?>
</table>