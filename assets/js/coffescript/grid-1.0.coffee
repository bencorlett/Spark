###
Spark Fuel Package By Ben Corlett

Spark - Set your fuel on fire!

The Spark Fuel Package is an open-source
fuel package consisting of several 'widgets'
engineered to make developing various
web-based systems easier and quicker.

@package    Fuel
@subpackage Spark
@version    1.0
@author     Ben Corlett (http://www.bencorlett.com)
@license    MIT License
@copyright  (c) 2011 Ben Corlett
@link       http://spark.bencorlett.com
###

(($) ->
	methods = init: (options) ->
		settings = 
			identifier: "grid"
			url: ""
			vars: 
				limit: "limit"
				page: "page"
				sort: "sort"
				direction: "direction"
				filters: "filters"
			
			ajax: true
			currentParams: {}
		
		
		handler = this
		$.extend settings, options	if options
		settings.currentParams[settings.vars.filters] = {}	if typeof settings.currentParams[settings.vars.filters] == "undefined"
		handler.find("button.filters-reset").click ->
			handler.find("form.filters-form").remove()
			handler.append $("<form></form>").css("display", "none").addClass("filters-form")
			handler.find("tr.filters .filter").each ->
				$(this).val ""
			
			handler.trigger "update"
		
		handler.find("button.filters-apply").click ->
			handler.trigger "reset-filters-form"
			handler.find("tr.filters .filter").each ->
				handler.find("form.filters-form").append $("<input></input>").attr("name", $(this).attr("name")).attr("value", $(this).attr("value"))	if $(this).val()
			
			handler.data settings.vars.page, 1
			handler.trigger "update"
		
		handler.find("tr.filters .filter").keypress (e) ->
			switch e.keyCode
				when 13
					handler.find("button.filters-apply").trigger "click"
		
		handler.bind "reset-filters-form", ->
			handler.find("form.filters-form").remove()
			handler.append $("<form></form>").css("display", "none").addClass("filters-form")
		
		handler.find("tr.headers span.header").click ->
			handler.data settings.vars.sort, $(this).attr("column")
			handler.trigger "update"
		
		handler.find("tr.headers span.header-checkbox input[type=\"checkbox\"]").change ->
			handler.find("tbody tr input[type=\"checkbox\"][name=" + $(this).attr("targets") + "]")[(if $(this).attr("checked") then "attr" else "removeAttr")] "checked", "checked"
		
		handler.find("table.controls input.page").keypress (e) ->
			switch e.keyCode
				when 13
					$(this).trigger "update"
		
		handler.find("table.controls input.page").bind "update", ->
			handler.data settings.vars.page, $(this).val()
			handler.trigger "update"
		
		handler.find("table.controls span.previous").click ->
			handler.find("table.controls input.page").val(parseInt(handler.find("table.controls input.page").val()) - 1).trigger "update"
		
		handler.find("table.controls span.next").click ->
			handler.find("table.controls input.page").val(parseInt(handler.find("table.controls input.page").val()) + 1).trigger "update"
		
		handler.find("table.controls .limit").change ->
			handler.data settings.vars.limit, $(this).val()
			handler.data settings.vars.page, 1
			handler.trigger "update"
		
		handler.bind "update", ->
			settings.currentParams[settings.vars.filters] = handler.find("form.filters-form").formParams()	if handler.find("form.filters-form").length > 0
			if handler.data(settings.vars.sort)
				if (settings.currentParams[settings.vars.sort] == handler.data(settings.vars.sort)) and settings.currentParams[settings.vars.direction] == "asc"
					settings.currentParams[settings.vars.direction] = "desc"
				else
					settings.currentParams[settings.vars.direction] = "asc"
				settings.currentParams[settings.vars.sort] = handler.data(settings.vars.sort)
			settings.currentParams[settings.vars.page] = handler.data(settings.vars.page)	if handler.data(settings.vars.page)
			settings.currentParams[settings.vars.limit] = handler.data(settings.vars.limit)	if handler.data(settings.vars.limit)
			$.cookie "grid-" + settings.identifier, JSON.stringify(settings.currentParams)
			if settings.ajax
				$.ajax 
					url: settings.url
					data: grid_identifier: settings.identifier
					success: (data, textStatus, jqXHR) ->
						handler.replaceWith data
			else
				window.location.href = settings.url
	
	$.fn.sparkGrid = (method) ->
		if methods[method]
			methods[method].apply this, Array::slice.call(arguments, 1)
		else if typeof method == "object" or not method
			methods.init.apply this, arguments
		else
			$.error "Method " + method + " does not exist in jQuery.sparkGrid"
) jQuery
(($) ->
	radioCheck = /radio|checkbox/i
	keyBreaker = /[^\[\]]+/g
	numberMatcher = /^[\-+]?[0-9]*\.?[0-9]+([eE][\-+]?[0-9]+)?$/
	isNumber = (value) ->
		return true	if typeof value == "number"
		return false	unless typeof value == "string"
		value.match numberMatcher
	
	$.fn.extend 
		formParams: (convert) ->
			return jQuery(jQuery.makeArray(this[0].elements)).getParams(convert)	if this[0].nodeName.toLowerCase() == "form" and this[0].elements
			jQuery("input[name], textarea[name], select[name]", this[0]).getParams convert
		
		getParams: (convert) ->
			data = {}
			convert = (if convert == undefined then true else convert)
			@each ->
				el = this
				type = el.type and el.type.toLowerCase()
				return	if (type == "submit") or not el.name
				key = el.name
				value = $.data(el, "value") or $.fn.val.call([ el ])
				isRadioCheck = radioCheck.test(el.type)
				parts = key.match(keyBreaker)
				write = not isRadioCheck or !!el.checked
				if convert
					if isNumber(value)
						value = parseFloat(value)
					else value = Boolean(value)	if value == "true" or value == "false"
				current = data
				i = 0
				
				while i < parts.length - 1
					current[parts[i]] = {}	unless current[parts[i]]
					current = current[parts[i]]
					i++
				lastPart = parts[parts.length - 1]
				if lastPart of current and type == "checkbox"
					current[lastPart] = (if current[lastPart] == undefined then [] else [ current[lastPart] ])	unless $.isArray(current[lastPart])
					current[lastPart].push value	if write
				else current[lastPart] = (if write then value else undefined)	if write or not current[lastPart]
			
			data
) jQuery

jQuery.cookie = (key, value, options) ->
	if arguments.length > 1 and String(value) != "[object Object]"
		options = jQuery.extend({}, options)
		options.expires = -1	if value == null or value == undefined
		if typeof options.expires == "number"
			days = options.expires
			t = options.expires = new Date()
			t.setDate t.getDate() + days
		value = String(value)
		return (document.cookie = [ encodeURIComponent(key), "=", (if options.raw then value else encodeURIComponent(value)), (if options.expires then "; expires=" + options.expires.toUTCString() else ""), (if options.path then "; path=" + options.path else ""), (if options.domain then "; domain=" + options.domain else ""), (if options.secure then "; secure" else "") ].join(""))
	options = value or {}
	decode = (if options.raw then (s) ->
	  s
	else decodeURIComponent)
	(if (result = new RegExp("(?:^|; )" + encodeURIComponent(key) + "=([^;]*)").exec(document.cookie)) then decode(result[1]) else null)