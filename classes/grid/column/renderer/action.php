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

class Grid_Column_Renderer_Action extends \Grid_Column_Renderer_Abstract {
	
	/**
	 * Render
	 * 
	 * Renders a cell and populates
	 * it's rendered value
	 * 
	 * @access	public
	 * @param	Spark\Grid_Column_Cell	Cell
	 * @return	Spark\Grid_Column_Renderer_Action
	 */
	public function render(\Grid_Column_Cell $cell)
	{
		// Get value
		$value = $cell->get_original_value();
		
		if ($actions = $cell->get_actions())
		{
			if ($actions->count() > 1)
			{
				// Convert uri to somethign that the browser will like
				$value = \Form::select(null,
									   null,
									   array(null => null) + $actions->get_data(),
									   array(
									   		'onchange'		=> 'window.location.href=this.value',
									   ));
			}
			else
			{
				foreach ($actions as $action => $value)
				{
					$value = \Html::anchor($action, $value);
					break;
				}
			}
		}
		
		// Set rendered
		$cell->set_rendered_value($value);
		
		return $this;
	}
	
	/**
	 * Process Single Action
	 * 
	 * Processes a single action
	 * 
	 * @access	public
	 * @param	Spark\Object	Actions object
	 * @return	Spark\Object	Actions object
	 */
	public function process_single_action(\Object $action)
	{
		
		
		return $action;
	}
	
	/**
	 * Render Header
	 * 
	 * Renders the header of a column
	 * 
	 * @access	public
	 * @param	Spark\Grid_Column_Header	Header
	 * @return	Spark\Grid_Column_Renderer_Interface
	 */
	public function render_header(\Grid_Column_Header $header)
	{
		// Standard header
		$header->set_rendered_value(html_tag('span', array(
			'class'			=> 'header',
			'column'		=> $header->get_column()->get_index(),
		), $header->get_original_value()));
		
		return $this;
	}
	
	/**
	 * Allows Action
	 * 
	 * Returns whether the renderer for this
	 * column allows cells within it to have
	 * an action applied to them or not
	 * 
	 * @access	public
	 * @return	bool	Allows action
	 */
	public function allows_action()
	{
		return false;
	}
}