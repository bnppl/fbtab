<?php
namespace Traffic\Silex\Provider;


use Silex\Application;
use Silex\ServiceProviderInterface;

class FacebookUtilsServiceProvider implements ServiceProviderInterface
{
  public function register(Application $app)
  {

        $app['facebook_utils'] = $app->share(function () use ($app) {
        
            
        $facebook_utils = new Traffic\Silex\FBTab\FacebookUtils($app['request']->get('signed_request'));

        return $facebook_utils;
    });
  }
  
  public function boot(Application $app){
      
  }
}