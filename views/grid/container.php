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
?>
<style>
body {
	font-family: "Helvetica";
	color: #666;
}
</style>
<div style="text-align: right; border-bottom: 1px solid #ccc;">
	<?php if ($container->get_add_button()): ?>
		<?=$container->get_add_button()?>
	<?php endif ?>
</div>