// jQuery Plugin
(function($) {
	
	// Normal usage
	// $("#<?=$grid?>").sparkGrid();
	$.fn.sparkGrid = function () {
		
		/**
		 * The Element Object
		 * 
		 * @var	Object
		 */
		var element		= $(this);
		
		/**
		 * The Selector String
		 * 
		 * @var	String
		 */
		var selector	= $(element).selector;
		
		/**
		 * The Grid ID
		 * 
		 * @var	String
		 */
		var grid_id		= selector.replace('#grid-', '');
		
		// Make ajax form
		$(selector + '-ajax-form').ajaxForm({
			target			: selector,
			beforeSubmit	: function() {
				
				// Show the overlay
				$(selector + '-overlay').show();
			},
			success			: function() {
				
				// Empty the form, we might use it again
				$(selector + '-ajax-form').empty();
			}
		});
		
		// When the user is entering a filter and presses enter
		$(selector + ' > table > thead > tr.filters > th input.filter').keypress(function(e)
		{
			switch (e.keyCode)
			{
				case 13:
					// Loop through filters and create
					// Form based on their name / value
					$(selector + ' > table > thead > tr.filters > th input.filter').each(function()
					{
						$(selector + '-ajax-form').append('<input type="hidden" name="' + $(this).attr('name') + '" value="' + $(this).val() + '" />');
					});
					
					$(selector + '-ajax-form').submit();
			}
		});
		
		// When the user clicks on a column header or footer
		$(selector + ' > table > thead > tr.labels > th > span, ' + selector + ' > table > tfoot > tr.labels > th > span').click(function()
		{
			$(selector + '-ajax-form').append('<input type="hidden" name="grid[' + grid_id + '][sort]" value="' + $(this).attr('column') + '" />');

			$(selector + '-ajax-form').submit();
		});
		
		// When the user clicks select all
		$(selector + '-select-all').click(function()
		{
			$(selector + ' > table > tbody > tr > td > input.select').each(function()
			{
				$(this).attr('checked', true);
			});
		});

		// When the user clicks select all
		$(selector + '-unselect-all').click(function()
		{
			$(selector + ' > table > tbody > tr > td > input.select').each(function()
			{
				$(this).removeAttr('checked');
			});
		});
		
		// When the uer applies a massaction
		$(selector + '-massactions-submit').click(function()
		{
			// If there are no rows checked
			if ($(selector + ' > table > tbody > tr > td > input.select:checked').length == 0)
			{
				alert('There are no rows checked!');
				return false;
			}

			$(selector + ' > table > tbody > tr > td > input.select:checked').each(function()
			{
				// Add the fields
				$(selector + '-massactions-form').append('<input type="hidden" name="ids[]" value="' + $(this).attr('row_id') + '" />');
			});

			// Set the action
			$(selector + '-massactions-form').attr('action', $(selector + '-massactions-select').val());

			// Submit the form
			setTimeout(function()
			{
				$(selector + '-massactions-form').submit();
			}, 30);
		});
		
		// Pagination
		$(selector + '-pagination-previous:not(.disabled)').click(function()
		{
			$(selector + '-ajax-form').append('<input type="hidden" name="grid[' + grid_id + '][pagination]" value="' + (parseInt($(selector + '-pagination-value').attr('original-value')) - 1) + '" />');

			$(selector + '-ajax-form').submit();
		});
		
		$(selector + '-pagination-next:not(.disabled)').click(function()
		{
			$(selector + '-ajax-form').append('<input type="hidden" name="grid[' + grid_id + '][pagination]" value="' + (parseInt($(selector + '-pagination-value').attr('original-value')) + 1) + '" />');

			$(selector + '-ajax-form').submit();
		});
		
		$(selector + '-pagination-value:not(.disabled)').keypress(function(e)
		{
			switch (e.keyCode)
			{
				case 13:
					if (parseInt($(this).val()) > $(this).attr('maximum-value')) $(this).val($(this).attr('maximum-value'));
					if (parseInt($(this).val()) < $(this).attr('minimum-value')) $(this).val($(this).attr('minimum-value'));
					$(selector + '-ajax-form').append('<input type="hidden" name="grid[' + grid_id + '][pagination]" value="' + $(this).val() + '" />');
					$(selector + '-ajax-form').submit();
					break;
			}
		});
	}
	
})(jQuery);