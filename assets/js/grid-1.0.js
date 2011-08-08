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
				// Reset form
				handler.find('form.filters-form').remove();
				handler.append($("<form></form>").css('display', 'block').addClass('filters-form'));
				
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
				// Reset form
				handler.find('form.filters-form').remove();
				handler.append($("<form></form>").css('display', 'block').addClass('filters-form'));
				
				// Loop through filters and add to the data
				// object, to be sent off to the grid url
				handler.find("tr.filters .filter").each(function()
				{
					// Only add to the filters if they
					// exist, no point filling it with
					// empty data
					if ($(this).val()) {
						$(this).clone().appendTo(handler.find('form.filters-form'));
					}
				});
				
				// // Trigger an update on the handler
				handler.trigger('update');
			});
			
			/**
			 * The user can click enter
			 * as a shortcut to apply filters
			 */
			handler.find("tr.filters .filter").keypress(function(e)
			{
				switch (e.keyCode) {
					case 13:
						handler.find("button.filters-apply").trigger('click');
						break;
				}
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
				data[settings.vars.filters] = handler.find('form.filters-form').formParams();
				
				// Determine if we want
				// to use ajax or not
				if (settings.ajax) {
					$.ajax({
						url			: settings.url,
						data		: data,
						success		: function(data, textStatus, jqXHR) {
							handler.replaceWith(data);
						}
					});
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

/**
 * Form Parameters - converts
 * inputs in form to an object
 * regardless of nesting of arrays
 */
(function( $ ) {
	var radioCheck = /radio|checkbox/i,
		keyBreaker = /[^\[\]]+/g,
		numberMatcher = /^[\-+]?[0-9]*\.?[0-9]+([eE][\-+]?[0-9]+)?$/;

	var isNumber = function( value ) {
		if ( typeof value == 'number' ) {
			return true;
		}

		if ( typeof value != 'string' ) {
			return false;
		}

		return value.match(numberMatcher);
	};

	$.fn.extend({
		/**
		 * @parent dom
		 * @download http://jmvcsite.heroku.com/pluginify?plugins[]=jquery/dom/form_params/form_params.js
		 * @plugin jquery/dom/form_params
		 * @test jquery/dom/form_params/qunit.html
		 * <p>Returns an object of name-value pairs that represents values in a form.  
		 * It is able to nest values whose element's name has square brackets. </p>
		 * Example html:
		 * @codestart html
		 * &lt;form>
		 *   &lt;input name="foo[bar]" value='2'/>
		 *   &lt;input name="foo[ced]" value='4'/>
		 * &lt;form/>
		 * @codeend
		 * Example code:
		 * @codestart
		 * $('form').formParams() //-> { foo:{bar:2, ced: 4} }
		 * @codeend
		 * 
		 * @demo jquery/dom/form_params/form_params.html
		 * 
		 * @param {Boolean} [convert] True if strings that look like numbers and booleans should be converted.  Defaults to true.
		 * @return {Object} An object of name-value pairs.
		 */
		formParams: function( convert ) {
			if ( this[0].nodeName.toLowerCase() == 'form' && this[0].elements ) {

				return jQuery(jQuery.makeArray(this[0].elements)).getParams(convert);
			}
			return jQuery("input[name], textarea[name], select[name]", this[0]).getParams(convert);
		},
		getParams: function( convert ) {
			var data = {},
				current;

			convert = convert === undefined ? true : convert;

			this.each(function() {
				var el = this,
					type = el.type && el.type.toLowerCase();
				//if we are submit, ignore
				if ((type == 'submit') || !el.name ) {
					return;
				}

				var key = el.name,
					value = $.data(el, "value") || $.fn.val.call([el]),
					isRadioCheck = radioCheck.test(el.type),
					parts = key.match(keyBreaker),
					write = !isRadioCheck || !! el.checked,
					//make an array of values
					lastPart;

				if ( convert ) {
					if ( isNumber(value) ) {
						value = parseFloat(value);
					} else if ( value === 'true' || value === 'false' ) {
						value = Boolean(value);
					}

				}

				// go through and create nested objects
				current = data;
				for ( var i = 0; i < parts.length - 1; i++ ) {
					if (!current[parts[i]] ) {
						current[parts[i]] = {};
					}
					current = current[parts[i]];
				}
				lastPart = parts[parts.length - 1];

				//now we are on the last part, set the value
				if ( lastPart in current && type === "checkbox" ) {
					if (!$.isArray(current[lastPart]) ) {
						current[lastPart] = current[lastPart] === undefined ? [] : [current[lastPart]];
					}
					if ( write ) {
						current[lastPart].push(value);
					}
				} else if ( write || !current[lastPart] ) {
					current[lastPart] = write ? value : undefined;
				}

			});
			return data;
		}
	});

})(jQuery)