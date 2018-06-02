<?php

header('Content-Type: application/json');

$http_origin = $_SERVER['HTTP_ORIGIN'];
$server = 'http://' . $_SERVER['HTTP_HOST'];

if ($http_origin == "http://localhost:8080")
{  
    $server = $http_origin;
}

// CORS
header('Access-Control-Max-Age: 1000');
header("Access-Control-Allow-Origin: {$server}");
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');

$save = 'https://web.archive.org/save/';
$data = file_get_contents('php://input');
$url = null;

if($data) {
    $data = json_decode($data);
    $url = $data->url;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!empty($url)) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_URL, $save.$url);

        $header = curl_exec($ch);

        curl_close($ch);

        $headers = preg_split("/((\r?\n)|(\r\n?))/", $header);

        $url = null;

        foreach($headers as $line) {
            if(strpos($line, 'Content-Location') !== false) {
                $url = str_replace('Content-Location: ', '', $line);
                break;
            }
        }

        if($url) {
            http_response_code(200);
            echo json_encode(array(
                'url' => $url,
                'ip' => $_SERVER['REMOTE_ADDR']
            ), JSON_PRETTY_PRINT);
        } else {
            http_response_code(500);
            echo json_encode(array(
                'message' => 'Erro ao gerar o snapshot da url informada'
            ), JSON_PRETTY_PRINT);
        }

    } else {
        http_response_code(400);
        echo json_encode(array(
            'message' => 'Favor informar a url'
        ), JSON_PRETTY_PRINT);
    }

} else {
    if($_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
        http_response_code(405);
        echo json_encode(array(
            'message' => 'Requisição não permitida'
        ), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}