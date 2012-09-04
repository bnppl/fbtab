<?php
namespace Traffic\Silex\Provider;


use Silex\Application;
use Silex\ServiceProviderInterface;

class PDOServiceProvider implements ServiceProviderInterface
{
  public function register(Application $app)
  {

     $app['pdo'] = $app->share(function () use ($app) {

        $dsn = $app['pdo.dsn'];
        $username = $app['pdo.username'];
        $password = $app['pdo.password'];
        $pdo = new \PDO($dsn, $username, $password);

        return $pdo;
    });
  }
  
  public function boot(Application $app){}
}