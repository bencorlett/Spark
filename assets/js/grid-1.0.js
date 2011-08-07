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
	
	/**
	 * Settings object
	 * 
	 * @var	Object
	 */
	var settings = {};
	
	/**
	 * Object containing filters
	 * 
	 * @var	Object
	 */
	var filters = {};
	
	/**
	 * The handler of the plugin,
	 * the element the plugin
	 * is triggered on
	 */
	var handler;
	
	/**
	 * Plugin methods
	 */
	var methods = {
		
		/**
		 * Init
		 * 
		 * Used to initalise the plugin
		 */
		init: function(options) {
			
			if (options) {
				$.extend(settings, options);
			}
			
			/**
			 * Set the handler variable
			 * to the trigger element
			 */
			handler = this;
			
			/**
			 * When the user resets filters
			 */
			handler.find("button.filters-reset").click(function()
			{
				// Reset the filters object
				filters = {};
				
				// Wipe the inputs, just to look nice
				handler.find("tr.filters .filter").each(function()
				{
					$(this).val('');
				});
				
				// Trigger an update on the handler
				handler.trigger('update');
			});
			
			/**
			 * When the user applies filters
			 */
			handler.find("button.filters-apply").click(function()
			{
				// Reset the filters if
				// they exist
				if (filters) filters = {};
				
				// Loop through filters and add to the data
				// object, to be sent off to the grid url
				handler.find("tr.filters .filter").each(function()
				{
					// Only add to the filters if they
					// exist, no point filling it with
					// empty data
					if ($(this).val()) {
						filters[$(this).attr('name')] = $(this).val();
					}
				});
				
				// Trigger an update on the handler
				handler.trigger('update');
			});
			
			/**
			 * When the handler is to update
			 */
			handler.bind('update', function()
			{
				// Object to be sent
				var data = {};
				
				// Make sure we dynamically
				// set the parameter names
				// based off what's configured
				// in the grid class
				data[settings.vars.filters] = filters;
				
				// Determine if we want
				// to use ajax or not
				if (settings.ajax) {
					alert('use ajax');
				}
				else {
					
					// If not, we need to build a query
					// string and visit that url
					var param = $.param(data);
					var href = settings.url + (param ? '?' + param : '');
					
					// Visit the url
					window.location.href = href;
				}
			});
		}
	};
	
	/**
	 * jQuery Plugin namespace declaration
	 */
	$.fn.sparkGrid = function(method) {
		
		/**
		 * Delegate functionality
		 * based on method
		 */
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		else if (typeof method === 'object' || ! method) {
			return methods.init.apply(this, arguments);
		}
		else {
			$.error('Method ' + method + ' does not exist in jQuery.sparkGrid');
		}
	}
	
})(jQuery);