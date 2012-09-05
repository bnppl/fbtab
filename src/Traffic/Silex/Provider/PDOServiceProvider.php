<?php
namespace Traffic\Silex\Provider;


use Silex\Application;
use Silex\ServiceProviderInterface;

class PDOServiceProvider implements ServiceProviderInterface
{
  public function register(Application $app)
  {

     $app['pdo'] = $app->share(function () use ($app) {
        
        $db_config = $app['db_config'];

        $pdo = new \PDO($db_config['dsn'], $db_config['username'], $db_config['password']);

        return $pdo;
    });
  }
  
  public function boot(Application $app){
      
  }
}