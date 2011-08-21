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
<div id="breadcrumbs" class="breadcrumbs">
	<ul>
		<?php foreach ($breadcrumbs as $breadcrumb): ?>
			<li>
				<?php if ($breadcrumb->get_uri()): ?>
					<?php echo \Html::anchor($breadcrumb->get_uri(), $breadcrumb->get_text()); ?>
				<?php else: ?>
					<?php echo html_tag('span', array(), $breadcrumb->get_text())?>
				<?php endif ?>
			</li>
		<?php endforeach?>
	</ul>
</div>