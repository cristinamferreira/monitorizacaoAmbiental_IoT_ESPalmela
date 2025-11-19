<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <title>Charco da Escola — Monitorização Ambiental com IOT</title>
    <?php include("head.php");     ?>
    <link rel="stylesheet" href="css/style_charco.css?v=<?php echo time(); ?>">
</head>

<body>
    <header>
        <?php include("navegacao.php");        ?>
        <link rel="stylesheet" href="css/style_charco.css?v=<?php echo time(); ?>">
    </header>
    <center>
        <div class="charco-botoes-alunos" style="display:flex; flex-wrap:wrap; gap:10px; justify-content:center; align-items:center;  margin:12px 0 12px;">
            <a href="site-alunos/Danielle/" target="_blank" class="btn-aluno">🕰️ A Nossa História</a>
            <a href="" target="_blank" class="btn-aluno">⭐ A Nossa Missão</a>
            <a href="pag_manutencao.php" target="_blank" class="btn-aluno">🧰 Manutenção</a>
        </div>
    </center>
    <div class="carousel">
        <div class="carousel-track">
            <img src="imagens/charco/img1.jpeg" alt="Imagem 1">
            <img src="imagens/charco/img4.jpg" alt="Imagem 2">
            <img src="imagens/charco/img5.jpg" alt="Imagem 3">
            <img src="imagens/charco/img6.jpg" alt="Imagem 4">
            <img src="imagens/charco/img1.jpeg" alt="Imagem 5">
            <img src="imagens/charco/img4.jpg" alt="Imagem 6">
            <img src="imagens/charco/img5.jpg" alt="Imagem 7">
            <img src="imagens/charco/img6.jpg" alt="Imagem 8">
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#charcoCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#charcoCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
    </div>
    <main class="charco-wrap">
        <aside class="charco-panel">
            <img src="imagens/charco/img1.jpeg" alt="Charco da Escola" class="charco-photo">
            <h2 style="margin:0 0 8px;">🌿 Charco da Escola</h2>
            <p style="margin:0 0 10px; line-height:1.5;">
                Monitorização do ecossistema do charco: <strong>temperatura do ar</strong>, <strong>temperatura da água</strong>,
                <strong>humidade do ar</strong>, <strong>luz ambiente</strong>, <strong>partículas</strong> e <strong>nível da água</strong>.
            </p>
        </aside>
        <section class="charco-kpis">
            <article class="kpi">
                <div class="kpi__head">
                    <div class="kpi__tag">🌡️ Temperatura do Ar</div>
                    <div id="charcoTempArEstado" class="kpi__status">--</div>
                </div>
                <div class="kpi__body">
                    <div class="kpi__value"><span id="charcoTempArValor">--</span> °C</div>
                </div>
                <div class="kpi__hint">✔️ 10–30 °C · ❗ &lt;5 / &gt;35 °C: adverso</div>
            </article>

            <article class="kpi">
                <div class="kpi__head">
                    <div class="kpi__tag">🌡️ Temperatura da Água</div>
                    <div id="charcoTempAguaEstado" class="kpi__status">--</div>
                </div>
                <div class="kpi__body">
                    <div class="kpi__value"><span id="charcoTempAguaValor">--</span> °C</div>
                </div>
                <div class="kpi__hint">✔️ 8–28 °C · ⚠️ variações rápidas afetam fauna/flora</div>
            </article>

            <article class="kpi">
                <div class="kpi__head">
                    <div class="kpi__tag">💧 Humidade do Ar</div>
                    <div id="charcoHumArEstado" class="kpi__status">--</div>
                </div>
                <div class="kpi__body">
                    <div class="kpi__value"><span id="charcoHumArValor">--</span> %</div>
                </div>
                <div class="kpi__hint">✔️ 40%–80% · ⚠️ &lt;40% seco · &gt;90% muito húmido</div>
            </article>

            <article class="kpi">
                <div class="kpi__head">
                    <div class="kpi__tag">💡 Luz Ambiente</div>
                    <div id="charcoLuxEstado" class="kpi__status">--</div>
                </div>
                <div class="kpi__body">
                    <div class="kpi__value"><span id="charcoLuxValor">--</span> lux</div>
                </div>
                <div class="kpi__hint">✔️ 1k–20k lux (difuso) · ❗ &gt;60k sol direto</div>
            </article>

            <article class="kpi">
                <div class="kpi__head">
                    <div class="kpi__tag">🌫️ Partículas (PM)</div>
                    <div id="charcoPartEstado" class="kpi__status">--</div>
                </div>
                <div class="kpi__body">
                    <div class="kpi__value"><span id="charcoPartValor">--</span> µg/m³</div>
                </div>
                <div class="kpi__hint">✔️ &lt;20 · ⚠️ 21–44 · ⛔ ≥45 (OMS)</div>
            </article>

            <article class="kpi">
                <div class="kpi__head">
                    <div class="kpi__tag">🟦 Nível da Água</div>
                    <div id="charcoNivelAguaEstado" class="kpi__status">--</div>
                </div>
                <div class="kpi__body">
                    <div class="kpi__value"><span id="charcoNivelAguaValor">--</span> cm</div>
                </div>
                <div class="kpi__hint">✔️ 12–20 cm · ⚠️ &lt;10 baixo · &gt;30 alto</div>
            </article>
        </section>
    </main>
    <main>
        <section>
            <article>
                <div class="charco-panel" style="padding:15px 50px 20px; margin:0;">
                    <strong>Estado Geral</strong>
                    <div id="mensagemEstadoCharco" style="margin-top:6px; color:#334155;">A carregar...</div>

                    <div style="margin-top:10px;">
                        <div style="font-weight:700; margin-bottom:6px;">Ações de Manutenção (NEE)</div>
                        <ul id="charcoAcoes" style="margin:0; padding-left:18px; line-height:1.5;"></ul>
                        <button id="charcoAcoesReset" class="btn" style="margin-top:8px;">Limpar lista de tarefas de hoje</button>
                    </div>

                    <div class="charco-footer">
                        Última atualização: <span id="charcoData"></span> <span id="charcoHora"></span>
                    </div>
                </div>
            </article>
        </section>
    </main>
    <?php include("rodape.php"); ?>

    <script src="js/charco.js?v=<?php echo time(); ?>"></script>
    <script src="js/carrosel.js?v=<?php echo time(); ?>"></script>

    <script>
        (function() {
            const ids = [
                "charcoTempArValor", "charcoTempAguaValor", "charcoHumArValor", "charcoLuxValor", "charcoPartValor", "charcoNivelAguaValor",
                "charcoTempArEstado", "charcoTempAguaEstado", "charcoHumArEstado", "charcoLuxEstado", "charcoPartEstado", "charcoNivelAguaEstado",
                "mensagemEstadoCharco", "charcoData", "charcoHora", "charcoAcoes", "charcoAcoesReset"
            ];
            const miss = ids.filter(i => !document.getElementById(i));
            if (miss.length) alert("Código que falta:\n" + miss.join("\n"));
        })();
    </script>

</body>

</html>