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
 * @link       http://www.github.com/bencorlett/bc
 */

namespace Spark;
?>
<script>
$(document).ready(function()
{
	<?php if ($grid->needs_filters()): ?>
		// When the user is entering a filter and presses enter
		$("#grid-<?=$grid?> > table > thead > tr.filters > th > input.filter").keypress(function(e)
		{
			switch (e.keyCode)
			{
				case 13:
					// Loop through filters and create
					// Form based on their name / value
					$("#grid-<?=$grid?> > table > thead > tr.filters > th > input.filter").each(function()
					{
						$("#grid-<?=$grid?>-ajax-form").append('<input type="hidden" name="' + $(this).attr('name') + '" value="' + $(this).val() + '" />');
					});

					$("#grid-<?=$grid?>-ajax-form").submit();
			}
		});
	<?php endif ?>
	
	// When the user clicks on a column header
	$("#grid-<?=$grid?> > table > thead > tr.labels > th > span, #grid-<?=$grid?> > table > tfoot > tr.labels > th > span").click(function()
	{
		$("#grid-<?=$grid?>-ajax-form").append('<input type="hidden" name="grid[<?=$grid?>][sort]" value="' + $(this).attr('column') + '" />');
		
		$("#grid-<?=$grid?>-ajax-form").submit();
	});
	
	// When the user clicks select all
	$("#grid-<?=$grid?>-select-all").click(function()
	{
		$("#grid-<?=$grid?> table > tbody > tr > td > input.select").each(function()
		{
			$(this).attr('checked', true);
		});
	});
	
	// When the user clicks select all
	$("#grid-<?=$grid?>-unselect-all").click(function()
	{
		$("#grid-<?=$grid?> table > tbody > tr > td > input.select").each(function()
		{
			$(this).removeAttr('checked');
		});
	});
	
	// When the uer applies a massaction
	$("#grid-<?=$grid?>-massactions-submit").click(function()
	{
		// If there are no rows checked
		if ($("#grid-<?=$grid?> > table > tbody > tr > td > input.select:checked").length == 0)
		{
			alert('There are no rows checked!');
			return false;
		}
		
		$("#grid-<?=$grid?> > table > tbody > tr > td > input.select:checked").each(function()
		{
			$("#grid-<?=$grid?>-ajax-form").append('<input type="hidden" name="ids[]" value="' + $(this).attr('row_id') + '" />');
		});
	});
});
</script>
<table class="<?=$grid->get_identifier()?>" cellpadding="0" cellspacing="0">
	<thead>
		<?php if ($grid->needs_select()): ?>
			<tr class="actions">
				<th colspan="<?=$grid->get_column_count(1)?>">
					<table class="full-width">
						<tbody>
							<tr>
								<td align="left" class="select">
									<?=\Form::button(null, 'Select All', array('class' => 'small orange button', 'id' => sprintf('grid-%s-select-all', $grid)))?>
									<?=\Form::button(null, 'Unselect All', array('class' => 'small gray button', 'id' => sprintf('grid-%s-unselect-all', $grid)))?>
								</td>
								<td align="right" class="massactions">
									<?php

									// Array for select
									$select = array();

									// Loop through massactions and add them
									foreach ($grid->get_massactions() as $massaction)
									{
										$select[$massaction->get_action()] = $massaction->get_label();
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
		<?php endif ?>
		<tr class="labels">
			<?php if ($grid->needs_select()): ?>
				<th>

				</th>
			<?php endif ?>
			<?php foreach ($grid->get_columns() as $column): ?>
				<th>
					<span column="<?=$column?>">
						<?=$column->get_label()?>
						
						<?php $sort = $grid->get_sort() ?>
						<?php if ($sort['column'] == $column): ?>
							<?php if ($sort['direction'] == 'asc'): ?>
								&uarr;
							<?php else: ?>
								&darr;
							<?php endif ?>
						<?php endif ?>
					</span>
				</th>
			<?php endforeach ?>
		</tr>
		<?php if ($grid->needs_filters()): ?>
			<tr class="filters">
				<?php if ($grid->needs_select()): ?>
					<th>

					</th>
				<?php endif ?>
				<?php foreach ($grid->get_columns() as $column): ?>
					<th>
						<?=$column->get_filter()->get_filter_html()?>
					</th>
				<?php endforeach ?>
			</tr>
		<?php endif ?>
	</thead>
	<tbody>
		<?php foreach ($grid->get_rows() as $row_id => $row): ?>
			<tr class="cells">
				<?php if ($grid->needs_select()): ?>
					<td align="center">
						<?=\Form::checkbox(sprintf('ids[%u]', $row_id), true, array('row_id' => $row_id, 'class' => sprintf('select', $grid)))?>
					</td>
				<?php endif ?>
				<?php foreach ($grid->get_columns() as $column): ?>
					<td>
						<?=$column->get_cell_for_row($row)?>
					</td>
				<?php endforeach ?>
			</tr>
		<?php endforeach ?>
	</tbody>
	<tfoot>
		<tr class="labels">
			<?php if ($grid->needs_select()): ?>
				<th>
					
				</th>
			<?php endif ?>
			<?php foreach ($grid->get_columns() as $column): ?>
				<th>
					<span column="<?=$column?>">
						<?=$column->get_label()?>
						
						<?php $sort = $grid->get_sort() ?>
						<?php if ($sort['column'] == $column): ?>
							<?php if ($sort['direction'] == 'asc'): ?>
								&uarr;
							<?php else: ?>
								&darr;
							<?php endif ?>
						<?php endif ?>
					</span>
				</th>
			<?php endforeach ?>
		</tr>
	</tfoot>
</table>