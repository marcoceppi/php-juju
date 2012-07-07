<?php
/**
 * Charm php abstraction
 * 
 * @author Marco Ceppi <marco@ceppi.net>
 * @package php-juju
 * @subpackage api
 */

/**
 * Charm API
 */
class Charm
{
	public static proof($charm, $repository)
	{
		
	}
	
	public static fork($charm, $repository = 'cs')
	{
		
	}
	
	public static load($charm, $repository = 'cs')
	{
		$charm_data = array();
		if( $repository != 'cs' )
		{
			$charm_data['repository'] = realpath($respository);
			$charm_data[] = 'local:' . $charm;
		}
		else
		{
			$charm_data[] = 'cs:' . $charm;
		}
		
		return $charm_data;
	}
}
