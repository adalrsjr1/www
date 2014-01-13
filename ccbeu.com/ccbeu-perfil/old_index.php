<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Show CCBEU</title>
    </head>
    <body>

<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */
 
	require 'fb/src/facebook.php';
	require_once 'FacebookGraphDAO.php';
	require_once 'AppCore.php';


// Create our Application instance (replace this with your appId and secret).
    $app_id = '189828001185136';
    $app_secret = 'c28cc9ca39be608b5668178a8aacfbdc';
    $app_namespace = 'teste-ccbeu';
    $app_url = 'http://apps.facebook.com/teste-ccbeu';
    $scope = 'email,publish_actions,user_relationships ';

    // Init the Facebook SDK
    $facebook = new Facebook(array(
		'appId' => 	'596714360390574',
		'secret' => '1e0cb829eb1e18678f207163982c747c',
	));

	$fbDAO = new FacebookGraphDAO($facebook);
	$_SESSION["fbDAO"] = $fbDAO;
	
	// Get the current user
	$user = $fbDAO->getUser();
	
	// If the user has not installed the app, redirect them to the Login Dialog
	if (!$user) {
	        $loginUrl = $facebook->getLoginUrl(array(
	        'scope' => $scope,
	        'redirect_uri' => $app_url,
	        ));
	
	        print('<script> top.location.href=\'' . $loginUrl . '\'</script>');
	}
	
	$sstart = microtime(true);
	
	
	$likes = $fbDAO->getLikes( $facebook->getUser());
	
	//var_dump($likes);
		
	$start = microtime(true);
	echo "<h1> Top 10 amigos </h1></br>";
	// probobabilidade namorada / familia
	var_dump( AppCore::topAmigos(10) ); // melhores amigos
	$time_taken = microtime(true) - $start;
	echo '<br/>time exec::: '.$time_taken.'<br/><br/>';
	
	/*$start = microtime(true);
	var_dump( $fbDAO->getFriendsRanking() ); // melhores amigos
	$time_taken = microtime(true) - $start;
	echo '<br/>time exec::: '.$time_taken.'<br/><br/>';*/
	
	/*
	$start = microtime(true);

	echo "<h1> Perfil cultural </h1></br>";
	
	var_dump( AppCore::calculaPerfilCultural( $likes ) ); // perfil cultural
	
	$time_taken = microtime(true) - $start;
	echo '<br/>time exec::: '.$time_taken.'<br/><br/>';
	*/

/*	echo "<h1> Fluencia baseada nos posts </h1></br>";
	
	$start = microtime(true);
	echo AppCore::calculaFluencia($fbDAO->getFeed()); // fluencia feed
	
	$time_taken = microtime(true) - $start;
	echo '<br/>time exec::: '.$time_taken.'<br/><br/>';
*/

	/*echo "<h1> Fluencia baseada nos likes </h1></br>";
	
	$start = microtime(true);
	echo AppCore::calculaFluenciaLikes( $likes ); // fluencia like
	
	$time_taken = microtime(true) - $start;
	/*echo '<br/>time exec::: '.$time_taken.'<br/><br/>';
	
	$time_taken = microtime(true) - $sstart;
	echo '<br/>total time exec::: '.$time_taken.'<br/><br/>';*/

//$top = AppCore::topAmigos();
	//AppCore::calculaFluenciaAmigos($top);

?>
	</body>
</html>