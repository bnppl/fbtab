<?php
/**
* PHP versions 5
*
* @license http://www.opensource.org/licenses/mit-license.php The MIT License (MIT)
*/
namespace Traffic\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
* Traffic\Silex\Provider\RedBeanServiceProvider
*
*/
class HerokuDBConfigServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['db_config'] = $app->share(function() use ($app) {
            
            /* extract relevant info from db url */
            if(isset($app['heroku.db_env_variable']))
            {
                $db_url = getenv($app['heroku.db_env_variable']);
                
                
            }
            else{
                $db_url = getenv('DATABASE_URL');
                
            }
            
            if(!$db_url){
                return false;
            }
            
            if(strpos($db_url, '?'))
            {
                list($db_url, $params) = explode('?', $db_url);
            }
            list($dbtype, $db_url) = explode('://', $db_url);
            list($credentials, $location) = explode('@', $db_url);
            list($username, $password) = explode(':', $credentials);
            list($server, $db_name) = explode('/',$location);

            $settings['dsn'] = $dbtype.":host=".$server.";dbname=".$db_name;
            $settings['username'] = $username;
            $settings['password'] = $password;
            
            return $settings;
        });
    }
    
    public function boot(Application $app)
    {
        
    }
    
}