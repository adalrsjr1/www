<?php

	require_once 'fb/src/facebook.php';
	require_once 'FacebookGraphDAO.php';
	require_once 'AppCore.php';
	
	$scope = 'email,publish_actions,user_relationships,user_about_me,publish_stream';
	$app_url = 'http://www.ccbeu.com/app/ccbeu-perfil/';
	$facebook = new Facebook(array(
		 	'appId'=>'596714360390574',
		 	'secret'=>'1e0cb829eb1e18678f207163982c747c',
		 	'cookie'=>true,
		 	'domain'=>$app_url,
		 	'scope'=> $scope
		 ));
	 
	$user = $facebook->getUser();
	
	//echo "<strong>Current session content</strong>:";
	//var_dump($user);
	
	if (!$user) {
	        $loginUrl = $facebook->getLoginUrl(array(
	 	       'scope' => $scope,
	    	    'redirect_uri' => $app_url,
	        ));
	
	        print('<script> top.location.href=\'' . $loginUrl . '\'</script>');
	}
	
	/*
	try{
	     $me = $facebook ->api('/me');
	     echo "<strong>Graph data about me</strong>:";
	     var_dump($me);
	}catch(FacebookApiException $e){
	     echo "<strong>Facebook exception</strong>: ".$e;
	     $url = $facebook->getLoginUrl(array('canvas'=>1,'fbconnect'=>0));
	     echo "<h3><a href=\"javascript:void(0)\" onclick=\"top.location.href = '".$url."'\">Get access token</a></h3>";
	}

*/
	$t = AppCore::userLike('215684038469835');
	var_dump(AppCore::topAmigos(10));
	//$fbDAO = new FacebookGraphDAO($facebook);
	//var_dump($fbDAO->userLike('215684038469835'));
?>