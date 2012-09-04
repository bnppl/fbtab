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
class RedBeanServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['redbean'] = $app->share(function() use ($app) {
            if (isset($app['redbean.class_path'])) {
                $redbean_path = $app['redbean.class_path'] . DIRECTORY_SEPARATOR . 'rb.php';
                if ('\\' === DIRECTORY_SEPARATOR) {
                    $redbean_path = str_replace('\\', '/', $redbean_path);
                }
                include_once $redbean_path;
            }

            $database = \R::setup();
            return $database;
        });
    }
    
    public function boot(Application $app)
    {
        
    }
    
}