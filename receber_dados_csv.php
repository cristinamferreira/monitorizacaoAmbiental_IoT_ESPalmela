<?php

$csvFile = __DIR__ . '/dados_ttn.csv';

if (!file_exists($csvFile)) {
    http_response_code(500);
    echo json_encode(['error' => 'Ficheiro CSV não encontrado']);
    exit;
}

$rows = [];
if (($h = fopen($csvFile, 'r')) !== false) {
    $header = fgetcsv($h);
    while (($line = fgetcsv($h)) !== false) {
        // Associa cada valor ao respetivo cabeçalho
        $rows[] = array_combine($header, $line);
    }
    fclose($h);
}

if (empty($rows)) {
    echo json_encode([
        'historico' => [],
        'ultimaLeitura' => null,
        'dadosSemanal'  => [],
        'dadosMensal'   => []
    ]);
    exit;
}

$ultimos12 = array_slice($rows, -12);

$ultima = end($rows);

$groupSemana = [];
foreach ($rows as $r) {
    $dt = new DateTime($r['data_hora']);
    $week = $dt->format("oW");
    $groupSemana[$week][] = $r;
}
$dadosSemanal = [];
foreach ($groupSemana as $week => $group) {
    // calcula médias
    $soma = array_fill_keys(
        ['Temp', 'Pressure', 'Humidity', 'Gas', 'Light', 'Particulas', 'Sismo_SI', 'Sismo_PGA'],
        0
    );
    foreach ($group as $r) {
        $soma['Temp']      += (float)$r['Temp'];
        $soma['Pressure']  += (float)$r['Pressure'];
        $soma['Humidity']  += (float)$r['Humidity'];
        $soma['Gas']       += (float)$r['Gas'];
        $soma['Light']     += (float)$r['Light'];
        $soma['Particulas'] += (float)$r['Particulas'];
        $soma['Sismo_SI']  += (float)$r['Sismo_SI'];
        $soma['Sismo_PGA'] += (float)$r['Sismo_PGA'];
    }
    $count = count($group);
    $dadosSemanal[] = [
        'semana'    => substr($week, 4), // só o número da semana
        'avgTemp'   => round($soma['Temp'] / $count, 2),
        'avgPress'  => round($soma['Pressure'] / $count, 2),
        'avgHum'    => round($soma['Humidity'] / $count, 2),
        'avgGas'    => round($soma['Gas'] / $count, 2),
        'avgLux'    => round($soma['Light'] / $count, 2),
        'avgPart'   => round($soma['Particulas'] / $count, 2),
        'avgSI'     => round($soma['Sismo_SI'] / $count, 2),
        'avgPGA'    => round($soma['Sismo_PGA'] / $count, 3),
    ];
}

$groupMes = [];
foreach ($rows as $r) {
    $dt  = new DateTime($r['data_hora']);
    $mes = $dt->format("n"); // 1–12
    $groupMes[$mes][] = $r;
}
$dadosMensal = [];
foreach ($groupMes as $mes => $group) {
    $soma = array_fill_keys(
        ['Temp', 'Pressure', 'Humidity', 'Gas', 'Light', 'Particulas', 'Sismo_SI', 'Sismo_PGA'],
        0
    );
    foreach ($group as $r) {
        $soma['Temp']      += (float)$r['Temp'];
        $soma['Pressure']  += (float)$r['Pressure'];
        $soma['Humidity']  += (float)$r['Humidity'];
        $soma['Gas']       += (float)$r['Gas'];
        $soma['Light']     += (float)$r['Light'];
        $soma['Particulas'] += (float)$r['Particulas'];
        $soma['Sismo_SI']  += (float)$r['Sismo_SI'];
        $soma['Sismo_PGA'] += (float)$r['Sismo_PGA'];
    }
    $count = count($group);
    $dadosMensal[] = [
        'mes'       => (string)$mes,
        'avgTemp'   => round($soma['Temp'] / $count, 2),
        'avgPress'  => round($soma['Pressure'] / $count, 2),
        'avgHum'    => round($soma['Humidity'] / $count, 2),
        'avgGas'    => round($soma['Gas'] / $count, 2),
        'avgLux'    => round($soma['Light'] / $count, 2),
        'avgPart'   => round($soma['Particulas'] / $count, 2),
        'avgSI'     => round($soma['Sismo_SI'] / $count, 2),
        'avgPGA'    => round($soma['Sismo_PGA'] / $count, 3),
    ];
}

$result = [
    'historico'     => $ultimos12,
    'ultimaLeitura' => [
        'tempAtual'     => $ultima['Temp'],
        'pressaoAtual'  => $ultima['Pressure'],
        'humidadeAtual' => $ultima['Humidity'],
        'gasAtual'      => $ultima['Gas'],
        'luxAtual'      => $ultima['Light'],
        'latitudeAtual' => $ultima['latitude'] ?? null,
        'longitudeAtual' => $ultima['longitude'] ?? null,
        'altitudeAtual' => $ultima['altitude'] ?? null,
        'particulasAtual' => $ultima['Particulas'],
        'sismoSIAtual'  => $ultima['Sismo_SI'],
        'sismoPGAAtual' => $ultima['Sismo_PGA'],
        'dataHoraAtual' => $ultima['data_hora']
    ],
    'dadosSemanal'  => $dadosSemanal,
    'dadosMensal'   => $dadosMensal
];

header('Content-Type: application/json');
echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
