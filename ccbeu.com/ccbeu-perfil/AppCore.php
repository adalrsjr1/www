<?php
//require_once 'alchemy/module/AlchemyAPI.php';
//require_once 'PEAR.php';
//require_once "./detect/Text/LanguageDetect.php";


/*
 * {
 * 	"status": "OK",
 * 	"usage": "By accessing AlchemyAPI or using information generated by AlchemyAPI,
 * 				you are agreeing to be bound by the AlchemyAPI
 * 				Terms of Use: http://www.alchemyapi.com/company/terms.html",
 * 	"url": "",
 * 	"language": "english",
 * 	"iso-639-1": "en",
 * 	"iso-639-2": "eng",
 * 	"iso-639-3": "eng",
 * 	"ethnologue": "http://www.ethnologue.com/show_language.asp?code=eng",
 * 	"native-speakers": "309-400 million",
 * 	"wikipedia": "http://en.wikipedia.org/wiki/English_language"
 * }
 * */
class AppCore {
	private static function idiomaTexto($texto) {
		$alchemyObj = new AlchemyAPI();
		$alchemyObj -> loadAPIKey('alchemy/key');

		$result_json = $alchemyObj -> TextGetLanguage($texto, 'json');

		$result = json_decode($result_json);

		//return $result->{'iso-639-1'};
		return $result_json;
	}

