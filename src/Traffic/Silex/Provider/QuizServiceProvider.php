<?php
namespace Traffic\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;


class QuizServiceProvider{
    
    public function register(Application $app)
    {
        $app['quiz'] = $app->share(function() use ($app) {
            
            return $database;
        });
    }
    
    public function boot(Application $app)
    {
        
    }
}