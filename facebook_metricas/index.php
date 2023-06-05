<?php
//Simple HTML DOM
require '../../inc/dom_parser/simple_html_dom.php';

//URL
$url = 'https://www.facebook.com/fiatmavel';

//Cria parametros do HEAD
$parametros = array(
	"http" => [
		"method" => "GET",
		"header" => "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7\r\n" .
					"accept-language: pt-BR,pt;q=0.9\r\n" .
					"cache-control: max-age=0\r\n" .
					"sec-ch-prefers-color-scheme: dark\r\n" .
					"sec-ch-ua: \"Google Chrome\";v=\"113\", \"Chromium\";v=\"113\", \"Not-A.Brand\";v=\"24\"\r\n" .
					"sec-ch-ua-full-version-list: \"Google Chrome\";v=\"113.0.5672.129\", \"Chromium\";v=\"113.0.5672.129\", \"Not-A.Brand\";v=\"24.0.0.0\"\r\n" .
					"sec-ch-ua-mobile: ?0\r\n" .
					"sec-ch-ua-platform: \"Windows\"\r\n" .
					"sec-ch-ua-platform-version: \"10.0.0\"\r\n" .
					"sec-fetch-dest: document\r\n" .
					"sec-fetch-mode: navigate\r\n" .
					"sec-fetch-site: same-origin\r\n" .
					"sec-fetch-user: ?1\r\n" .
					"upgrade-insecure-requests: 1\r\n" .
					"viewport-width: 1011\r\n" .
					"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36"
	]
);

//HTML
$html = file_get_html($url, false, stream_context_create($parametros));

if(http_response_code() > 299){
	//Apresenta erro
	exit('Métricas não foram coletadas pois houve um erro.');
} else{
	//Apresenta dados
	//echo $html;
}

//Pega texto bruto
$plaintext = '';

foreach($html->find('script') as $el){
	//Concatena no texto
	$plaintext .= $el;
}

//Regex
$preg_likes  = preg_match('/([0-9],?[0-9]*).{0,6}([a-z]*) curtidas/', $plaintext, $mt_likes);
$preg_follow = preg_match('/([0-9],?[0-9]*).{0,6}([a-z]*) seguidores/', $plaintext, $mt_follow);

//Likes
if($preg_likes === 1){
	if(preg_match('/[^0-9]/', $mt_likes[1]) === 1){
		$likes = preg_replace('/[^0-9]/', '', $mt_likes[1]);
		$qtd   = (!empty($mt_likes[2]) ? ($mt_likes[2] == 'mil' ? 100 : 100000) : 1);
	} else{
		$likes  = $mt_likes[1];
		$qtd    = (!empty($mt_likes[2]) ? ($mt_likes[2] == 'mil' ? 1000 : 1000000) : 1);
	}
	
	//Pega likes do perfil
	$likes = $likes * $qtd;
} else{
	$likes = 0;
}

//Seguidores
if($preg_follow === 1){
	if(preg_match('/[^0-9]/', $mt_follow[1]) === 1){
		$follow = preg_replace('/[^0-9]/', '', $mt_follow[1]);
		$qtd    = (!empty($mt_follow[2]) ? ($mt_follow[2] == 'mil' ? 100 : 100000) : 1);
	} else{
		$follow = $mt_follow[1];
		$qtd    = (!empty($mt_follow[2]) ? ($mt_follow[2] == 'mil' ? 1000 : 1000000) : 1);
	}
	
	//Pega likes do perfil
	$follow = $follow * $qtd;
} else{
	$follow = 0;
}

//Apresenta métricas
echo '<h2>FACEBOOK - MÉTRICAS</h2><hr>';
echo '<p>Empresa: Fiat Mavel</p>';
echo '<p>Likes: '.$likes.'</p>';
echo '<p>Seguidores: '.$follow.'</p>';