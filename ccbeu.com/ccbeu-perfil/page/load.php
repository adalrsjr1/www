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
	<a href="resultado.php">
		<div class="counter">
			<img src="img/counter.gif"/>
			<!--<div class="nota">
			</div>-->
			<?php
				$fbDAO = $_SESSION["fbDAO"];
				$user = $_SESSION["user"];
				$likes = $fbDAO->getLikes( $user );
				$perfil = AppCore::calculaPerfilCultural( $likes );
				$amigos = AppCore::topAmigos(10);
				$_SESSION["amigos"] = serialize($amigos);
				$_SESSION["me"] = AppCore::getMe();
				$_SESSION["perfil"] = $perfil;
				//echo $perfil[0].'</br>'; // seleciona o perfil cultural do usuario
			?>
		</div>
	</a>
	
	
	
	<div class="container">
		<div class="logo-ccbeu">
		</div>
		<img src="img/logo.png"/>
		<p>
			É SIMPLES. E EXATAMENTE O QUE PARECE.<br />
			VOCÊ CLICA EM <strong>“ACEITAR”</strong> E A GENTE ENTREGA, IMEDIATAMENTE,<br />
			UM DIAGNÓSTICO COM SUAS APTIDÕES E TALENTOS.<br />
			MAIS QUE UM APLICATIVO, UM ESPELHO DO QUE O FUTURO ESPERA DE VOCÊ.
		</p>
		
		<input type="button" class="btn" type="" name="" value="Aceitar" />
	</div>
</body>
</html>