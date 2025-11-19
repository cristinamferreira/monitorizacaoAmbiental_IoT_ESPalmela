<?php
include("ligacaoBD.php");

$json = file_get_contents('php://input');
$data = json_decode($json, true);

file_put_contents("debug_log.txt", date("Y-m-d H:i:s") . " - Recebido: " . $json . PHP_EOL, FILE_APPEND);
$debugData = [
    "data_hora" => date("Y-m-d H:i:s"),
    "json_recebido" => $data
];

file_put_contents("debug.json", json_encode($debugData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

if (isset($data['uplink_message']['decoded_payload'])) {

    $payload = $data['uplink_message']['decoded_payload'];

    $latitude = 38.57162;
    $longitude = -8.90927;
    $altitude = 102;

    $stmt = $conn->prepare("INSERT INTO monitorizacao 
            (temperatura, pressao, humidade, gas, lux, latitude, longitude, altitude, particulas, sismo_si, sismo_pga) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        file_put_contents("queru_insert.txt", date("Y-m-d H:i:s") . " - Erro no prepare: " . $conn->error . PHP_EOL, FILE_APPEND);
        exit;
    }
    $stmt->bind_param(
        "ddddddddddd",
        $payload['Temp'],
        $payload['Pressure'],
        $payload['Humidity'],
        $payload['Gas'],
        $payload['Light'],
        $latitude,
        $longitude,
        $altitude,
        $payload['Particulas'],
        $payload['Sismo_SI'],
        $payload['Sismo_PGA']
    );

    if ($stmt->execute()) {
        echo json_encode(['status' => 'sucesso', 'message' => 'Dados inseridos com sucesso.']);
    } else {
        echo json_encode(['status' => 'erro', 'message' => 'Erro ao inserir no banco de dados.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'erro', 'message' => 'decoded_payload não encontrado']);
}

$conn->close();
