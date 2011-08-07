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
(function($) {
	
	$.fn.sparkGrid = function() {
		
		/**
		 * The element object
		 * 
		 * @var	Object
		 */
		var element = $(this);
		
		/**
		 * The selector string
		 * 
		 * @var	string
		 */
		var selector = $(element).selector;
		
		/**
		 * The grid identifier
		 * 
		 * @var	string
		 */
		var identifier = selector.substr(6);
		
		/**
		 * When the user clicks on
		 * search, to apply the filters
		 */
		$(selector + "-filter-actions-search").click(function()
		{
			// Filters object
			var filters = {};
			
			$("input.grid-" + identifier + "-filters").each(function()
			{
				if ($(this).val())
				{
					filters[$(this).attr('name')] = $(this).val();
				}
			});
		});
	}
	
})(jQuery);