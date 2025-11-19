<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="pt">

<head>

    <?php include("head.php");     ?>
    <link rel="stylesheet" href="css/style_sensores.css?v=<?php echo time(); ?>">
</head>

<body>
    <header>
        <?php include("navegacao.php"); ?>
    </header>
    <div class="sensor-info">
        <h2><span class="titulo-sensor">Projeto de Monitorização Ambiental IoT - Escola Secundária de Palmela</span>
        </h2>
    </div>
    <center>
        <div class="card-gateway">
            A nossa escola está a implementar um projeto inovador de Monitorização Ambiental com IoT, onde integramos tecnologia, sustentabilidade e aprendizagem ativa. Este sistema permite recolher e analisar dados ambientais em tempo real, contribuindo para uma maior consciência ecológica e para o desenvolvimento de competências digitais dos alunos.
        </div>

        <div class="cards-container">
            <div class="card">
                <h3>🎯 Objetivos do Projeto</h3>
                <ul>
                    <li>📘 Promover a literacia ambiental e tecnológica entre os alunos.</li>
                    <li>🤖 Estimular o uso de tecnologias emergentes em contexto educativo.</li>
                    <li>♻️ Criar um sistema sustentável e inovador.</li>
                    <li>🛰️ Monitorizar o ambiente escolar de forma contínua e acessível.</li>
                    <li>🌱 Fomentar a consciência ecológica através da análise de dados reais.</li>
                    <li>📊 Desenvolver competências em ciência de dados e interpretação de gráficos.</li>
                    <li>🌐 Envolver a comunidade escolar numa rede de monitorização digital.</li>
                    <li>🚀 Motivar para carreiras nas áreas STEM (Ciência, Tecnologia, Engenharia e Matemática).</li>
                    <li>🛠️ Explorar soluções para problemas ambientais reais com tecnologia.</li>
                </ul>
            </div>

            <div class="card">
                <h3>🔧 Equipamentos Utilizados</h3>
                <ul>
                    <li><strong>📡 Gateway LoRaWAN (RAK7268V2)</strong>
                        <p>É o coração da comunicação do sistema. Recolhe os dados dos sensores e envia-os para a internet através da rede LoRaWAN.</p>
                    </li>
                    <li><strong>🌡️ Sensor Ambiental (RAK1906)</strong>
                        <p>Este sensor mede Temperatura, Humidade do ar, Pressão atmosférica e Qualidade do Ar.</p>
                    </li>
                    <li><strong>📍 Sensor GPS (RAK1910)</strong>
                        <p>Permite localizar os sensores no espaço, atribuindo coordenadas geográficas.</p>
                    </li>
                    <li><strong>🧠 Módulo Central WisBlock - RAK19003 (base) + RAK4631 (core)</strong> constituem a “placa-mãe” do sistema, responsável por ler os dados dos sensores e enviá-los para o gateway via LoRa.</p>
                    </li>
                </ul>
            </div>

            <div class="card">
                <h3>💻 Software e Ferramentas</h3>
                <ul>
                    <li>💻 <strong>Arduino IDE</strong>: Utilizado para programar o funcionamento dos sensores e a comunicação com o gateway.</li>
                    <li>⚙️ <strong>WisToolBox</strong>: Facilita a configuração inicial do módulo WisBlock.</li>
                    <li>🌐 <strong>Servidor LoRaWAN (Helium ou ChirpStack)</strong>: Recebe os dados transmitidos via LoRa e permite encaminhá-los para visualização e armazenamento.</li>
                    <li>🧮 <strong>Dashboard </strong>: Plataformas visuais onde os dados são apresentados em gráficos, indicadores e tabelas em tempo real.</li>
                    <li>🗄️ <strong>Base de Dados (MySQL)</strong>: Armazena os dados ambientais de forma organizada, permitindo consultas e exportações.</li>
                </ul>
            </div>

            <div class="card">
                <h3>🧩 Como Montámos o Sistema</h3>
                <ul>
                    <li>🔌 Conexão dos Sensores: Os sensores foram ligados à placa base WisBlock através de jumpers.</li>
                    <li>💾 Ligação ao Computador: A placa é alimentada via USB, permitindo também a programação.</li>
                    <li>🧠 Programação em Arduino: O código foi desenvolvido e carregado através do Arduino IDE.</li>
                    <li>📡 Transmissão de Dados: Os dados recolhidos pelos sensores são enviados via LoRaWAN para o gateway e depois armazenados no servidor da escola.</li>
                    <li>📱 Visualização Online - Site: Os dados ficam disponíveis numa plataforma web, acessível a partir de qualquer dispositivo a toda a comunidade.</li>
                </ul>
            </div>
        </div>


        <div class="sensor-info">
            <h2><span class="titulo-sensor">Sensores</span>
            </h2>
        </div>

        <section class="sensor-card">
            <img src="imagens/rak7268v2.jpg" alt="Sensor RAK12019">
            <div class="card-gateway">
                <h3>Gateway LoRaWAN - RAK7268v2</h3>
                <p>O <strong>RAK7268v2</strong> é um gateway LoRaWAN industrial que permite a comunicação confiável entre sensores IoT e a rede central:</p>
                <ul>
                    <li>📡 Suporte a múltiplos canais LoRaWAN simultâneos.</li>
                    <li>⚙️ Processador potente para tratamento e encaminhamento dos dados.</li>
                    <li>🔌 Conectividade Ethernet, Wi-Fi e opcional 4G para internet.</li>
                    <li>🛠️ Fácil integração com plataformas IoT e APIs.</li>
                    <li>🏭 Design robusto para uso em ambientes industriais e externos.</li>
                </ul>
                <p>Ideal para projetos de monitorização ambiental e IoT em larga escala.</p>
            </div>
        </section>
        <section class="sensor-card">
            <img src="imagens/rak12019.jpeg" alt="Sensor RAK12019">
            <div class="sensor-info">
                <h2>Sensor de Luz UV – RAK12019</h2>
                <ul>
                    <li><strong>Chipset:</strong> Lite-On LTR-390UV-01</li>
                    <li><strong>Interface:</strong> I2C (100 kHz ou 400 kHz)</li>
                    <li><strong>Tensão:</strong> 1.7 V a 3.6 V</li>
                    <li><strong>Consumo de Corrente:</strong> 1 µA a 110 µA</li>
                    <li><strong>Resolução Efetiva:</strong> 13 a 20 bits</li>
                    <li><strong>Gama Dinâmica:</strong> 1:18,000,000</li>
                    <li><strong>Temperatura Suportada:</strong> -40°C a +85°C</li>
                </ul>
                <div class="card-luz">
                    <h3>Sensor de Luz - RAK19003 (TSL2591)</h3>
                    <p>O sensor <strong>RAK19003</strong> mede a intensidade da luz ambiente com alta precisão:</p>
                    <ul>
                        <li>💡 Medição da luz visível e infravermelha.</li>
                        <li>🔆 Permite monitorar a luminosidade em diferentes ambientes, desde interiores a espaços exteriores.</li>
                        <li>🌞 Útil para ajustar sistemas de iluminação e para monitoramento ambiental.</li>
                    </ul>
                    <p>📊 Ajuda a entender a variação da luz e o seu impacto no ambiente e no conforto visual.</p>
                </div>

            </div>
        </section>
        <section class="sensor-card">
            <img src="imagens/rak1906.png" alt="Sensor RAK1906">
            <div class="sensor-info">
                <h2>Sensor de Ambiente – RAK1906</h2>
                <ul>
                    <li><strong>Sensor:</strong> Bosch BME680</li>
                    <li><strong>Parâmetros:</strong> Temperatura, Humidade, Pressão, Gás (COVs - Compostos Orgânicos Voláteis)</li>
                    <li><strong>Interface:</strong> I2C</li>
                    <li><strong>Faixa Temperatura:</strong> -40°C a +85°C</li>
                    <li><strong>Faixa Humidade:</strong> 0% a 100% RH</li>
                    <li><strong>Faixa Pressão:</strong> 300 a 1100 hPa</li>
                    <li><strong>IAQ:</strong> Índice de Qualidade do Ar com estimativa de COVs</li>
                </ul>
                <div class="card-covs">
                    <h3>O que são COVs?</h3>
                    <p><strong>COVs</strong> (Compostos Orgânicos Voláteis) são substâncias químicas que evaporam facilmente à temperatura ambiente.</p>
                    <ul>
                        <li>🌬 Presentes em tintas, colas, produtos de limpeza, combustíveis, etc.</li>
                        <li>⚠️ Podem ser tóxicos ou cancerígenos (ex: benzeno, formaldeído).</li>
                        <li>🏠 Afetam a qualidade do ar interior e contribuem para o smog urbano.</li>
                    </ul>
                    <p>🔎 Os sensores como o <strong>RAK1906 (BME680)</strong> ajudam a detetar COVs e calcular o <strong>índice de qualidade do ar (IAQ)</strong>.</p>
                </div>
            </div>
        </section>
        <section class="sensor-card">
            <img src="imagens/rak12039.jpg" alt="Sensor RAK12039">
            <div class="sensor-info">
                <h2>Sensor de Partículas – RAK12039</h2>
                <ul>
                    <li><strong>Sensor:</strong> Sensirion SPS30</li>
                    <li><strong>Parâmetros:</strong> PM1.0, PM2.5, PM4.0, PM10</li>
                    <li><strong>Interface:</strong> UART / I2C</li>
                    <li><strong>Precisão:</strong> Alta estabilidade e confiabilidade</li>
                    <li><strong>Tempo de Vida:</strong> 8 anos em ambiente urbano</li>
                    <li><strong>Faixa de Medição:</strong> 0 - 1000 µg/m³</li>
                    <li><strong>Aplicações:</strong> Qualidade do ar interior/exterior</li>
                </ul>
                <div class="card-particulas">
                    <h3>Monitorização de Partículas (PM)</h3>
                    <p>O sensor <strong>RAK12039</strong> mede partículas em suspensão no ar:</p>
                    <ul>
                        <li>🔹 <strong>PM1.0</strong> – partículas ultrafinas (penetram nos alvéolos pulmonares).</li>
                        <li>🔸 <strong>PM2.5</strong> – associadas a doenças respiratórias e cardiovasculares.</li>
                        <li>⚫ <strong>PM10</strong> – poeiras, pólen, cinzas, etc.</li>
                    </ul>
                    <p>📉 A exposição prolongada a estas partículas pode afetar gravemente a saúde, especialmente em crianças e idosos.</p>
                </div>
            </div>
        </section>
        <section class="sensor-card">
            <img src="imagens/rak12027.jpeg" alt="Sensor RAK12027">
            <div class="sensor-info">
                <h2>Sensor de Sismos – RAK12027</h2>
                <ul>
                    <li><strong>Sensor:</strong> Omron D7S</li>
                    <li><strong>Parâmetros:</strong> Aceleração Sísmica (PGA), Inclinação</li>
                    <li><strong>Interface:</strong> I2C</li>
                    <li><strong>Precisão:</strong> Deteção de vibração vertical e horizontal</li>
                    <li><strong>Funções:</strong> Deteção de queda, aviso de risco, auto-reset</li>
                    <li><strong>Faixa PGA:</strong> até ±2G</li>
                    <li><strong>Dimensões:</strong> 10.3 × 10.3 × 3 mm (sensor)</li>
                </ul>
                <div class="card-sismos">
                    <h3>Deteção de Sismos</h3>
                    <p>O sensor <strong>RAK12027</strong> usa tecnologia <strong>Omron D7S</strong> para detetar movimentos sísmicos:</p>
                    <ul>
                        <li>🌍 Mede a <strong>aceleração sísmica</strong> e calcula o valor <strong>PGA</strong> (Peak Ground Acceleration).</li>
                        <li>📈 Permite identificar tremores de forma rápida e automática.</li>
                        <li>🚨 Pode ser integrado em sistemas de alerta precoce.</li>
                    </ul>
                    <p>✅ Importante em escolas e zonas urbanas para segurança e prevenção.</p>
                </div>

            </div>
        </section>
    </center>
    <?php include("rodape.php"); ?>

    <button id="btnTopo" title="Voltar ao topo">🏠</button>
    <script src="js/s3.js?v=<?php echo time(); ?>"></script>
    <button id="botaoPDF" onclick="gerarPDF()">📄 PDF</button>

</body>

</html>