<?php

function make_bitly_url($url,$login,$appkey,$format = 'xml',$version = '2.0.1')
{
	//create the URL
	$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;

	//get the url
	//could also use cURL here
	$response = file_get_contents($bitly);

	//parse depending on desired format
	if(strtolower($format) == 'json')
	{
		$json = @json_decode($response,true);
		return $json['results'][$url]['shortUrl'];
	}
	else //xml
	{
		$xml = simplexml_load_string($response);
		return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
	}
}

if(isset($_POST['link']))
{
//echo file_get_contents($_POST['fuck']);
$id = explode('/', $_POST['link']);

//make_bitly_url($mp3_320k,'davidwalshblog','R_96acc320c5c423e4f5192e006ff24980','json');
//make_bitly_url($mp3_lossless,'davidwalshblog','R_96acc320c5c423e4f5192e006ff24980','json');
	$id = explode('.html', $id[5]);
	$api = 'http://api.mp3.zing.vn/api/mobile/song/getsonginfo?keycode=fafd463e2131914934b73310aa34a23f&requestdata={"id":"'.$id[0].'"}';
	$get = file_get_contents($api);
	preg_match('/"128":"(.*)",/U', $get, $mp3_128k);
	$mp3_128k = str_replace('\/', '/', $mp3_128k[1]);

	preg_match('/"320":"(.*)"}/U', $get, $mp3_320k);
	$mp3_320k = str_replace('\/', '/', $mp3_320k[1]);


	preg_match('/"lossless":"(.*)",/U', $get, $mp3_lossless);
	$mp3_lossless = str_replace('\/', '/', $mp3_lossless[1]);
	$mp3_128k = make_bitly_url($mp3_128k,'davidwalshblog','R_96acc320c5c423e4f5192e006ff24980','json');
	$mp3_320k = make_bitly_url($mp3_320k,'davidwalshblog','R_96acc320c5c423e4f5192e006ff24980','json');
	//$mp3_lossless = make_bitly_url($mp3_lossless,'davidwalshblog','R_96acc320c5c423e4f5192e006ff24980','json');

	echo '128K: '.$mp3_128k.'<br>320K: '.$mp3_320k;

}

?>
