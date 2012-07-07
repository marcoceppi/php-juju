<?php
/**
 * Juju php abstraction
 * 
 * @author Marco Ceppi <marco@ceppi.net>
 * @package php-juju
 * @subpackage api
 */

/**
 * Juju API class
 */
class Juju_API
{
	public static $juju_bin = "/usr/bin/juju";
	protected static $environments;
	
	public static function status($environment)
	{
		return json_decode(static::exec(array('status', 'e' => $environment, 'format' => 'json')), true);
	}
	
	public static function environment($environment, $settings = array())
	{
		// TODO
		if( !empty($settings) )
		{
			// Write it out to file with yaml_emit and file_put_contents
		}
		else
		{
			$envs = static::environments();
			
			return (array_key_exists($environment, $envs)) ? $envs[$environment] : false;
		}
	}
	
	/**
	 * Juju Environments
	 * 
	 * This will retrieve the list of all environments for the current user
	 * 
	 * @param string $user
	 * 
	 * @return array
	 */
	public static function environments($user = NULL)
	{
		if( !is_array(static::$environments) )
		{
			$user = array_shift(posix_getpwuid(posix_geteuid()));
			$env_file = file_get_contents('/home/' . $user . '/.juju/environments.yaml');
			$juju_envs = yaml_parse($env_file);
			
			static::$environments = $juju_envs['environments'];
		}
		
		return static::$environments;
	}
	
	public static function environment_exists($environment)
	{
		return array_key_exists($environment, static::environments());
	}
	
	public static function bootstrap($environment)
	{
		if( static::environment_exists($environment) )
		{
			return static::exec(array('bootstrap', 'e' => $environment));
		}
		else
		{
			throw new Exception('Environment doesn\'t exist');
		}
	}
	
	public static function deploy($environment, $charm)
	{
		
	}
	
	public static function destroy_service($environment, $service)
	{
		
	}
	
	public static function expose($environment, $service)
	{
		return static::exec(array('expose', $service, 'e' => $environment));
	}
	
	public static function unexpose($environment, $service)
	{
		return static::exec(array('unexpose', $service, 'e' => $environment));
	}
	
	public static function resolved($environment, $service)
	{
		
	}
	
	public static function terminate($environment, $machine)
	{
		if( is_array($machine) )
		{
			$machine = implode(' ', $machine);
		}
		
		return static::exec(array('terminate-machine', $machine, 'e' => $environment));
	}
	
	public static function destroy_environment($environment)
	{
		if( static::environment_exists($environment) )
		{
			exec('yes | ' . static::$juju_bin . ' -l /dev/null destroy-environment -e ' . $environment, $exec_raw, $ret);
			
			return ($ret != 0) ? false : true;
		}
		else
		{
			throw new Exception('Environment does not exist');
		}
		
		return false;
	}
	
	public static function config($environment, $service, $params = array())
	{
		
	}
	
	public static function add_unit($environment, $service, $num_units = 1)
	{
		
	}
	
	public static function remove_unit($environment, $service, $num_units = 1)
	{
		
	}
	
	public static function add_relation($environment, $interface, $services)
	{
		
	}
	
	public static function remove_relation($environment, $interface, $services)
	{
		
	}
	
	/**
	 * Execute Juju command
	 * 
	 * @TODO Make this part of a CLI super-class so Charm and Juju can
	 * share this.
	 */
	protected static function exec($params)
	{
		exec(static::$juju_bin . ' -l /dev/null ' . static::param($params), $exec_raw, $ret);
		
		return ($ret > 0) ? false : implode("\n", $exec_raw);
	}

	/**
	 * Build command-line parameters
	 * 
	 * @TODO Make this part of a CLI super-class so Charm and Juju can
	 * share this.
	 */
	protected static function param($param)
	{
		$params = '';
		foreach($param as $key => $val)
		{
			if( !is_numeric($key) )
			{
				$params .= ' ' . ((strlen($key) > 1) ? '--' : '-') . $key . ' ' . $val;
			}
			else
			{
				$params .= ' ' . $val;
			}
		}
		
		return $params;
	}
}