	// Function to get the client ip address
	private function get_client_ip() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if (getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if (getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if (getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if (getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if (getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';

		return $ipaddress;
	}

	/*
	 * {
	 * 	"ip":"186.223.237.29",
	 * 	"country_code":"BR",
	 * 	"country_name":"Brazil",
	 * 	"region_code":"29",
	 * 	"region_name":"Goiás",
	 * 	"city":"Anápolis",
	 * 	"zipcode":"",
	 * 	"latitude":-16.3333,
	 * 	"longitude":-48.9667,
	 * 	"metro_code":"",
	 * 	"areacode":""
	 * }
	 */
	public static function getLocation() {
		//echo $_SERVER['REMOTE_ADDR'];
		//$IP = "186.223.237.29";
		$IP = self::get_client_ip();

		$resp = file_get_contents('http://freegeoip.net/json/' . $IP);
		return $resp;
	}

	/*
	 * Get the first n best friends, where significant_other is
	 * ther best best fried, follow by the family and finally by
	 * the friends with more related friends with the user
	 * */
	public static function topAmigos($limit) {
		$fbDAO = $_SESSION['fbDAO'];
		
		$amigos = $fbDAO->getFriendsRanking();
		
		$dez_amigos = array();
		
		for ($i=0; $i < 10; $i++) {
			if($i == 5)
			{
				shuffle($amigos);		
			} 
			array_push($dez_amigos,array_shift($amigos));
		}
		
		shuffle($dez_amigos);
		return $dez_amigos;
		
		/*$fbDAO = $_SESSION['fbDAO'];

		$companheiro = $fbDAO -> getSignificantOther();
		$family = $fbDAO -> getFamily();
		$friends = $fbDAO -> getBestFriends($limit);

		$top = array();
		$count = 0;
		if($limit == 0)
			return $top;
		
		if (!empty($companheiro['significant_other'])) {
			$aux = $companheiro['significant_other'];
			$top[$count] = array('id' => $aux['id'], 'name' => $aux['name']);
			++$count;
			if ($count > $limit)
				return $top;
		}

		if (!empty($family)) {
			$aux = $family['family']['data'];
			foreach ($aux as $key) {
				$top[$count] = array('id' => $key['id'], 'name' => $key['name']);
				++$count;
				if ($count > $limit)
					return $top;

			}
		}

		if (!empty($friends)) {
			$aux = $friends;
			foreach ($aux as $key) {
				$top[$count] = array('id' => $key['uid'], 'name' => $key['name']);
				++$count;
				if ($count > $limit)
					return $top;

			}
		}

		return $top;*/
	}

	/*
	 * Muito lento.. melhorar excluir
	 * */
	public static function calculaFluenciaAmigos($amigos) {
		$fbDAO = $_SESSION['fbDAO'];

		$result = array();
		$count = 0;
		foreach ($amigos as $key) {
			$posts = $fbDAO -> getFriendFeed($key['id'], 20);
			$fluencia = self::calculaFluencia($posts);
			$result[$count] = array('id' => $key['id'], 'name' => $key['name'], 'fluencia' => $fluencia);
			++$count;
			echo "nome: $key[name] fluencia: $fluencia <br/>";
		}

		//var_dump($result);
		return $result;
	}

	private static function categoriesLike()
	{
		$categories = array();
			
		$categories['ACTOR/DIRECTOR'] = 'cinema';
		$categories['AEROSPACE/DEFENSE'] = 'espaco';
		$categories['AIRPORT'] = 'sabores';
		$categories['ALBUM'] = 'fotografia';
		$categories['AMATEUR SPORTS TEAM'] = 'esporte';
		$categories['ANATOMICAL STRUCTURE'] = 'biologia';
		$categories['ANIMAL'] = 'animais';
		$categories['ANIMAL BREED'] = 'animais';
		$categories['APP'] = 'software';
		$categories['APP PAGE'] = 'software';
		$categories['APPLIANCES'] = 'ciencia';
		$categories['ARTIST'] = 'arte';
		$categories['ARTS/ENTERTAINMENT/NIGHTLIFE'] = 'arte';
		$categories['ARTS/HUMANITIES WEBSITE'] = 'arte';
		$categories['ATHLETE'] = 'esportes';
		$categories['ATTRACTIONS/THINGS TO DO'] = 'sabores';
		$categories['AUTHOR'] = 'conhecimento';
		$categories['AUTOMOBILES AND PARTS'] = 'motor';
		$categories['AUTOMOTIVE'] = 'motor';
		$categories['BABY GOODS/KIDS GOODS'] = 'comunidade';
		$categories['BAGS/LUGGAGE'] = 'sabores';
		$categories['BANK/FINANCIAL INSTITUTION'] = 'negocios';
		$categories['BANK/FINANCIAL SERVICES'] = 'negocios';
		$categories['BAR'] = 'sabores';
		$categories['BIOTECHNOLOGY'] = 'biologia';
		$categories['BOOK'] = 'conhecimento';
		$categories['BOOK GENRE'] = 'escrever';
		$categories['BOOK STORE'] = 'escrever';
		$categories['BUILDING MATERIALS'] = 'arquitetura';
		$categories['BUSINESS PERSON'] = 'negocios';
		$categories['BUSINESS SERVICES'] = 'negocios';
		$categories['BUSINESS/ECONOMY WEBSITE'] = 'negocios';
		$categories['CAMERA/PHOTO'] = 'fotografia';
		$categories['CARS'] = 'motor';
		$categories['CAUSE'] = 'comunidade';
		$categories['CHEF'] = 'comida';
		$categories['CHEMICALS'] = 'drugs';
		$categories['CHURCH/RELIGIOUS ORGANIZATION'] = 'religiao';
		$categories['CITY'] = 'sabores';
		$categories['CLOTHING'] = 'moda';
		$categories['CLUB'] = 'eventos';
		$categories['COACH'] = 'esportes';
		$categories['COLOR'] = 'arte';
		$categories['COMEDIAN'] = 'humor';
		$categories['COMMERCIAL EQUIPMENT'] = 'ciencia';
		$categories['COMMUNITY'] = 'comunidade';
		$categories['COMMUNITY ORGANIZATION'] = 'comunidade';
		$categories['COMMUNITY/GOVERNMENT'] = 'comunidade';
		$categories['COMPANY'] = 'negocios';
		$categories['COMPUTERS'] = 'software';
		$categories['COMPUTERS/INTERNET WEBSITE'] = 'software';
		$categories['COMPUTERS/TECHNOLOGY'] = 'software';
		$categories['CONCERT TOUR'] = 'musica';
		$categories['CONCERT VENUE'] = 'musica';
		$categories['CONSULTING/BUSINESS SERVICES'] = 'negocios';
		$categories['CONTINENT'] = 'sabores';
		$categories['COUNTRY'] = 'sabores';
		$categories['DANCER'] = 'danca';
		$categories['DEGREE'] = 'conhecimento';
		$categories['DISEASES'] = 'biologia';
		$categories['DOCTOR'] = 'biologia';
		$categories['DRINK'] = 'comida';
		$categories['DRUGS'] = 'drugs';
		$categories['EDITOR'] = 'escrever';
		$categories['EDUCATION'] = 'conhecimento';
		$categories['EDUCATION WEBSITE'] = 'conhecimento';
		$categories['ELECTRONICS'] = 'ciencia';
		$categories['ENERGY/UTILITY'] = 'ciencia';
		$categories['ENGINEERING/CONSTRUCTION'] = 'arquitetura';
		$categories['ENTERTAINER'] = 'humor';
		$categories['ENTERTAINMENT WEBSITE'] = 'humor';
		$categories['EPISODE'] = 'tv';
		$categories['EVENT PLANNING/EVENT SERVICES'] = 'public';
		$categories['FARMING/AGRICULTURE'] = 'campo';
		$categories['FICTIONAL CHARACTER'] = 'escrever';
		$categories['FIELD OF STUDY'] = 'conhecimento';
		$categories['FOOD'] = 'comida';
		$categories['FOOD/BEVERAGES'] = 'comida';
		$categories['FOOD/GROCERY'] = 'comida';
		$categories['FURNITURE'] = 'arquitetura';
		$categories['GAMES/TOYS'] = 'software';
		$categories['GOVERNMENT OFFICIAL'] = 'politica';
		$categories['GOVERNMENT ORGANIZATION'] = 'politica';
		$categories['GOVERNMENT WEBSITE'] = 'politica';
		$categories['HEALTH/BEAUTY'] = 'biologia';
		$categories['HEALTH/MEDICAL/PHARMACEUTICALS'] = 'biologia';
		$categories['HEALTH/MEDICAL/PHARMACY'] = 'biologia';
		$categories['HEALTH/WELLNESS WEBSITE'] = 'biolgia';
		$categories['HOME DECOR'] = 'arquitetura';
		$categories['HOME IMPROVEMENT'] = 'arquitetura';
		$categories['HOME/GARDEN WEBSITE'] = 'arquitetura';
		$categories['HOSPITAL/CLINIC'] = 'biologia';
		$categories['HOTEL'] = 'sabores';
		$categories['HOUSEHOLD SUPPLIES'] = 'requinte';
		$categories['INDUSTRIALS'] = 'conhecimento';
		$categories['INSURANCE COMPANY'] = 'conhecimento';
		$categories['INTEREST'] = 'noticia';
		$categories['INTERNET/SOFTWARE'] = 'software';
		$categories['JEWELRY/WATCHES'] = 'requinte';
		$categories['JOURNALIST'] = 'escrever';
		$categories['KITCHEN/COOKING'] = 'comida';
		$categories['LANDMARK'] = 'requinte';
		$categories['LANGUAGE'] = 'escrever';
		$categories['LAWYER'] = 'politica';
		$categories['LEGAL/LAW'] = 'politica';
		$categories['LIBRARY'] = 'conhecimento';
		$categories['LOCAL BUSINESS'] = 'negocios';
		$categories['LOCAL/TRAVEL WEBSITE'] = 'sabores';
		$categories['MAGAZINE'] = 'escrever';
		$categories['MEDIA/NEWS/PUBLISHING'] = 'escrever';
		$categories['MEDICAL PROCEDURE'] = 'biologia';
		$categories['MINING/MATERIALS'] = 'ciencia';
		$categories['MONARCH'] = 'politica';
		$categories['MOVIE'] = 'cinema';
		$categories['MOVIE GENERAL'] = 'cinema';
		$categories['MOVIE GENRE'] = 'cinema';
		$categories['MOVIE THEATER'] = 'cinema';
		$categories['MOVIES/MUSIC'] = 'cinema';
		$categories['MUSEUM/ART GALLERY'] = 'arte';
		$categories['MUSIC'] = 'musica';
		$categories['MUSIC AWARD'] = 'musica';
		$categories['MUSIC CHART'] = 'musica';
		$categories['MUSIC VIDEO'] = 'musica';
		$categories['MUSICAL GENRE'] = 'musica';
		$categories['MUSICAL INSTRUMENT'] = 'musica';
		$categories['MUSICIAN/BAND'] = 'musica';
		$categories['NEIGHBORHOOD'] = 'comunidade';
		$categories['NEWS PERSONALITY'] = 'public';
		$categories['NEWS/MEDIA WEBSITE'] = 'escrever';
		$categories['NON-GOVERNMENTAL ORGANIZATION (NGO)'] = 'politica';
		$categories['NON-PROFIT ORGANIZATION'] = 'politica';
		$categories['OFFICE SUPPLIES'] = 'escrever';
		$categories['ORGANIZATION'] = 'politica';
		$categories['OTHER'] = 'website';
		$categories['OUTDOOR GEAR/SPORTING GOODS'] = 'esportes';
		$categories['PATIO/GARDEN'] = 'arquitetura';
		$categories['PERSONAL BLOG'] = 'escrever';
		$categories['PERSONAL WEBSITE'] = 'website';
		$categories['PET SERVICES'] = 'animais';
		$categories['PET SUPPLIES'] = 'animais';
		$categories['PLAYLIST'] = 'musica';
		$categories['POLITICAL IDEOLOGY'] = 'politica';
		$categories['POLITICAL ORGANIZATION'] = 'politica';
		$categories['POLITICAL PARTY'] = 'politica';
		$categories['POLITICIAN'] = 'politica';
		$categories['PRODUCER'] = 'negocios';
		$categories['PRODUCT/SERVICE'] = 'negocios';
		$categories['PROFESSIONAL SERVICES'] = 'public';
		$categories['PROFESSIONAL SPORTS TEAM'] = 'esporte';
		$categories['PUBLIC FIGURE'] = 'public';
		$categories['PUBLIC PLACES'] = 'public';
		$categories['PUBLISHER'] = 'escrever';
		$categories['RADIO STATION'] = 'musica';
		$categories['REAL ESTATE'] = 'politica';
		$categories['RECORD LABEL'] = 'musica';
		$categories['RECREATION/SPORTS WEBSITE'] = 'esporte';
		$categories['REFERENCE WEBSITE'] = 'website';
		$categories['REGIONAL WEBSITE'] = 'website';
		$categories['RELIGION'] = 'religiao';
		$categories['RESTAURANT/CAFE'] = 'comida';
		$categories['RETAIL AND CONSUMER MERCHANDISE'] = 'negocios';
		$categories['SCHOOL'] = 'conhecimento';
		$categories['SCHOOL SPORTS TEAM'] = 'esporte';
		$categories['SCIENCE WEBSITE'] = 'ciencia';
		$categories['SHOPPING/RETAIL'] = 'comunidade';
		$categories['SMALL BUSINESS'] = 'politica';
		$categories['SOCIETY/CULTURE WEBSITE'] = 'website';
		$categories['SOFTWARE'] = 'software';
		$categories['SONG'] = 'musica';
		$categories['SPAS/BEAUTY/PERSONAL CARE'] = 'saude';
		$categories['SPORT'] = 'esporte';
		$categories['SPORTS LEAGUE'] = 'esporte';
		$categories['SPORTS VENUE'] = 'esporte';
		$categories['SPORTS/RECREATION/ACTIVITIES'] = 'esporte';
		$categories['STATE/PROVINCE/REGION'] = 'politica';
		$categories['STUDIO'] = 'musica';
		$categories['TEACHER'] = 'conhecimento';
		$categories['TEENS/KIDS WEBSITE'] = 'website';
		$categories['TELECOMMUNICATION'] = 'ciencia';
		$categories['TOOLS/EQUIPMENT'] = 'ciencia';
		$categories['TOURS/SIGHTSEEING'] = 'society';
		$categories['TRANSIT STOP'] = 'sabores';
		$categories['TRANSPORT/FREIGHT'] = 'motor';
		$categories['TRANSPORTATION'] = 'motor';
		$categories['TRAVEL/LEISURE'] = 'sabores';
		$categories['TV'] = 'tv';
		$categories['TV CHANNEL'] = 'tv';
		$categories['TV GENRE'] = 'tv';
		$categories['TV NETWORK'] = 'tv';
		$categories['TV SHOW'] = 'tv';
		$categories['TV/MOVIE AWARD'] = 'tv';
		$categories['UNIVERSITY'] = 'conhecimento';
		$categories['VITAMINS/SUPPLEMENTS'] = 'drugs';
		$categories['WEBSITE'] = 'software';
		$categories['WINE/SPIRITS'] = 'comida';
		$categories['WORK POSITION'] = 'public';
		$categories['WRITER'] = 'escrever';
		
		return $categories;
	}

	public static function frases($perfil)
	{
		
		$categories = array();
		$categories['cinema'] = "<p>Um tipo Ben Afleck, que tem talento pra atuar e dirigir. Mas se liga, nesse ramo, falar inglês é fundamental.</p><p>Ou você vai fazer o seu discurso em português no Oscar?</p>";
		$categories['espaco'] = "<p>Um tipo intelectual, que não sabe o que é maior: as conexões cerebrais ou as dimensões espaciais. Mas se liga, nesse segmento, inglês é fundamental.</p><p>Ou você acha que na Nasa todo mundo fala português?</p>";
		$categories['sabores'] = "<p>Experimentar sabores inéditos e fazer da vida um guia turístico repleto de atrações. Mas se liga, tão importante quanto viajar, é entender a sua viagem.</p><p>E nada melhor que ter um idioma universal na ponta da língua para isso.</p>";
		$categories['fotografia'] = "<p>Fotos antigas, registrar o momento, eternizar um pôr do sol e folhear um álbum de fotografias. Mas se liga, no ramo da fotografia, inglês é fundamental.</p><p>Ou você vai fazer o seu discurso em português na cerimônia do Pulitzer?</p>";
		$categories['esporte'] = "<p>Até porque perder o fôlego às vezes faz bem à saúde. Ser atleta, essa é a sua. Mas se liga, nas Olimpíadas, o que mais tem é gente falando em inglês. </p><p>Trate logo de aprender.</p>";
		$categories['biologia'] = "<p>Mas se liga, quase todos os artigos científicos são publicados em inglês. </p><p>Trate logo de aprender.</p>";
		$categories['animais'] = "<p>Mas se liga, o que mais tem é papagaio falando inglês hoje em dia.</p><p>Trate logo de aprender.</p>";
		$categories['software'] = "<p>E como já sabe, inglês nessa área é fundamental. </p><p>Ou você já viu algum sistema operacional chamado Janelas?</p>";
		$categories['ciencia'] = "<p>Um tipo intelectual, que sabe o quanto a telecomunicação, assim como o inglês, aproxima distâncias. </p>";
		$categories['arte'] = "<p>Vez e outra, você também expressa seus sentimentos por meio desse idioma chamado arte.</p><p>Mas já pensou o quanto seria inspirador se expressar por meio de outro idioma, também universal? </p>";
		$categories['esportes'] = "<p>Até porque perder o fôlego às vezes faz bem à saúde. Ser atleta, essa é a sua.Mas se liga, nas Olimpíadas, o que mais tem é gente falando em inglês.</p><p>Trate logo de aprender.</p>";
		$categories['conhecimento'] = "<p>Você nunca se dá por satisfeito. Quer sempre saber mais. E está certo. Novos dados, novas formas, novos conceitos.</p><p>E para continuar nessa sede por conhecimento, nada melhor que um idioma universal para abrir novos horizontes.</p>";
		$categories['motor'] = "<p>É do tipo que sabe o nome de todos os carros. Inclusive os que ainda não foram inventados.</p><p>Mas me diga, além do ronco do motor, já pensou em aprender outro idioma universal?</p>";
		$categories['comunidade'] = "<p>Você é aquele que faz a diferença, busca uma sociedade melhor e não mede esforços em prol da comunidade. O que você faz pelo mundo precisa ser levado para esse mesmo mundo.</p><p>E não tem ferramenta social para isso melhor que o inglês.</p>";
		$categories['negocios'] = "<p>Além dos negócios, já pensou em investir em outro idioma universal?</p><p>Segundo especialistas, o inglês está em alta.</p>";
		$categories['escrever'] = "<p>Seu sobrenome ainda não é Assis, Lispector ou Saramago, mas você tem talento para chegar lá. E chegar ainda mais longe, caso tenha seus livros traduzidos para o inglês.</p><p>Já pensou em escrever nesse idioma universal?</p>";
		$categories['arquitetura'] = "<p>Você tem a sensibilidade de ver além da simples forma. Seu idioma é a engenharia.</p><p>Melhor seria traduzir tudo o que pensa para o inglês, assim, mais pessoas compartilhariam das suas ideias.</p>";;
		$categories['comida'] = "<p>Você é um amante gastronômico. Sabe que para tudo nessa vida tem uma receita. Inclusive para o sucesso.</p><p>E por falar nisso, como vai o seu inglês?</p>";;
		$categories['drugs'] = "<p>Ninguém fica tão feliz quanto você ao ver uma tabela periódica. Química é seu idioma.</p><p>Mas, para que o mundo inteiro saiba desse seu conhecimento, seria interessante aprender outro idioma também, tão universal quanto a química.</p>";
		$categories['religiao'] = "<p>Saber mais sobre a igreja e suas peculiaridades é o que inspira você. Até mesmo um pouco de latim figura no seu conhecimento.</p><p>Já pensou em ampliar suas fontes de pesquisa sabendo, além do latim, o inglês?</p>";
		$categories['moda'] = "<p>Você sabe quando uma cor não conversa com a outra, quando uma estampa não conversa com a outra.</p><p>Mas e você, tem inglês o suficiente para conversar com o restante do mundo?</p>";
		$categories['eventos'] = "<p>Você é do tipo que faz acontecer. Transforma o dia em comemoração e traz mais brilho para qualquer data.</p><p>É atento aos detalhes e sabe que para um futuro promissor, existe um detalhe fundamental: saber inglês.</p>";
		$categories['humor'] = "<p>Para você, não tem tempo ruim. Humor é o idioma que faz tudo ficar bem. Já pensou em levar essa sua alegria para o mundo inteiro?</p><p>Se a resposta é sim, o primeiro passo seria falar uma língua universal, além do humor, é claro.</p>";
		$categories['danca'] = "<p>Dança, você é a dança em pessoa. Tem ritmo e leva à vida no compasso do instante. Vai além daqueles “dois pra lá, dois pra cá” e traz encanto quando se apresenta.</p><p>Como já sabe, o que mais tem é nome de passo de dança em inglês. Por isso, minha pergunta é: você só dança ou também entende?</p>";
		$categories['tv'] = "<p>Você é a programação televisiva em pessoa. Sabe da novela de ontem, do programa de amanhã e do filme de hoje.</p><p>Sabe também que os melhores programas são americanos e que dublagem está fora de moda.</p>";
		$categories['public'] = "<p>Você está por dentro de tudo que Nicole Kidman comeu no almoço e do que Justin Bieber disse antes de dormir. Sua especialidade é o universo das celebridades.</p><p>Celebridades essas que falam todas em inglês. E você?</p>";
		$categories['campo'] = "<p>Como todo agricultor, você defende que é plantando que se colhe. E seu raciocínio está certinho.</p><p>Quem investe em inglês, colhe bons frutos no futuro. </p>";
		$categories['politica'] = "<p>E para ficar por dentro de tudo que acontece na política, saber o idioma da maioria dos discursos do mundo é fundamental.</p><p>Falando nisso, como vai o seu inglês?</p>";
		$categories['requinte'] = "<p>Você sabe que um relógio ou jóia, além de obras de arte, são formas de investimento.</p><p>Assim como saber inglês, que também é uma forma de investir em um futuro promissor. </p>";
		$categories['noticia'] = "<p>Você é do tipo que busca estar sempre por dentro de tudo. Gosta de saber o que acontece no Brasil e no mundo.</p><p>E por falar em mundo, como vai a sua comunicação com ele? Falam o mesmo idioma?</p>";
		$categories['website'] = "<p>Até porque perder o fôlego às vezes faz bem à saúde. Ser atleta, essa é a sua.Mas se liga, nas Olimpíadas, o que mais tem é gente falando em inglês.</p><p>Trate logo de aprender</p>";
		$categories['saude'] = "<p>Você é a boa forma, sabe que saúde só pode fazer bem. E por isso faz a sua parte. Agora, uma dica: você sabia que aprender um novo idioma também faz bem?</p><p>Sim, faz bem ao seu futuro.</p>";
		$categories['society'] = "<p>Você mescla um universo inteiro a partir do seu computador. Elimina fronteiras e convida mais cultura para os seus contatos.</p><p>Sua essência lembra muito a do idioma inglês: aproximar a tudo.</p>";
		$categories['musica'] = "<p>Você é do tipo que tem uma trilha sonora para cada instante do dia. É fácil te ver cantarolando algo.</p><p>E para que novos ritmos entrem na sua playlist, nada melhor que aprender inglês. E sair cantando por aí.</p>";
	
		if(array_key_exists($perfil, $categories))
		{
			$ret = $categories[$perfil];
		}	
		else 
		{
			$ret = "";	
		}	
	
		return $ret;
	}

	private static function typesLike()
	{
		$types = array();
		
		$types['ACTOR/DIRECTOR'] = 0;
		$types['AEROSPACE/DEFENSE'] = 0;
		$types['AIRPORT'] = 0;
		$types['ALBUM'] = 0;
		$types['AMATEUR SPORTS TEAM'] = 0;
		$types['ANATOMICAL STRUCTURE'] = 0;
		$types['ANIMAL'] = 0;
		$types['ANIMAL BREED'] = 0;
		$types['APP'] = 0;
		$types['APP PAGE'] = 0;
		$types['APPLIANCES'] = 0;
		$types['ARTIST'] = 0;
		$types['ARTS/ENTERTAINMENT/NIGHTLIFE'] = 0;
		$types['ARTS/HUMANITIES WEBSITE'] = 0;
		$types['ATHLETE'] = 0;
		$types['ATTRACTIONS/THINGS TO DO'] = 0;
		$types['AUTHOR'] = 0;
		$types['AUTOMOBILES AND PARTS'] = 0;
		$types['AUTOMOTIVE'] = 0;
		$types['BABY GOODS/KIDS GOODS'] = 0;
		$types['BAGS/LUGGAGE'] = 0;
		$types['BANK/FINANCIAL INSTITUTION'] = 0;
		$types['BANK/FINANCIAL SERVICES'] = 0;
		$types['BAR'] = 0;
		$types['BIOTECHNOLOGY'] = 0;
		$types['BOOK'] = 0;
		$types['BOOK GENRE'] = 0;
		$types['BOOK STORE'] = 0;
		$types['BUILDING MATERIALS'] = 0;
		$types['BUSINESS PERSON'] = 0;
		$types['BUSINESS SERVICES'] = 0;
		$types['BUSINESS/ECONOMY WEBSITE'] = 0;
		$types['CAMERA/PHOTO'] = 0;
		$types['CARS'] = 0;
		$types['CAUSE'] = 0;
		$types['CHEF'] = 0;
		$types['CHEMICALS'] = 0;
		$types['CHURCH/RELIGIOUS ORGANIZATION'] = 0;
		$types['CITY'] = 0;
		$types['CLOTHING'] = 0;
		$types['CLUB'] = 0;
		$types['COACH'] = 0;
		$types['COLOR'] = 0;
		$types['COMEDIAN'] = 0;
		$types['COMMERCIAL EQUIPMENT'] = 0;
		$types['COMMUNITY'] = 0;
		$types['COMMUNITY ORGANIZATION'] = 0;
		$types['COMMUNITY/GOVERNMENT'] = 0;
		$types['COMPANY'] = 0;
		$types['COMPUTERS'] = 0;
		$types['COMPUTERS/INTERNET WEBSITE'] = 0;
		$types['COMPUTERS/TECHNOLOGY'] = 0;
		$types['CONCERT TOUR'] = 0;
		$types['CONCERT VENUE'] = 0;
		$types['CONSULTING/BUSINESS SERVICES'] = 0;
		$types['CONTINENT'] = 0;
		$types['COUNTRY'] = 0;
		$types['DANCER'] = 0;
		$types['DEGREE'] = 0;
		$types['DISEASES'] = 0;
		$types['DOCTOR'] = 0;
		$types['DRINK'] = 0;
		$types['DRUGS'] = 0;
		$types['EDITOR'] = 0;
		$types['EDUCATION'] = 0;
		$types['EDUCATION WEBSITE'] = 0;
		$types['ELECTRONICS'] = 0;
		$types['ENERGY/UTILITY'] = 0;
		$types['ENGINEERING/CONSTRUCTION'] = 0;
		$types['ENTERTAINER'] = 0;
		$types['ENTERTAINMENT WEBSITE'] = 0;
		$types['EPISODE'] = 0;
		$types['EVENT PLANNING/EVENT SERVICES'] = 0;
		$types['FARMING/AGRICULTURE'] = 0;
		$types['FICTIONAL CHARACTER'] = 0;
		$types['FIELD OF STUDY'] = 0;
		$types['FOOD'] = 0;
		$types['FOOD/BEVERAGES'] = 0;
		$types['FOOD/GROCERY'] = 0;
		$types['FURNITURE'] = 0;
		$types['GAMES/TOYS'] = 0;
		$types['GOVERNMENT OFFICIAL'] = 0;
		$types['GOVERNMENT ORGANIZATION'] = 0;
		$types['GOVERNMENT WEBSITE'] = 0;
		$types['HEALTH/BEAUTY'] = 0;
		$types['HEALTH/MEDICAL/PHARMACEUTICALS'] = 0;
		$types['HEALTH/MEDICAL/PHARMACY'] = 0;
		$types['HEALTH/WELLNESS WEBSITE'] = 0;
		$types['HOME DECOR'] = 0;
		$types['HOME IMPROVEMENT'] = 0;
		$types['HOME/GARDEN WEBSITE'] = 0;
		$types['HOSPITAL/CLINIC'] = 0;
		$types['HOTEL'] = 0;
		$types['HOUSEHOLD SUPPLIES'] = 0;
		$types['INDUSTRIALS'] = 0;
		$types['INSURANCE COMPANY'] = 0;
		$types['INTEREST'] = 0;
		$types['INTERNET/SOFTWARE'] = 0;
		$types['JEWELRY/WATCHES'] = 0;
		$types['JOURNALIST'] = 0;
		$types['KITCHEN/COOKING'] = 0;
		$types['LANDMARK'] = 0;
		$types['LANGUAGE'] = 0;
		$types['LAWYER'] = 0;
		$types['LEGAL/LAW'] = 0;
		$types['LIBRARY'] = 0;
		$types['LOCAL BUSINESS'] = 0;
		$types['LOCAL/TRAVEL WEBSITE'] = 0;
		$types['MAGAZINE'] = 0;
		$types['MEDIA/NEWS/PUBLISHING'] = 0;
		$types['MEDICAL PROCEDURE'] = 0;
		$types['MINING/MATERIALS'] = 0;
		$types['MONARCH'] = 0;
		$types['MOVIE'] = 0;
		$types['MOVIE GENERAL'] = 0;
		$types['MOVIE GENRE'] = 0;
		$types['MOVIE THEATER'] = 0;
		$types['MOVIES/MUSIC'] = 0;
		$types['MUSEUM/ART GALLERY'] = 0;
		$types['MUSIC'] = 0;
		$types['MUSIC AWARD'] = 0;
		$types['MUSIC CHART'] = 0;
		$types['MUSIC VIDEO'] = 0;
		$types['MUSICAL GENRE'] = 0;
		$types['MUSICAL INSTRUMENT'] = 0;
		$types['MUSICIAN/BAND'] = 0;
		$types['NEIGHBORHOOD'] = 0;
		$types['NEWS PERSONALITY'] = 0;
		$types['NEWS/MEDIA WEBSITE'] = 0;
		$types['NON-GOVERNMENTAL ORGANIZATION (NGO)'] = 0;
		$types['NON-PROFIT ORGANIZATION'] = 0;
		$types['OFFICE SUPPLIES'] = 0;
		$types['ORGANIZATION'] = 0;
		$types['OTHER'] = 0;
		$types['OUTDOOR GEAR/SPORTING GOODS'] = 0;
		$types['PATIO/GARDEN'] = 0;
		$types['PERSONAL BLOG'] = 0;
		$types['PERSONAL WEBSITE'] = 0;
		$types['PET SERVICES'] = 0;
		$types['PET SUPPLIES'] = 0;
		$types['PLAYLIST'] = 0;
		$types['POLITICAL IDEOLOGY'] = 0;
		$types['POLITICAL ORGANIZATION'] = 0;
		$types['POLITICAL PARTY'] = 0;
		$types['POLITICIAN'] = 0;
		$types['PRODUCER'] = 0;
		$types['PRODUCT/SERVICE'] = 0;
		$types['PROFESSIONAL SERVICES'] = 0;
		$types['PROFESSIONAL SPORTS TEAM'] = 0;
		$types['PUBLIC FIGURE'] = 0;
		$types['PUBLIC PLACES'] = 0;
		$types['PUBLISHER'] = 0;
		$types['RADIO STATION'] = 0;
		$types['REAL ESTATE'] = 0;
		$types['RECORD LABEL'] = 0;
		$types['RECREATION/SPORTS WEBSITE'] = 0;
		$types['REFERENCE WEBSITE'] = 0;
		$types['REGIONAL WEBSITE'] = 0;
		$types['RELIGION'] = 0;
		$types['RESTAURANT/CAFE'] = 0;
		$types['RETAIL AND CONSUMER MERCHANDISE'] = 0;
		$types['SCHOOL'] = 0;
		$types['SCHOOL SPORTS TEAM'] = 0;
		$types['SCIENCE WEBSITE'] = 0;
		$types['SHOPPING/RETAIL'] = 0;
		$types['SMALL BUSINESS'] = 0;
		$types['SOCIETY/CULTURE WEBSITE'] = 0;
		$types['SOFTWARE'] = 0;
		$types['SONG'] = 0;
		$types['SPAS/BEAUTY/PERSONAL CARE'] = 0;
		$types['SPORT'] = 0;
		$types['SPORTS LEAGUE'] = 0;
		$types['SPORTS VENUE'] = 0;
		$types['SPORTS/RECREATION/ACTIVITIES'] = 0;
		$types['STATE/PROVINCE/REGION'] = 0;
		$types['STUDIO'] = 0;
		$types['TEACHER'] = 0;
		$types['TEENS/KIDS WEBSITE'] = 0;
		$types['TELECOMMUNICATION'] = 0;
		$types['TOOLS/EQUIPMENT'] = 0;
		$types['TOURS/SIGHTSEEING'] = 0;
		$types['TRANSIT STOP'] = 0;
		$types['TRANSPORT/FREIGHT'] = 0;
		$types['TRANSPORTATION'] = 0;
		$types['TRAVEL/LEISURE'] = 0;
		$types['TV'] = 0;
		$types['TV CHANNEL'] = 0;
		$types['TV GENRE'] = 0;
		$types['TV NETWORK'] = 0;
		$types['TV SHOW'] = 0;
		$types['TV/MOVIE AWARD'] = 0;
		$types['UNIVERSITY'] = 0;
		$types['VITAMINS/SUPPLEMENTS'] = 0;
		$types['WEBSITE'] = 0;
		$types['WINE/SPIRITS'] = 0;
		$types['WORK POSITION'] = 0;
		$types['WRITER'] = 0;
		
		return $types;
	}
	
	private static function calculaPerfil($likes_data)
	{
		$types = self::typesLike();
		$categories = self::categoriesLike();
			
		$userTypes = array();
		foreach ($likes_data as $like) {
			$upper = strtoupper($like['type']);
			if(key_exists($upper, $types))
			{
				if(key_exists($upper, $userTypes))
				{
					$userTypes[$upper] = $userTypes[$upper] + 1;
				} else 
				{
					$userTypes[$upper] = 1;
				}
			} else 
			{
				continue;	
			}
		}

		if(count($userTypes) == 0)
		{
			$userTypes['LIBRARY'] = 1000;
		}

		arsort($userTypes);
		$result = array();
		for($i = 0; $i < 5; $i++)
		{
			$first = key($userTypes);
			array_push($result,$first);
			next($userTypes);
		}
		
		shuffle($result);
//		var_dump($result);
		$perfil = array_shift($result);
		$ret = $categories[$perfil];
		//var_dump($ret);

		return $ret;
	}

	/*
	 * retorno: cat(categoria)//, qt(quantidade)
	 */
	public static function calculaPerfilCultural($likes_data) {
		$perfil = self::calculaPerfil($likes_data);
		$sex = self::userSex($uid);
		if($sex != 'male')
		{
			if($perfil == 'negocios' || $perfil == 'comida')
			{
				$str = '-m';
				if($perfil == 'negocios')
				{
					$r = rand(1, 2);
					$str = $str.$r;
				}
				$perfil = $perfil.$str;
				
			}
		}
		return $perfil;
		//return 'conhecimento';
		 /*
		$types = self::typesLike();
		$categories = self::categoriesLike();
		
		$userTypes = array();
		$userCats = array();
		foreach ($likes_data as $like) {
			$upper = strtoupper($like['type']);
			if(key_exists($upper, $types))
			{
				if(key_exists($upper, $userTypes))
				{
					$userTypes[$upper] += 1;	
				} else $userTypes[$upper] = 1;
				
				if(!key_exists($categories[$upper], $userCats))
				{
					$userCats[$categories[$upper]] = 1;	
				} //else $userCats[$categories[$upper]] = 1;
				
			}
			
		}
		
		arsort($userTypes);
		
		foreach ($userTypes as $key => $value) {
			
				//echo $key.' '.$value.'<br/>';
				if($categories[$key] == 'OUTROS')
					$userCats[$categories[$key]] -= 1;
				else $userCats[$categories[$key]] += 1;
			
			
		}
		
		arsort($userCats);
		
		$cats = array();
		$cont = 0;
		foreach ($userCats as $key=>$value) {
			if($cont == 5) break;
			if($key != "OUTROS")
			{
				array_push($cats,$key);
			}
			$cont++;
							
		}
		shuffle($cats);
		return $cats;
		 */
	}

	/*
	 * Calcula fluencia baseada nos likes
	 * */
	 // doc: http://pear.php.net/package/Text_LanguageDetect/docs/latest/Text_LanguageDetect/Text_LanguageDetect.html#methoddetect
	 // install pearl: http://pear.php.net/manual/pt_BR/guide.users.commandline.installing.php
	 // fix bug: https://drupal.org/node/172355
	public static function calculaFluenciaLikes($likes)
	{
		
		$l = new Text_LanguageDetect();
		$l->setNameMode(2);
		
		$en = 0;
		foreach ($likes as $like) {
			$like_name = $like["name"];
			
			$r = $l->detectConfidence($like_name);
			
			/*if (PEAR::isError($r)) {
			    echo $r->getMessage();
			} else {
				echo $like_name.' '.$r['language'];
			    //print_r($r);
				echo "<br/>";
			}*/
					
			if($r['language'] != 'pt')
			{
				++$en;
			}
		
			
		}
		
		$total = count($likes);
		if($total == 0) $total = 1;
		$en = $en * 0.8;
		//echo '<br/>'.$en.' '.$total.' : '.($en/$total).'<br/>';
		return $en/$total;
			/*
		$real_likes = $likes['likes']['data'];
		//var_dump( $real_likes );
		
		$count = 0;
		$engCount = 0;
		foreach ($real_likes as $l) {
			if(array_key_exists('name', $l))
			{
				$str = $l['name'];
				$txt = self::idiomaTexto($str.' '.$str.' '.$str.' '.$str.' '.$str);
				$jsonLang = json_decode($txt);
	
				echo $l['name'].' '.$jsonLang->{'iso-639-1'} .'<br/>';
				$count++;
	
				if ($jsonLang -> {'iso-639-1'} == 'en') {
					//echo $key['message'].' '.$jsonLang->{'iso-639-1'}.'<br/>';
					++$engCount;
				}
			
			}
		}
		if($count > 0)
			return $engCount / $count;
		else return 0;*/
		
	}	 

	/*
	 * Calcula fluencia baseada nas postagens
	 * */
	public static function calculaFluencia($posts) {
		$l = new Text_LanguageDetect();
		$l->setNameMode(2);
		
		$en = 0;
		foreach ($posts as $post) {
			$post_name = $post["message"];
			
			$r = $l->detectConfidence($post_name);
			
			/*if (PEAR::isError($r)) {
			    echo $r->getMessage();
			} else {
				echo $like_name.' '.$r['language'];
			    //print_r($r);
				echo "<br/>";
			}*/
					
			if($r['language'] != 'pt')
			{
				++$en;
			}
		
			
		}
		
		$total = count($posts);
		if($total == 0) $total = 1;
		//$en = $en * 0.8;
		//echo '<br/>'.$en.' '.$total.' : '.($en/$total).'<br/>';
		return $en/$total;
	}

	public static function userSex()
	{
		$dao = $_SESSION['fbDAO'];
		$ret = $dao->userSex();
		return $ret;
	}

	public static function userLike($uid)
	{
		$dao = $_SESSION['fbDAO'];
		$ret = $dao->userLike($uid);
		return sizeof($ret);
	}

	public static function getMe()
	{
		$dao = $_SESSION['fbDAO'];
		$me = $dao->getMe();
		return $me; 
	}

	public static function uploadPhoto($msg, $path, $tags)
	{
		$dao = $_SESSION['fbDAO'];
		
		
		return $dao->uploadPhoto($msg,$path, $tags);
	}
	
	public function tagPhoto($photo_id, $ids)
	{
		$dao = $_SESSION['fbDAO'];
		$dao->tagPhoto($photo_id,$ids);
	}

	public function getAvatar($w, $h)
	{
		getAvatar($id, $w, $h);
	}

}

//echo AppCore::getLocation();

//echo AppCore::idiomaTexto("the book is on the table");
//var_dump(AppCore::idiomaTexto("hoje é um bom dia"));
?>