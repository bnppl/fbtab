<?php

include __DIR__.'/../bootstrap.php';



/**
 * index  
 */
$app->match('/', function() use($app) { 
  

  
  
  if($app['request']->get('signed_request'))
  {
    if($facebook->isPageLiked())
    {
      return $app->redirect('/enter');
    }
    else
    {
      return $app->redirect('/unliked');
    }
  }
  else
  {
   /* if we aren't inside facebook - return the like og tags and redirect js */
   var_dump($app['view_config_vars']);   
   $values = array();
   $values['facebook_app_id'] ='';
   return $app['twig']->render('test.html.twig', $values ); 
  }
}); /* end of index action */

	

$app['debug'] = true;


$app->run();