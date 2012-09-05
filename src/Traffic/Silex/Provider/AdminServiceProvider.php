<?php
namespace Traffic\Silex\Provider;


use Silex\Application;
use Silex\ServiceProviderInterface;


class AdminServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {

      
        $app['admin'] = $app->share(function () use ($app) {
            
            
           
            
            $admin = new \Traffic\Silex\FBTab\Admin();
            
            return $admin;
        });
    }
    
    public function boot(Application $app){}
}