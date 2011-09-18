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
	 * Plugin methods
	 */
	var methods = {
		
		/**
		 * Init
		 * 
		 * Used to initalise the plugin
		 */
		init: function(options) {
			
			/**
			 * Settings object
			 * 
			 * @var	Object
			 */
			var settings = {
				identifier			: 'grid',
				url					: '',
				vars				: {
					limit				: 'limit',
					page				: 'page',
					sort				: 'sort',
					direction			: 'direction',
					filters				: 'filters',
				},
				ajax				: true,
				currentParams		: {},
				showOverlay			: true,
				fadeOverlaySpeed	: 'fast',
			};

			/**
			 * The handler of the plugin,
			 * the element the plugin
			 * is triggered on
			 */
			var handler;
			
			/**
			 * Set the handler variable
			 * to the trigger element
			 */
			handler = this;
			
			if (options) {
				$.extend(settings, options);
			}
			
			/**
			 * Initialise parameters
			 */
			if (typeof settings.currentParams[settings.vars.filters] === 'undefined')
			{
				settings.currentParams[settings.vars.filters] = {};
			}
			
			/**
			 * When the user resets filters
			 */
			handler.find("button.filters-reset").click(function() {
				
				// Reset form
				handler.find('form.filters-form').remove();
				handler.append($("<form></form>").css('display', 'none').addClass('filters-form'));
				
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
			handler.find("button.filters-apply").click(function() {
				
				// Reset the form
				handler.trigger('reset-filters-form');
				
				// Loop through filters and add to the data
				// object, to be sent off to the grid url
				handler.find("tr.filters .filter").each(function()
				{
					// Only add to the filters if they
					// exist, no point filling it with
					// empty data
					if ($(this).val()) {
						
						// The second val() method is to make sure that select boxes
						// get the value that the original select box had
						handler.find('form.filters-form').append($('<input></input>').attr('name', $(this).attr('name')).attr('value', $(this).attr('value')));
					}
				});
				
				// Reset to page 1
				handler.data(settings.vars.page, 1);
				
				// Trigger an update on the handler
				handler.trigger('update');
			});
			
			/**
			 * The user can click enter
			 * as a shortcut to apply filters
			 */
			handler.find("tr.filters .filter").keypress(function(e) {
				
				switch (e.keyCode) {
					case 13:
						handler.find("button.filters-apply").trigger('click');
						break;
				}
			});
			
			/**
			 * Reset filters form
			 */
			handler.bind('reset-filters-form', function() {
				
				// Reset form
				handler.find('form.filters-form').remove();
				handler.append($("<form></form>").css('display', 'none').addClass('filters-form'));
			});
			
			/**
			 * When the user clicks on
			 * a column header to sort
			 * by that column
			 */
			handler.find("tr.headers span.header").click(function() {
				
				// Set the sort
				handler.data(settings.vars.sort, $(this).attr('column'));
				
				// Trigger an update
				handler.trigger('update');
			});
			
			/**
			 * When a user clicks a checkbox header to select
			 * all checkboxes in the grid
			 */
			handler.find('tr.headers span.header-checkbox input[type="checkbox"]').change(function()
			{
				handler.find('tbody tr input[type="checkbox"][name=' + $(this).attr('targets') + ']')[$(this).attr('checked') ? 'attr' : 'removeAttr']('checked', 'checked');
			});
			
			/**
			 * When the user types
			 * a value in the pagination
			 */
			handler.find('.pager input.page').keypress(function(e) {
				
				switch (e.keyCode) {
					case 13:
						
						$(this).trigger('update')
						break;
				}
			});
			
			/**
			 * Update pagination from
			 * input value
			 */
			handler.find('.pager input.page').bind('update', function() {
				
				// Set the page
				handler.data(settings.vars.page, $(this).val());
				
				// Trigger an update
				handler.trigger('update');
			});
			
			/**
			 * When user clicks previous in
			 * the pagination
			 */
			handler.find('.pager .previous').click(function() {
				
				// Increase the value
				handler.find('.pager input.page').val(parseInt(handler.find('.pager input.page').val()) - 1).trigger('update');
			});
			
			/**
			 * When user clicks next in
			 * the pagination
			 */
			handler.find('.pager .next').click(function() {
				
				// Increase the value
				handler.find('.pager input.page').val(parseInt(handler.find('.pager input.page').val()) + 1).trigger('update');
			});
			
			/**
			 * When user changes the limit
			 */
			handler.find('.pager .limit').change(function() {
				
				// Set the limit
				handler.data(settings.vars.limit, $(this).val());
				
				// Reset to page 1
				handler.data(settings.vars.page, 1);
				
				// Trigger an update
				handler.trigger('update');
			});
			
			/**
			 * When the handler needs to update
			 */
			handler.bind('update', function() {

				/**
				 * Fade in overlay
				 */
				if (settings.showOverlay) {
					handler.find('div.overlay').fadeIn(settings.fadeOverlaySpeed);
				}
				
				/**
				 * Update filter params
				 */
				if (handler.find('form.filters-form').length > 0 ) {
					settings.currentParams[settings.vars.filters] = handler.find('form.filters-form').formParams();
				}
				
				/**
				 * Update sort and direction
				 */
				if (handler.data(settings.vars.sort)) {
					
					// Work out the direction based on the column
					// we're clicking on
					if ((settings.currentParams[settings.vars.sort] == handler.data(settings.vars.sort)) && settings.currentParams[settings.vars.direction] == 'asc') {
						settings.currentParams[settings.vars.direction] = 'desc';
					}
					else settings.currentParams[settings.vars.direction] = 'asc';
					
					settings.currentParams[settings.vars.sort] = handler.data(settings.vars.sort);
				}
				
				/**
				 * Update pagination
				 */
				if (handler.data(settings.vars.page)) {
					settings.currentParams[settings.vars.page] = handler.data(settings.vars.page);
				}
				
				/**
				 * Update limit
				 */
				if (handler.data(settings.vars.limit)) {
					settings.currentParams[settings.vars.limit] = handler.data(settings.vars.limit);
				}
				
				// Set them to a cookie
				$.cookie('grid-' + settings.identifier, JSON.stringify(settings.currentParams));
				
				// Determine if we want
				// to use ajax or not
				if (settings.ajax) {
					$.ajax({
						url     : settings.url,
						data    : {
							grid_identifier	: settings.identifier
						},
						success : function(data, textStatus, jqXHR) {

							if (settings.showOverlay) {
								handler.find('div.overlay').fadeOut(settings.showOverlaySpeed, function() {
									handler.replaceWith(data);
								});
							}
							else {
								handler.replaceWith(data);
							}
						},
						error   : function(jqXHR, textStatus, errorThrown) {
							handler.find('div.overlay').fadeOut('fast');
						}
					});
				}
				else {
					
					// If not, we just simply
					// visit the grid url
					window.location.href = settings.url;
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

/**
 * jQuery Cookie plugin
 *
 * Copyright (c) 2010 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */
jQuery.cookie = function (key, value, options) {

    // key and at least value given, set cookie...
    if (arguments.length > 1 && String(value) !== "[object Object]") {
        options = jQuery.extend({}, options);

        if (value === null || value === undefined) {
            options.expires = -1;
        }

        if (typeof options.expires === 'number') {
            var days = options.expires, t = options.expires = new Date();
            t.setDate(t.getDate() + days);
        }

        value = String(value);

        return (document.cookie = [
            encodeURIComponent(key), '=',
            options.raw ? value : encodeURIComponent(value),
            options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
            options.path ? '; path=' + options.path : '',
            options.domain ? '; domain=' + options.domain : '',
            options.secure ? '; secure' : ''
        ].join(''));
    }

    // key and possibly options given, get cookie...
    options = value || {};
    var result, decode = options.raw ? function (s) { return s; } : decodeURIComponent;
    return (result = new RegExp('(?:^|; )' + encodeURIComponent(key) + '=([^;]*)').exec(document.cookie)) ? decode(result[1]) : null;
};
