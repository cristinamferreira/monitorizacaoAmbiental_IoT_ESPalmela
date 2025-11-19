<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitorização da Sala de Aula - Espalmela</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/style_navegacao.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/style_listar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <meta http-equiv="refresh" content="120;url=listar_dados.php">
    <script src="js/s1.js"></script>
</head>

<body>
    <?php include('navegacao.php'); ?>
    <br>
    <h1 style="text-align: center;">Dados de Monitorização</h1>
    <div id="dados-monitorizacao">
        <?php include('exibir_dados.php'); ?>
    </div>
    <?php include('rodape.php'); ?>
</body>

</html>