<?php
namespace Traffic;


use Silex\Application;
use Silex\ServiceProviderInterface;


class AdminServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {

      
        $app['admin'] = $app->share(function () use ($app) {
            
            require_once $app['admin.class_path'];
           
            
            $admin = new Admin();
            
            return $admin;
        });
    }
    
    public function boot(Application $app){}
}