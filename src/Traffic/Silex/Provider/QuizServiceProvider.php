<?php
namespace Traffic\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;


class QuizServiceProvider implements ServiceProviderInterface{
    
    public function register(Application $app)
    {
        $app['quiz'] = $app->share(function() use ($app) {
            
            $quiz = new \Traffic\Silex\FBTab\Quiz($app['pdo'], $app['form.factory'], $app['user_fields']);
            
            return $quiz;
        });
    }
    
    
    public function boot(Application $app)
    {
        
    }
}