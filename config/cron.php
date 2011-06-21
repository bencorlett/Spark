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
 * @link       http://www.github.com/bencorlett/spark
 */
return array(
	
	// The addresses for when something goes
	// wrong in the cron systme
	'from_address'		=> 'mail@counciljobs.com',
	'from_name'			=> 'Council Jobs',
	'to_address'		=> 'ben@tjstechnology.com.au',
	
	// How many times a cron job can unsuccessfully
	// run before it is discarded and an email is sent
	// to the person configured above
	'threshold'			=> 5,
);

/* End of file config/cron.php */