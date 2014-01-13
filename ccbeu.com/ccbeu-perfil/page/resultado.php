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
	
	<div class="container">
		<div class="logo-ccbeu">
		</div>
		<div class="resultado">
			<?php
				$me = $_SESSION['me'];
				echo "<img id='avatar' class='avatar-sqrd' width='525' height='400' src='".$me[0]["pic"]."'/>";
				echo "<img id='result' class='resultado-img' width='525' height='400' src='img/perfis/".$_SESSION['perfil'].".jpg'/>";
			?>
			
			
		</div>
		<div class="amigos">
			<h2 style="text-align: right;"> Veja quem dos seus amigos mais combina com você:</h2>
			
			<?php
				$amigos = unserialize($_SESSION["amigos"]);
				foreach ($amigos as $key ) {
					$fb = "https://www.facebook.com/".$key["uid"];
					echo "<div class='amigo-foto'>
							 <a href='".$fb."'>							 
						     <img src=".$key["pic_big"]." width='50' height='50' >
						     </a>
						  </div>";
				} 
			?>
			
		</div>
		<?php echo AppCore::frases($_SESSION['perfil']); ?>
		<!--
		<p>
			Um tipo Ben Afleck, que tem talento pra atuar e dirigir. Mas se liga, nesse ramo, falar inglês é fundamental. 
		</p>
		<p>
			Ou você vai fazer o seu discurso em português no Oscar?
		</p>
		-->
		<a href="end.php" style="margin-left: -245px"><input type="button" class="btn" type="" name="" value="Compartilhar"/></a>
			
			
		<?php
			//echo $me[0]["pic_big"]."</br>";
		
		
			$img1 = imagecreatefromjpeg("img/perfis/".$_SESSION['perfil'].".jpg");
			$img2 = imagecreatefromjpeg($me[0]["pic"]);
			
			imagecopymerge($img1, $img2, 40, 40, 0, 0, 100, 100, 100);
			//header('Content-Type: image/png');
			
			$tmpfname = "../tmp/res".rand();
			$tmpfname = $tmpfname.".png";
			
			imagejpeg($img1,$tmpfname);
			$im = file_get_contents($tmpfname);
			$_SESSION["resultado"] = base64_encode($im);
			
			/*ob_start();
			imagejpeg($img1);
			// Capture the output
			$imagedata = ob_get_contents();
			// Clear the output buffer
			ob_end_clean();
			$_SESSION["resultado"] = base64_encode($imagedata);*/
			
			imagedestroy($img1);
			imagedestroy($img2);
			//imagedestroy($im);
			
			unlink($tmpfname);
			/*$img2 = imagecreatefromjpeg($url);
			
			header('Content-Type: image/png');
			imagecopymerge($img1, $img2, 10, 10, 0, 0, imagesx($img2), imagesy($img2),50); 
			imagesavealpha($img1, true); 
			imagepng($img1, NULL); 
			
			imagedestroy($img1);
			imagedestroy($img2);*/
			
			//$dao->uploadPhoto("teste","img/resultado.jpg");
			//AppCore::uploadPhoto("","img/resultado.jpg");
			
			//var_dump($me);
		?>
	</div>

	
</body>
</html>