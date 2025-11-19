<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitorização da Sala de Aula - Espalmela</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="css/style_navegacao.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link rel="icon" type="image/png" href="imagens/logotipo.png">


    <meta charset="UTF-8">
    <title>Painel de Alertas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #eef3f7;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .alerta {
            background-color: #ffffff;
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin: 10px auto;
            max-width: 500px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .verde {
            border-color: #4CAF50;
            background-color: #e8f5e9;
        }

        .amarelo {
            border-color: #f9a825;
            background-color: #fffde7;
        }

        .vermelho {
            border-color: #c62828;
            background-color: #ffebee;
        }

        .alerta h2 {
            margin: 0 0 10px 0;
        }

        .alerta span {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <header>
        <?php include("navegacao.php"); ?>
    </header>
    <h1>Alertas Ubidots</h1>

    <div class="alerta" id="humidade">
        <h2>Humidade:</h2>
        <p><span id="humidade_valor">...</span></p>
    </div>
    <div class="alerta" id="temperatura">
        <h2>Temperatura:</h2>
        <p><span id="temperatura_valor">...</span></p>
    </div>
    <div class="alerta" id="luz">
        <h2>Luz:</h2>
        <p><span id="luz_valor">...</span></p>
    </div>
    <div class="alerta" id="pga">
        <h2>PGA:</h2>
        <p><span id="pga_valor">...</span></p>
    </div>
    <section id="footer">
        <?php include("rodape.php"); ?>
    </section>
    <script>
        async function carregarDados() {
            try {
                const resp = await fetch('dados_alerta.json');
                const dados = await resp.json();

                function aplicarCor(elem, valor, tipo) {
                    elem.classList.remove('verde', 'amarelo', 'vermelho');

                    let cor = 'verde';

                    if (tipo === 'humidade') {
                        if (valor > 40 && valor < 70) cor = 'verde';
                        else if (valor >= 70) cor = 'amarelo';
                        else cor = 'vermelho';
                    } else if (tipo === 'temperatura') {
                        if (valor >= 18 && valor <= 25) cor = 'verde';
                        else if ((valor >= 10 && valor < 18) || (valor > 30 && valor <= 33)) cor = 'amarelo';
                        else cor = 'vermelho';
                    } else if (tipo === 'luz') {
                        if (valor < 300) cor = 'vermelho';
                        else if (valor <= 1000) cor = 'amarelo';
                        else cor = 'verde';
                    } else if (tipo === 'pga') {
                        if (valor < 0.1) cor = 'verde';
                        else if (valor <= 0.2) cor = 'amarelo';
                        else cor = 'vermelho';
                    }

                    elem.classList.add(cor);
                }

                const elHum = document.getElementById('humidade');
                const valHum = dados.humidade?.valor || 0;
                document.getElementById('humidade_valor').textContent = valHum + " %";
                aplicarCor(elHum, valHum, 'humidade');

                const elTemp = document.getElementById('temperatura');
                const valTemp = dados.temperatura?.valor || 0;
                document.getElementById('temperatura_valor').textContent = valTemp + " °C";
                aplicarCor(elTemp, valTemp, 'temperatura');

                const elLuz = document.getElementById('luz');
                const valLuz = dados.luz?.valor || 0;
                document.getElementById('luz_valor').textContent = valLuz + " lux";
                aplicarCor(elLuz, valLuz, 'luz');

                const elPga = document.getElementById('pga');
                const valPga = dados.pga?.valor || 0;
                document.getElementById('pga_valor').textContent = valPga + " gal";
                aplicarCor(elPga, valPga, 'pga');

            } catch (e) {
                console.error("Erro ao carregar os dados:", e);
            }
        }

        carregarDados();
        setInterval(carregarDados, 5000);
    </script>
</body>

</html>