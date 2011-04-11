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
<table class="<?=$grid->get_identifier()?>">
	<thead>
		<tr class="actions">
			<th colspan="<?=$grid->get_column_count(1)?>">
				Actions
			</th>
		</tr>
		<tr class="labels">
			<th>
				
			</th>
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
		<tr class="filters">
			<th>
				
			</th>
			<?php foreach ($grid->get_columns() as $column): ?>
				<th>
					<?=$column->get_filter()->get_filter_html()?>
				</th>
			<?php endforeach ?>
	</thead>
	<tbody>
		<?php foreach ($grid->get_rows() as $row): ?>
			<tr>
				<td>
					<?=\Form::checkbox(null)?>
				</td>
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
			<th>
				
			</th>
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
<?=\Form::open(array('id' => sprintf('grid-%s-form', $grid)))?>
<?=\Form::close()?>