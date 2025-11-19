<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <?php include("head.php");     ?>


</head>

<body>
    <div id="mainContent">
        <header>
            <?php include("navegacao.php"); ?>
        </header>
        <br>
        <h1 class="titulo-historico">Relatórios Ambientais - Registos Diários, Semanais e Mensais</h1>


        <div class="botoes-navegacao-relatorios">
            <button onclick="scrollToSection('relatorio-diario')" class="botao-diario">Relatório Diário</button>
            <button onclick="scrollToSection('relatorio-semanal')" class="botao-semanal">Relatório Semanal</button>
            <button onclick="scrollToSection('relatorio-mensal')" class="botao-mensal">Relatório Mensal</button>
            <button onclick="abrirTabelaPopup()" class="botao-tabela">Ver dados forma Tabelar</button>
        </div>
        <section id="relatorios10reg">
            <div class="container" id="relatorio-diario">
                <h2 style="text-align:center;">Relatório Diário</h2>
                <form id="filtroForm">
                    <div class="filtro-container">
                        <label for="data">Data:</label>
                        <input type="date" id="data" name="data" required>

                        <label for="periodo">Período:</label>
                        <select id="periodo" name="periodo">
                            <option value="manha">Manhã</option>
                            <option value="tarde">Tarde</option>
                            <option value="noite">Noite</option>
                        </select>
                        <button id="filtrarBtn" type="button">Filtrar</button>
                    </div>
                </form>
                <div id="loadingSpinner" style="display:none;">A carregar...</div>
                <p id="mensagemInicial" style="text-align:center; font-size: 18px; color: #555; margin-top: 20px;">
                    Selecione a data e o período que pretende visualizar os dados.
                </p>
                <p id="mensagemSemDados" style="display: none; text-align:center; font-size: 18px; color: #a00; margin-top: 20px;">
                    Não existem dados para o período e data selecionados.
                </p>
                <div id="periodoContainer"></div>
                <div class="graficos-grid">
                    <!--      <canvas id="graficoDia" width="400" height="200"></canvas>-->
                    <canvas id="graficoTempHumPartGas" width="400" height="200"></canvas>
                    <canvas id="graficoPressLuz" width="400" height="200"></canvas>
                    <strong>Atenção:</strong> Para obter os valores clique em cima de cada ponto do gráfico.
                </div>

            </div>
            <div class="container" id="relatorio-semanal">
                <h2 style="text-align: center;">Relatórios Semanais</h2>
                <div class="graficos-grid">
                    <canvas id="graficoSemanalA" width="400" height="200"></canvas>
                    <canvas id="graficoSemanalB" width="400" height="200"></canvas>
                    <strong>Atenção:</strong> Para obter os valores clique em cima de cada ponto do gráfico.
                </div>
            </div>

            <div class="container" id="relatorio-mensal">
                <h2 style="text-align: center;">Relatórios Mensais</h2>
                <div class="graficos-grid">
                    <canvas id="graficoMensalA" width="400" height="200"></canvas>
                    <canvas id="graficoMensalB" width="400" height="200"></canvas>
                    <strong>Atenção:</strong> Para obter os valores clique em cima de cada barra do gráfico.
                </div>

            </div>
    </div>
    <div id="popupTabela" class="modal-tabela" style="display:none;">
        <div class="modal-content">
            <span class="fechar" onclick="fecharTabelaPopup()">&times;</span>
            <h2>Dados em Tabela</h2>
            <table id="tabela-dados">
                <thead>
                    <tr style="background-color: #c9cfc9;">
                        <th><i class="fas fa-calendar-day"></i> Data</th>
                        <th><i class="fas fa-temperature-high"></i> Temperatura (°C)</th>
                        <th><i class="fas fa-tint"></i> Humidade (%)</th>
                        <th><i class="fas fa-tachometer-alt"></i> Pressão (hPa)</th>
                        <th><i class="fas fa-smog"></i> Partículas (µg/m³)</th>
                        <th><i class="fas fa-lightbulb"></i> Luz (%)</th>
                        <th><i class="fas fa-burn"></i> Gás ppm</th>
                        <th><i class="fas fa-heat"></i> SI(Sismo Intensidade)</th>
                        <th><i class="fas fa-fan"></i> PGA(Sismo Movimento)</th>
                    </tr>
                </thead>
                <tbody id="corpo-tabela">
                    <tr>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <strong>Atenção:</strong> A tabela apresenta os ultimos 50 registos obtidos.
            <div style="margin-top: 20px; text-align: right;">
                <button class="botao-dashboard botao-pdf" style="background-color: rgb(36, 103, 249);" onclick="gerarPDFTab()">📄 Exportar PDF</button>
                <button class="botao-dashboard botao-csv" onclick="exportarCSV()">🧾 Exportar CSV</button>
            </div>
        </div>
    </div>

    </section>
    <button id="btnTopo" title="Voltar ao topo">🏠</button>
    <br><br><br><br><br><br>
    <button id="botaoPDF">📄 PDF</button>
    <section id="footer">
        <?php include("rodape.php"); ?>
    </section>
    </div>
    <script src="js/s2.js?v=<?php echo time(); ?>"></script>
    <script src="js/s3.js?v=<?php echo time(); ?>"></script>


</body>

</html>