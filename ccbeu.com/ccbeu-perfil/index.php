<?php session_start(); ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>CCBEU Perfil</title>
    </head>
    <body>
<?php
	
	
	require_once 'fb/src/facebook.php';
	require_once 'FacebookGraphDAO.php';
	require_once 'AppCore.php';
	
	$app_id = '596714360390574';
	$app_secret = '1e0cb829eb1e18678f207163982c747c';
	$scope = 'email,publish_actions,user_relationships,user_about_me,publish_stream';
	$app_url = 'https://apps.facebook.com/ccbeu-perfil';
	
	// pega objecto fb
	$facebook = new Facebook(array(
		 	'appId'=> $app_id,
		 	'secret'=> $app_secret,
		 	'cookie'=>true,
		 	'domain'=>$app_url,
		 	'scope'=> $scope
		 ));
	 
	$user = $facebook->getUser();
		
	// cria link para conectar
	if (!$user) {
		$url = $facebook->getLoginUrl(array(
	       'scope' => $scope,
		    'redirect_uri' => $app_url,
	    ));
		//echo "<h3><a href=\"javascript:void(0)\" onclick=\"top.location.href = '".$url."'\">Get access token</a></h3>";
		die();
	}

	$fbDAO = new FacebookGraphDAO($facebook);
	$_SESSION["fbDAO"] = $fbDAO;

	$sstart = microtime(true);
	
	$likes = $fbDAO->getLikes( $user );
		
	$start = microtime(true);
	/*echo "<h1> Top 10 amigos </h1></br>";
	// probobabilidade namorada / familia
	var_dump( AppCore::topAmigos(10) ); // melhores amigos
	$time_taken = microtime(true) - $start;
	echo '<br/>time exec::: '.$time_taken.'<br/><br/>';
	 * */
	
	//$start = microtime(true);
	//var_dump( $fbDAO->getFriendsRanking() ); // melhores amigos
	var_dump(AppCore::topAmigos(10));
	/*$time_taken = microtime(true) - $start;
	echo '<br/>time exec::: '.$time_taken.'<br/><br/>';
	
	
	$start = microtime(true);
	
	echo "<h1> Perfil cultural </h1></br>";
	
	$perfil = AppCore::calculaPerfilCultural( $likes );
	//shuffle ( $perfil );
	echo "</br>";
	var_dump($perfil ); // perfil cultural
	/*
	$time_taken = microtime(true) - $start;
	echo '<br/>time exec::: '.$time_taken.'<br/><br/>';

	echo "<h1> Fluencia baseada nos posts </h1></br>";
	
	$start = microtime(true);
	echo AppCore::calculaFluencia($fbDAO->getFeed()); // fluencia feed
	
	$time_taken = microtime(true) - $start;
	echo '<br/>time exec::: '.$time_taken.'<br/><br/>';


	echo "<h1> Fluencia baseada nos likes </h1></br>";
	
	$start = microtime(true);
	echo AppCore::calculaFluenciaLikes( $likes ); // fluencia like
	
	$time_taken = microtime(true) - $start;	
*/
?>
</html>