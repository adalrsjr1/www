<?php

require_once '../fb/src/facebook.php';
require_once '../FacebookGraphDAO.php';
require_once '../AppCore.php';

session_start();
?>
<meta charset="UTF-8">
<html>
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

	
	</script>
	
	<div class="container">
		<div class="logo-ccbeu">
		</div>
		<h2>
			OBRIGADO POR PARTICIPAR!
		</h2>
			
	</div>
	<div class="container">
	<?php	
			function base64_to_jpeg( $base64_string, $output_file ) {
			  $ifp = fopen( $output_file, "wb" ); 
			  fwrite( $ifp, base64_decode( $base64_string) ); 
			  fclose( $ifp ); 
			  return( $output_file ); 
			}
			//$tmpfname = tempnam ("/tmp", "FOO".rand());
			$tmpfname = "../tmp/tmp".rand();
			$tmpfname = $tmpfname.".png";
			$img = $_SESSION["resultado"];
			
			$f = base64_to_jpeg($img, $tmpfname);
				
			
			//print '<p><img src="'.$tmpfname.'" width="525" height="400" /></p>';
			//print '<p><img src="data:image/png;base64,'.$_SESSION["resultado"].'" width="525" height="400"/></p>';
			//AppCore::uploadPhoto("",$imageSave);
			$msg = "Amigos, esse é o talento que temos em comum.
					Descubra o seu! https://apps.facebook.com/ccbeu-perfil/
					#CCBEUCULTURALSCANNER";
			AppCore::uploadPhoto($msg,$tmpfname, $_SESSION['amigos']);
			
			unlink($tmpfname);
	?>
	
	<?php
		if(!AppCore::userLike('215684038469835'))
		{
			echo "<img style='margin-left: 400px;' src='img/aviso-curtir3.png' alt='' />";
		}
		
	?>
	<!--<img style='margin-left: 230px;' src='img/aviso-curtir2.png' alt='' />-->
	
		<div class="fb-like-box"
			data-href="https://www.facebook.com/CCBEUOficial" data-colorscheme="light" data-show-faces="false" data-header="false" data-stream="false" data-show-border="false">
		</div>	
	
	
	
	<div class="rodape">
		<a href="#" onclick="alert('Adalberto Junior\nadalberto.comp@gmail.com');"><input type="button" style="font-size:6;height: 21px;width: 73px;position:absolute;
  bottom:0;" class="btn" type="" name="" value="Desenvolvido por"/></a>
	</div>	
	</div>
	
</body>
</html>