<?php 
session_start();

require_once '../fb/src/facebook.php';
require_once '../FacebookGraphDAO.php';
require_once '../AppCore.php';

$app_id = '596714360390574';
$app_secret = '1e0cb829eb1e18678f207163982c747c';
$scope = 'email,publish_actions,user_relationships,user_about_me,publish_stream,user_photos';
$app_url = 'http://www.ccbeu.com/app/ccbeu-perfil/page';

// pega objecto fb
$facebook = new Facebook(array(
	 	'appId'=> $app_id,
	 	'secret'=> $app_secret,
	 	'cookie'=>true,
	 	'domain'=>$app_url,
	 	'scope'=> $scope,
	 	'fileUpload'=>true
	 ));
 
$user = $facebook->getUser();
	
// cria link para conectar
if (!$user) {
	$url = $facebook->getLoginUrl(array(
       'scope' => $scope,
	    'redirect_uri' => "https://apps.facebook.com/ccbeu-perfil",//$app_url,
    ));
	echo "
		<meta charset=\"UTF-8\">
		
		<head>
			<title> CCBEU2014 — Cultural Scanner</title>
			<link rel=\"stylesheet\" href=\"style.css\" />
		</head>
		<body>

			<div class=\"container\">
			<div class=\"logo-ccbeu\">
			</div>
			<img src=\"img/logo.png\"/>
			<h2>
				DESCUBRA SEU TALENTO.<br/>
				
				MAIS DO QUE UM APLICATIVO, UM ESPELHO DO QUE O FUTURO ESPERA DE VOCÊ.<br/>
				INSPIRE-SE!<br/>
				<!--
				É SIMPLES. E EXATAMENTE O QUE PARECE.<br />
				VOCÊ CLICA EM <strong>“ACEITAR”</strong> E A GENTE ENTREGA, IMEDIATAMENTE,<br />
				UM DIAGNÓSTICO COM SUAS APTIDÕES E TALENTOS.<br />
				MAIS QUE UM APLICATIVO, UM ESPELHO DO QUE O FUTURO ESPERA DE VOCÊ.
				-->
			</h2>
		
			<a href=\"javascript:void(0)\" onclick=\"top.location.href = '".$url."'\"> <input type=\"button\" class=\"btn\" type=\"\" name=\"\" value=\"Aceitar\" /></a>
				
		</body>

		</html>
	";
	
	//echo "<h3><a href=\"javascript:void(0)\" onclick=\"top.location.href = '".$url."'\">Get access token</a></h3>";
	die();
}

$fbDAO = new FacebookGraphDAO($facebook);
$_SESSION["fbDAO"] = $fbDAO;
$_SESSION["user"] = $user;
?>

<meta charset="UTF-8">
<html xmlns:fb="http://ogp.me/ns/fb#">
<head>
	<title> CCBEU2014 — Cultural Scanner</title>
	<link rel="stylesheet" href="style.css" />
</head>
<body>
	
	<div id="fb-root"></div>
	<script>
	(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=456484411139990";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	
	window.fbAsyncInit = function() {
    FB.Event.subscribe('edge.create', function(response) {
      //console.log(response);
      //alert('Thanks for liking!');
      //window.location.href = "load.php";
    });
 
    FB.Event.subscribe('edge.remove', function(response) {
      //console.log(response);
      //alert('Sad to see you dont like me :(');
      //location.reload();//location.reload();
    });
  };
	
	</script>
	
	<!--<div class="counter">
		<img src="img/counter.gif"/>
	</div>-->
	<div class="container">
		<div class="logo-ccbeu">
		</div>
		<img src="img/logo.png"/>
		<h2>
			DESCUBRA SEU TALENTO.<br/>
			
			MAIS DO QUE UM APLICATIVO, UM ESPELHO DO QUE O FUTURO ESPERA DE VOCÊ.<br/>
			INSPIRE-SE!<br/>
			<!--
			É SIMPLES. E EXATAMENTE O QUE PARECE.<br />
			VOCÊ CLICA EM <strong>“ACEITAR”</strong> E A GENTE ENTREGA, IMEDIATAMENTE,<br />
			UM DIAGNÓSTICO COM SUAS APTIDÕES E TALENTOS.<br />
			MAIS QUE UM APLICATIVO, UM ESPELHO DO QUE O FUTURO ESPERA DE VOCÊ.
			-->
		</h2>
		
		<a href="load.php"><input type="button" class="btn" type="" name="" value="Participar" /></a>
		</div>
		
		
		<?php
		if(!AppCore::userLike('215684038469835'))
		{
			
			echo "<img style='margin-left: 230px;' src='img/aviso-curtir3.png' alt='' />";
		}
		/*	if(AppCore::userLike('215684038469835'))
			{
				
				
				echo '<a href="load.php"><input type="button" class="btn" type="" name="" value="Participar" /></a>';		
			}
			else 
			{				
				echo "<a href='#' onclick='alert".'("Você precisa curtir a página do CCBEU para continuar.\nÉ só curtir no fim da página ;)")'.";location.reload();'>
				<input type='button' class='btn' type='' name='' value='Participar' /></a>
				</div>
				<img style='margin-left: 230px;' src='img/aviso-curtir2.png' alt='' />	";
						
										
			}*/
		?>
	
	<div >
		<div class="fb-like-box"
	data-href="https://www.facebook.com/CCBEUOficial" data-colorscheme="light" data-show-faces="false" data-header="false" data-stream="false" data-show-border="false"></div>	
	</div>
	
	
</body>

<script type="text/javascript">
adroll_adv_id = "HCHUZZ32IFEYJO5ZFHIIQY";
adroll_pix_id = "M4UYDVFFHFAH3KD4QSAAUN";
(function () {
var oldonload = window.onload;
window.onload = function(){
   __adroll_loaded=true;
   var scr = document.createElement("script");
   var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
   scr.setAttribute('async', 'true');
   scr.type = "text/javascript";
   scr.src = host + "/j/roundtrip.js";
   ((document.getElementsByTagName('head') || [null])[0] ||
    document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
   if(oldonload){oldonload()}};
}());

(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-36608025-2', 'facebook.com');
ga('send', 'pageview');
</script>

</html>