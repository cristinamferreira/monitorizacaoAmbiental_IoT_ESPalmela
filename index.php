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
        <main>
            <div class="button-group">
                <a href="#resumo" class="botao-dashboard btn-resumo">Resumo</a>
                <a href="#leitura" class="botao-dashboard btn-leitura">Leitura Atual</a>
                <a href="#sismica" class="botao-dashboard btn-sismica">Atividade Sísmica</a>

                <button class="botao-dashboard btn-ambiental" onclick="window.open('https://stem.ubidots.com/app/dashboards/67ec3274ada8da0010244511', '_blank')">
                    Abrir Dashboard Ambiental
                </button>
                <button class="botao-dashboard btn-unidots" style="background-color: #f9cb24;"><a href="painel-visualizacao.php">
                        Abrir Alertas Ubidots</a>
                </button>
                <button class="botao-dashboard btn_csv" style="background-color:rgb(249, 36, 213);" onclick="exportarCSV()">Exportar Dados em CSV</button>

                <div class="titulo_mon_10reg">Atualização: <span id="dataAtual"></span> -
                    <span id="horaAtual" style="display: inline-block;margin-right: 3ch;"></span>
                </div>
            </div>
            <div id="resumo">
                <center>
                    <div id="resumoContentor" class="resumo-dashboard">

                        <div class="card-resumo">
                            <div id="map" style="height: 600px;"></div>
                        </div>

                        <div class="card-resumo">
                            <p style="font-weight: bold; font-size: 18px;text-align: center;"><strong>📋 Leitura - Nº Total de Registos:</strong> <span id="totalRegistosResumo">--</span></p>

                            <div class="card_v1-grid">
                                <div class="card_v1 card_v1-temp">
                                    <div class="card_v1-tag card_v1-tag-temp">Temperatura</div>
                                    <br>
                                    <table class="tabela-centro" style="width: 230px;">
                                        <tr>
                                            <td rowspan=" 2" style="font-size: 30px;">🌡️</td>
                                            <td style="background-color: #3b9fb8ff;"><strong>Norte</strong> </td>
                                            <td class="col-salas">
                                                <div class="card_v1-value"><span id="tempResumo"> --</span> °C</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: #daba08ff;"><strong>Sul</strong></td>
                                            <td class="col-salas">
                                                <div class="card_v1-value"><span id="tempResumoSul"> --</span> °C </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="card_v1 card_v1-hum">
                                    <div class="card_v1-tag card_v1-tag-hum">Humidade</div>
                                    <br>
                                    <table class="tabela-centro" style="width: 230px;">
                                        <tr>
                                            <td rowspan="2" style="font-size: 30px;">💧</td>
                                            <td style="background-color: #3b9fb8ff;"><strong>Norte</strong> </td>
                                            <td class="col-salas">
                                                <div class="card_v1-value"><span id="humResumo"> --</span> %</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: #daba08ff;"><strong>Sul</strong></td>
                                            <td class="col-salas">
                                                <div class="card_v1-value"><span id="humResumoSul"> --</span> % </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="card_v1 card_v1-part">
                                    <div class="card_v1-tag card_v1-tag-part">Partículas</div>
                                    <br>
                                    <table class="tabela-centro" style="width: 230px;">
                                        <tr>
                                            <td rowspan="2" style="font-size: 30px;">🌫️</td>
                                            <td style="background-color: #3b9fb8ff;"><strong>Norte</strong></td>
                                            <td class="col-salas">
                                                <div class="card_v1-value"><span id="partResumo">--</span> µg/m³</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: #daba08ff;"><strong>Sul</strong></td>
                                            <td class="col-salas">
                                                <div class="card_v1-value"><span id="partResumoSul">--</span> µg/m³</div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="card_v1 card_v1-gas">
                                    <div class="card_v1-tag card_v1-tag-gas">Gás</div>
                                    <br>
                                    <table class="tabela-centro" style="width: 230px;">
                                        <tr>
                                            <td rowspan="2" style="font-size: 30px;">🧪</td>
                                            <td style="background-color: #3b9fb8ff;"><strong>Norte</strong></td>
                                            <td class="col-salas">
                                                <div class="card_v1-value"><span id="gasResumo">--</span> ppm</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: #daba08ff;"><strong>Sul</strong></td>
                                            <td class="col-salas">
                                                <div class="card_v1-value"><span id="gasResumoSul">--</span> ppm</div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="card_v1 card_v1-lux">
                                    <div class="card_v1-tag card_v1-tag-lux">Luz</div>
                                    <br>
                                    <table class="tabela-centro" style="width: 230px;">
                                        <tr>
                                            <td rowspan="2" style="font-size: 30px;">💡</td>
                                            <td style="background-color: #3b9fb8ff;"><strong>Norte</strong></td>
                                            <td class="col-salas">
                                                <div class="card_v1-value"><span id="luxResumo">--</span> lux</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: #daba08ff;"><strong>Sul</strong></td>
                                            <td class="col-salas">
                                                <div class="card_v1-value"><span id="luxResumoSul">--</span> lux</div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="card_v1 card_v1-press">
                                    <div class="card_v1-tag card_v1-tag-press">Pressão</div>
                                    <br>
                                    <table class="tabela-centro" style="width: 230px;">
                                        <tr>
                                            <td rowspan="2" style="font-size: 30px;">📊</td>
                                            <td style="background-color: #3b9fb8ff;"><strong>Norte</strong></td>
                                            <td class="col-salas">
                                                <div class="card_v1-value"><span id="pressResumo">--</span> hPa</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: #daba08ff;"><strong>Sul</strong></td>
                                            <td class="col-salas">
                                                <div class="card_v1-value"><span id="pressResumoSul">--</span> hPa</div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="card_v2-grid">
                                <div class="card_v1 card_v1-sismo">
                                    <div class="card_v1-tag card_v1-tag-sismo">Atividade Sísmica</div>
                                    <div class="card_v1-value">🌎 Intensidade: <span id="sismoSIResumo">--</span> cm/s - Movimento:<span id="sismoPGAResumo">--</span> m/s²</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="resumo-ambiente" id="resumoAmbiente">
                        <div class="estado-box" id="estadoGeral">
                            <h3>Estado Geral do Ambiente</h3>
                            <p id="mensagemEstado">A carregar dados...</p>
                        </div>
                    </div>
                    <br>
                    <span id="dataAtualResumo"></span>
                    <span id="horaAtualResumo"></span>
                </center>
            </div>
            <div id="leitura">
                <div class="section-bar">📋 Informação sobre leitura atual</div>
                <div class="container">
                    <div class="card_v1 card_v1-temp" style="width: 300px;">
                        <div class="card_v1-tag card_v1-tag-temp">Temperatura</div>
                        <table class="tabela-centro" style="width: 300px;">
                            <tr>
                                <td style="background-color: #3b9fb8ff;"><strong>Norte</strong></td>
                                <td style="background-color: #daba08ff;"><strong>Sul</strong></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="card_v1-value"> 🌡️ <span id="tempAtual"> --</div>
                                    <div id="card-temp">
                                        <p id="texto-temp" class="card-alert" style="font-size: 30px; font-weight: bold;"></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="card_v1-value"> 🌡️ <span id="tempAtualSul"> --</div>
                                    <div id="card-temp">
                                        <p id="texto-temp" class="card-alert" style="font-size: 30px; font-weight: bold;"></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="card-info">
                                        ✔️ Ideal: 20°C a 28°C<br>⚠️ < 20ºC, Frio<br> ❗ > 28ºC, Muito Calor
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="card_v1 card_v1-hum" style="width: 300px;">
                        <div class="card_v1-tag card_v1-tag-hum">Humidade</div>

                        <table class="tabela-centro" style="width: 300px;">
                            <tr>
                                <td style="background-color: #3b9fb8ff;"><strong>Norte</strong></td>
                                <td style="background-color: #daba08ff;"><strong>Sul</strong></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="card_v1-value">💧 <span id="humidadeAtual">--</span> </div>
                                    <div id="card-hum">
                                        <p id="texto-hum" class="card-alert" style="font-size: 30px; font-weight: bold;"></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="card_v1-value">💧 <span id="humidadeAtualSul">--</span> %</div>
                                    <div id="card-hum">
                                        <p id="texto-hum" class="card-alert" style="font-size: 30px; font-weight: bold;"></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="card-info">
                                        ✔️ Ideal: 40% a 75%<br>
                                        ⚠️ &lt; 40%, tempo seco<br>
                                        ❗ &gt; 76%, tempo de chuva
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="card_v1 card_v1-press" style="width: 300px;">
                        <div class="card_v1-tag card_v1-tag-press">Pressão</div>
                        <table class="tabela-centro" style="width: 300px;">
                            <tr>
                                <td style="background-color: #3b9fb8ff;"><strong>Norte</strong></td>
                                <td style="background-color: #daba08ff;"><strong>Sul</strong></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="card_v1-value">📊 <span id="pressaoAtual">--</span> </div>
                                    <div id="card-press">
                                        <p id="texto-press" class="card-alert" style="font-size: 30px; font-weight: bold;"></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="card_v1-value">📊 <span id="pressaoAtualSul">--</span> hPa</div>
                                    <div id="card-press">
                                        <p id="texto-press" class="card-alert" style="font-size: 30px; font-weight: bold;"></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="card-info">
                                        ✔️ Ideal: 1013 hPa<br>
                                        ⚠️ &lt; 1000, tempo instável<br>
                                        ❗ &gt; 1020, tempo seco e estável
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="card_v1 card_v1-gas" style="width: 300px;">
                        <div class="card_v1-tag card_v1-tag-gas">Gás</div>
                        <table class="tabela-centro" style="width: 300px;">
                            <tr>
                                <td style="background-color: #3b9fb8ff;"><strong>Norte</strong></td>
                                <td style="background-color: #daba08ff;"><strong>Sul</strong></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="card_v1-value">🧪 <span id="gasAtual">--</span> </div>
                                    <div id="card-gas">
                                        <p id="texto-gas" class="card-alert" style="font-size: 30px; font-weight: bold;"></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="card_v1-value">🧪 <span id="gasAtualSul">--</span> </div>
                                    <div id="card-gas">
                                        <p id="texto-gas" class="card-alert" style="font-size: 30px; font-weight: bold;"></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="card-info">
                                        ✔️ Ideal: &lt; 300 ppm<br>
                                        ⚠️ 300 a 500, alerta<br>
                                        ❗ &gt; 500, preocupante
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>


                    <div class="card_v1 card_v1-lux" style="width: 300px;">
                        <div class="card_v1-tag card_v1-tag-lux">Luz</div>
                        <table class="tabela-centro" style="width: 300px;">
                            <tr>
                                <td style="background-color: #3b9fb8ff;"><strong>Norte</strong></td>
                                <td style="background-color: #daba08ff;"><strong>Sul</strong></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="card_v1-value">💡 <span id="luxAtual">--</span> </div>
                                    <div id="card-luz">
                                        <p id="texto-luz" class="card-alert" style="font-size: 30px; font-weight: bold;"></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="card_v1-value">💡 <span id="luxAtualSul">--</span> </div>
                                    <div id="card-luz">
                                        <p id="texto-luz" class="card-alert" style="font-size: 30px; font-weight: bold;"></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="card-info">
                                        ✔️ Ideal: 300 a 500 lux<br>
                                        ⚠️ &lt; 300, pouca iluminação<br>
                                        ❗ &gt; 500, demasiada luz
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="card_v1 card_v1-part" style="width: 300px;">
                        <div class="card_v1-tag card_v1-tag-part">Partículas</div>
                        <table class="tabela-centro" style="width: 300px;">
                            <tr>
                                <td style="background-color: #3b9fb8ff;"><strong>Norte</strong></td>
                                <td style="background-color: #daba08ff;"><strong>Sul</strong></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="card_v1-value">🌫️ <span id="particulasAtual">--</span> </div>
                                    <div id="card-part">
                                        <p id="texto-partic" class="card-alert" style="font-size: 30px; font-weight: bold;"></p>
                                    </div>
                                </td>
                                <td>
                                    <div class="card_v1-value">🌫️ <span id="particulasAtualSul">--</span> </div>
                                    <div id="card-part">
                                        <p id="texto-partic" class="card-alert" style="font-size: 30px; font-weight: bold;"></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="card-info">
                                        ✔️ Ideal: &lt; 20 µg/m³<br>
                                        ⚠️ Entre 21 e 44 µg/m³, tempo seco<br>
                                        ⛔ &lt; 45 (Máximo OMS): 45 µg/m³ (média diária)
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="container">
                    <div class="section-bar">
                        <div class="card-resumo diff-zone">
                            <strong>🔀 Diferença entre leituras (Norte − Sul)</strong>

                        </div>
                        <p style="text-align:center; margin:-6px 0 10px 0; font-size:12px; opacity:.8">
                            Δ = Norte − Sul · valor <span style="color:#d72e2e; font-weight:700">(+)</span> Norte maior ·
                            <span style="color:#2563eb; font-weight:700">(−)</span> Sul maior
                        </p>
                        <div class="diff-grid">

                            <div class="diff-card" data-key="temp">
                                <div class="diff-card__tag">
                                    <span class="label" style="color:#fa4c07;font-size: 20px;">Temperatura</span>
                                    <span class="status-badge" aria-live="polite"></span>
                                </div>
                                <div class="diff-card__value">Δ 🌡️ <span id="tempDiff">--</span> °C</div>
                            </div>

                            <div class="diff-card" data-key="hum">
                                <div class="diff-card__tag">
                                    <span class="label" style="color:#1e89d1;">Humidade</span>
                                    <span class="status-badge" aria-live="polite"></span>
                                </div>
                                <div class="diff-card__value">Δ 💧 <span id="humDiff">--</span> %</div>
                            </div>


                            <div class="diff-card" data-key="part">
                                <div class="diff-card__tag">
                                    <span class="label" style="color:#807b7a;">Partículas</span>
                                    <span class="status-badge" aria-live="polite"></span>
                                </div>
                                <div class="diff-card__value">Δ 🌫️ <span id="partDiff">--</span> µg/m³</div>
                            </div>


                            <div class="diff-card" data-key="gas">
                                <div class="diff-card__tag">
                                    <span class="label" style="color:#0a7b74;">Gás</span>
                                    <span class="status-badge" aria-live="polite"></span>
                                </div>
                                <div class="diff-card__value">Δ 🧪 <span id="gasDiff">--</span> ppm</div>
                            </div>

                            <div class="diff-card" data-key="lux">
                                <div class="diff-card__tag">
                                    <span class="label" style="color:#b8860b;">Luz</span>
                                    <span class="status-badge" aria-live="polite"></span>
                                </div>
                                <div class="diff-card__value">Δ 💡 <span id="luxDiff">--</span> lux</div>
                            </div>

                            <div class="diff-card" data-key="press">
                                <div class="diff-card__tag">
                                    <span class="label" style="color:#169716;">Pressão</span>
                                    <span class="status-badge" aria-live="polite"></span>
                                </div>
                                <div class="diff-card__value">Δ 📊 <span id="pressDiff">--</span> hPa</div>
                            </div>
                        </div>
                    </div>
                    <script>
                        (function() {
                            const THRESHOLDS = {
                                temp: {
                                    warn: 2,
                                    crit: 4
                                },
                                hum: {
                                    warn: 8,
                                    crit: 15
                                },
                                part: {
                                    warn: 8,
                                    crit: 15
                                },
                                gas: {
                                    warn: 80,
                                    crit: 150
                                },
                                lux: {
                                    warn: 150,
                                    crit: 300
                                },
                                press: {
                                    warn: 3,
                                    crit: 6
                                }
                            };

                            const SIMULAR = true;
                            const DEMO_DIFFS = {
                                tempDiff: -4.0,
                                humDiff: +12.0,
                                partDiff: +16.0,
                                gasDiff: +120.0,
                                luxDiff: +250.0,
                                pressDiff: +5.0
                            };

                            const SOURCES = {
                                temp: {
                                    n: "tempResumoNorte",
                                    s: "tempResumo"
                                },
                                hum: {
                                    n: "humResumoNorte",
                                    s: "humResumo"
                                },
                                part: {
                                    n: "partResumoNorte",
                                    s: "partResumo"
                                },
                                gas: {
                                    n: "gasResumoNorte",
                                    s: "gasResumo"
                                },
                                lux: {
                                    n: "luxResumoNorte",
                                    s: "luxResumo"
                                },
                                press: {
                                    n: "pressResumoNorte",
                                    s: "pressResumo"
                                }
                            };

                            const toNum = s => {
                                if (s == null) return NaN;
                                s = ("" + s).replace(/[^\d\-\.\,]/g, "").replace(",", ".");
                                return parseFloat(s);
                            };

                            function fmtSigned(v) {
                                if (!isFinite(v)) return "--";
                                const sign = v > 0 ? "+" : v < 0 ? "−" : "±";
                                const val = Math.abs(v) >= 10 ? Math.round(Math.abs(v)) : Math.round(Math.abs(v) * 10) / 10;
                                return `${sign} ${val}`;
                            }

                            function setBadge(card, level) {
                                card.classList.remove("is-ok", "is-warn", "is-crit");
                                card.classList.add(level);
                                const b = card.querySelector(".status-badge");
                                if (!b) return;
                                b.textContent = (level === "is-crit" ? "ALERTA" : level === "is-warn" ? "Aviso" : "OK");
                            }

                            function applySigned(diffId, key, unit) {
                                const span = document.getElementById(diffId);
                                if (!span) return;
                                const card = span.closest(".diff-card");
                                if (!card) return;

                                let vSigned = NaN;

                                if (SIMULAR && diffId in DEMO_DIFFS) {
                                    vSigned = DEMO_DIFFS[diffId];
                                } else {
                                    const src = SOURCES[key];
                                    if (src) {
                                        const nEl = document.getElementById(src.n);
                                        const sEl = document.getElementById(src.s);
                                        if (nEl && sEl) {
                                            const n = toNum(nEl.textContent);
                                            const s = toNum(sEl.textContent);
                                            if (isFinite(n) && isFinite(s)) vSigned = n - s;
                                        }
                                    }
                                    if (!isFinite(vSigned)) {
                                        vSigned = toNum(span.textContent);
                                    }
                                }

                                const valWrap = span;
                                const cls = vSigned > 0 ? "diff-pos" : vSigned < 0 ? "diff-neg" : "diff-zero";
                                valWrap.classList.remove("diff-pos", "diff-neg", "diff-zero");
                                valWrap.classList.add("signed", cls);
                                span.textContent = fmtSigned(vSigned);

                                const absV = Math.abs(vSigned);
                                const {
                                    warn,
                                    crit
                                } = THRESHOLDS[key] || {
                                    warn: Infinity,
                                    crit: Infinity
                                };
                                if (absV >= crit) setBadge(card, "is-crit");
                                else if (absV >= warn) setBadge(card, "is-warn");
                                else setBadge(card, "is-ok");
                            }

                            function run() {
                                applySigned("tempDiff", "temp", "°C");
                                applySigned("humDiff", "hum", "%");
                                applySigned("partDiff", "part", "µg/m³");
                                applySigned("gasDiff", "gas", "ppm");
                                applySigned("luxDiff", "lux", "lux");
                                applySigned("pressDiff", "press", "hPa");
                            }

                            if (document.readyState === "loading") {
                                document.addEventListener("DOMContentLoaded", run);
                            } else {
                                run();
                            }
                            setInterval(run, 1500);
                        })();
                    </script>

                </div><!-- container  -->
            </div>
            <center>
                <div id="sismica">
                    <div class="section-bar" style="background-color:rgb(185, 135, 139); border: 6px solid rgb(163, 40, 50);color:whitesmoke;">🌍 Informação sobre atividade sísmica</div>
                    <div class="card-sismo">
                        <div class="card-header">

                            <h2>Atividade Sísmica</h2>
                            <p style="font-size: 15px;">O SI (Spectral Intensity) avalia a resposta dinâmica do solo (movimento), e o PGA (Peak Ground Acceleration) quantifica a aceleração máxima registada (mede a força máxima) durante um evento sísmico.</p>

                            <div class="card-body">
                                <div class="sismo-table-container">
                                    <table class="sismo-table" style="font-size: 12px;">
                                        <thead>
                                            <tr>
                                                <th>SI (cm/s)</th>
                                                <th>PGA (m/s²)</th>
                                                <th>Descrição</th>
                                                <th>Risco (Intensidade)</th>
                                                <th>Risco (PGA)</th>
                                                <th>Escala de Richter</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style="background-color:rgb(142, 230, 164);">
                                                <td>0</td>
                                                <td>0</td>
                                                <td>Sem movimento detetado</td>
                                                <td>Nenhum</td>
                                                <td>Nulo</td>
                                                <td>&lt; 1.0</td>
                                            </tr>
                                            <tr style="background-color:rgb(142, 230, 164);">
                                                <td>0.1 – 25.0</td>
                                                <td>0.001 – 0.98</td>
                                                <td>Muito ligeiro, quase impercetível</td>
                                                <td>Fraco</td>
                                                <td>Baixo</td>
                                                <td>~1.0 – 2.5</td>
                                            </tr>
                                            <tr style="background-color:rgb(142, 230, 164);">
                                                <td>25.1 – 50.0</td>
                                                <td>0.99 – 1.96</td>
                                                <td>Leve, sentido por algumas pessoas</td>
                                                <td>Ligeiro</td>
                                                <td>Ligeiro</td>
                                                <td>~2.5 – 3.5</td>
                                            </tr>
                                            <tr style="background-color:rgb(233, 211, 14);">
                                                <td>50.1 – 100.0</td>
                                                <td>1.97 – 3.92</td>
                                                <td>Objetos balançam ligeiramente</td>
                                                <td>Moderado</td>
                                                <td>Moderado</td>
                                                <td>~3.5 – 4.5</td>
                                            </tr>
                                            <tr style=" background-color:rgb(230, 119, 15);">
                                                <td>100.1 – 200.0</td>
                                                <td>3.93 – 7.84</td>
                                                <td>Vibração clara, possíveis danos leves</td>
                                                <td>Muito Elevado</td>
                                                <td>Muito Elevado</td>
                                                <td>~4.5 – 5.0</td>
                                            </tr>
                                            <tr style="background-color:rgb(255, 33, 4);">
                                                <td>200.1 – 400.0</td>
                                                <td>7.85 – 10.0</td>
                                                <td>Danos em estruturas frágeis</td>
                                                <td>Muito Forte</td>
                                                <td>Extremo</td>
                                                <td>~5.0 – 5.5</td>
                                            </tr>
                                            <tr style="background-color:rgb(255, 33, 4);">
                                                <td>&gt; 400.0</td>
                                                <td>&gt; 10.0</td>
                                                <td>Danos estruturais significativos</td>
                                                <td>Catastrófico</td>
                                                <td>Catastrófico</td>
                                                <td>&gt; 6.0</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div> <!-- fim tabela container -->
                                <div class="sismo-table-container" style="display:flex; align-items: center; justify-content: center; gap:20px; flex-wrap:wrap;">
                                    <div class="card_v1 card_v1-sismo" style="width: 200px;">
                                        <br>
                                        <div class="card_v1-tag card_v1-tag-sismo">Sismo: Intensidade</div>
                                        <div class="card_v1-value">📈 <span id="sismoSIAtual">--</span> </div>

                                        <div id="card-si" class="sismo-estado-box">
                                            <p id="texto-si" class="sismo-estado-texto"></p>
                                        </div>
                                        <div class="card-info">
                                            ✔️ Normal: &lt;25 <br>
                                            ⚠️ Atenção: 25–59 <br>
                                            ❗ Alerta: 60–129 <br>
                                            ⛔ Emergência: ≥130
                                            <br>
                                        </div>
                                    </div>
                                    <div class="card_v1 card_v1-sismo" style="width: 200px;">
                                        <br>
                                        <div class="card_v1-tag card_v1-tag-sismo">Sismo: Movimento</div>
                                        <div style="height: 50px;" class="card_v1-value">🌀 <span id="sismoPGAAtual">--</span></div>

                                        <div id="card-pga" class="sismo-estado-box">
                                            <p id="texto-pga" class="sismo-estado-texto"></p>
                                        </div>

                                        <div class="card-info">
                                            ✔️ Baixo: &lt;0.1 <br>
                                            ⚠️ Atenção: 0.1–0.2 <br>
                                            ⛔ Alto ≥0.2
                                        </div>
                                    </div>
                                    <div class="card-info card-richter-global" style="font-size: 14px; margin-top: 10px; color: rgb(90, 30, 30); font-weight: bold;">
                                        🌋 Escala de Richter estimada (evento):
                                        <strong><span id="richterGlobal">-</span></strong>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </center>
        </main>
        <br><br><br><br>
        <?php include("rodape.php"); ?>
    </div>
    <button id="btnTopo" title="Voltar ao topo">🏠</button>
    <script src="js/s1.js?v=<?php echo time(); ?>"></script>
    <script src="js/s3.js?v=<?php echo time(); ?>"></script>
    <button id="botaoPDF" onclick="gerarPDF()">📄 PDF</button>

</body>

</html>