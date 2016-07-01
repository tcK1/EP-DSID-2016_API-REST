<?php
header('Content-Type: text/html; charset=utf-8');
echo '<pre>';

//print_r($_SERVER);

echo '-------------------------------BuscaCompras-------------------------------</br>';

$curl = curl_init();
    
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://distribuidosrest-ztck.c9users.io/webservice?id=12',
    CURLOPT_USERAGENT => 'Codular Sample cURL Request'
));

$resp = curl_exec($curl);
curl_close($curl);

echo '**********************Resposta*************************';

var_dump($resp);

echo '**********************XML Lido*************************';

$xml = simplexml_load_string($resp, "SimpleXMLElement", LIBXML_NOCDATA);
var_dump($xml);

echo '**********************Array Lido*************************</br>';

$json = json_encode($xml);
$array = json_decode($json,TRUE);
print_r($array);

echo '-------------------------------InsereCompra-------------------------------</br>';

$curl = curl_init();
    
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://distribuidosrest-ztck.c9users.io/webservice',
    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
    CURLOPT_POST => 1,
    CURLOPT_POSTFIELDS => array( 
        'id' => 12,
        'detalhes' => 'Viagem: LGW para CDG: 190.45832;Viagem: LGW para CDG: 190.45832;Hotel: Littre: 600.0475;Carro: Mercedes E Class: 2409.9205;',
        'preco' => 3390.88
        )
));

$resp = curl_exec($curl);
curl_close($curl);

echo '**********************Resposta*************************';

var_dump($resp);

echo '**********************XML Lido*************************';

$xml = simplexml_load_string($resp, "SimpleXMLElement", LIBXML_NOCDATA);
var_dump($xml);

echo '**********************Array Lido*************************</br>';

$json = json_encode($xml);
$array = json_decode($json,TRUE);
print_r($array);


echo '-------------------------------AtualizaCompra-------------------------------</br>';
$id = $array[mensagem];

$data = array( 
        'id' => $id
        );

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://distribuidosrest-ztck.c9users.io/webservice',
    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
    CURLOPT_CUSTOMREQUEST => "PUT",
    CURLOPT_POSTFIELDS => http_build_query($data)
));

$resp = curl_exec($curl);
curl_close($curl);

echo '**********************Resposta*************************';

var_dump($resp);

echo '**********************XML Lido*************************';

$xml = simplexml_load_string($resp, "SimpleXMLElement", LIBXML_NOCDATA);
var_dump($xml);

echo '**********************Array Lido*************************</br>';

$json = json_encode($xml);
$array = json_decode($json,TRUE);
print_r($array);


echo '</pre>';
