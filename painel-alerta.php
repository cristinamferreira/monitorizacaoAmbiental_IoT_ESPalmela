<?php
$fich = 'dados_alerta.json';

if (!isset($_GET['tipo'])) {
    http_response_code(400);
    echo json_encode(["erro" => "Tipo de sensor não especificado."]);
    exit;
}

$tipo = $_GET['tipo'];
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

if (!isset($input['value'])) {
    http_response_code(400);
    echo json_encode(["erro" => "Valor não recebido."]);
    exit;
}

$valor = $input['value'];

$dados = [];
if (file_exists($fich)) {
    $conteudo = file_get_contents($fich);
    $dados = json_decode($conteudo, true) ?: [];
}

$dados[$tipo] = [
    'valor' => $valor,
    'timestamp' => date('Y-m-d H:i:s')
];

file_put_contents($fich, json_encode($dados, JSON_PRETTY_PRINT));

echo json_encode(["sucesso" => true, "tipo" => $tipo, "valor" => $valor]);
