<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Manutenção do Charco— Monitorização Ambiental com IOT</title>
    <?php include("head.php");     ?>
    <link rel="stylesheet" href="css/style_charco.css">
</head>

<body>
    <header>
        <?php include "navegacao.php"; ?>
    </header>

    <main>
        <section class="charco-wrap" style="margin-top: 10px;">
            <article class="charco-panel">
                <h2>🛠️ Página de Manutenção</h2>
                <p>
                    Nesta página os alunos podem ver quais as tarefas de manutenção realizadas no <strong>Charco</strong>: o que foi feito, quando foi feito e por quem foi feito.
                </p>

                <img src="imagens/charco/img1.jpeg"
                    alt="Estado inicial do charco"
                    class="charco-photo">

                <p class="charco-footer">
                    📷 <strong>Estado inicial (exemplo fictício):</strong> água baixa, alguma vegetação
                    e presença de folhas e pequenos resíduos na margem.
                </p>
            </article>

            <article class="charco-panel">
                <h3>📊 Estado atual (resumo)</h3>

                <div class="charco-kpis">
                    <div class="kpi">
                        <div class="kpi__head">
                            <span class="kpi__tag">💧 Nível da água</span>
                            <span class="kpi__status warn">A vigiar</span>
                        </div>
                        <div class="kpi__body">
                            <div class="kpi__value">Baixo</div>
                            <div class="kpi__hint">
                                Última medição: <strong>12 cm</strong>. Próxima verificação agendada
                                para dia <strong>28/11</strong>.
                            </div>
                        </div>
                    </div>

                    <div class="kpi">
                        <div class="kpi__head">
                            <span class="kpi__tag">🌿 Vegetação</span>
                            <span class="kpi__status ok">Cuidada</span>
                        </div>
                        <div class="kpi__body">
                            <div class="kpi__value">Boa</div>
                            <div class="kpi__hint">
                                Zona do charco limpa, plantas cortadas e sem ramos a obstruir o acesso.
                            </div>
                        </div>
                    </div>

                    <div class="kpi">
                        <div class="kpi__head">
                            <span class="kpi__tag">🧹 Limpeza</span>
                            <span class="kpi__status ok">Concluída</span>
                        </div>
                        <div class="kpi__body">
                            <div class="kpi__value">Sem lixo</div>
                            <div class="kpi__hint">
                                Última limpeza geral: <strong>01/03</strong>, pela turma <strong>10.º C</strong>.
                            </div>
                        </div>
                    </div>

                    <div class="kpi">
                        <div class="kpi__head">
                            <span class="kpi__tag">♿ Acessibilidade</span>
                            <span class="kpi__status warn">A melhorar</span>
                        </div>
                        <div class="kpi__body">
                            <div class="kpi__value">Parcial</div>
                            <div class="kpi__hint">
                                Caminho principal livre, mas ainda existem pedras soltas na zona sul.
                            </div>
                        </div>

                    </div>
                    <div class="kpi">
                        <div class="kpi__head">
                            <span class="kpi__tag">♿ gghgh</span>
                            <span class="kpi__status warn">A melhorar</span>
                        </div>
                        <div class="kpi__body">
                            <div class="kpi__value">Parcial</div>
                            <div class="kpi__hint">
                                Caminho principal livre, mas ainda existem pedras soltas na zona sul.
                            </div>
                        </div>

                    </div>
                    <div class="kpi">
                        <div class="kpi__head">
                            <span class="kpi__tag">♿ rtrth</span>
                            <span class="kpi__status warn">A melhorar</span>
                        </div>
                        <div class="kpi__body">
                            <div class="kpi__value">Parcial</div>
                            <div class="kpi__hint">
                                Caminho principal livre, mas ainda existem pedras soltas na zona sul.
                            </div>
                        </div>

                    </div>
                </div>
            </article>
        </section>
        <section class="charco-panel manut-panel">
            <h3>📋 Registo de tarefas de manutenção (exemplo fictício)</h3>

            <p class="manut-intro">
                Tarefas realizadas no charco.
            </p>

            <div class="manut-table-wrapper">
                <table class="manut-table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Tarefa</th>
                            <th>Local</th>
                            <th>Responsável</th>
                            <th>Turma / Grupo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>01/03/2025</td>
                            <td>🧹 Limpeza de folhas à superfície do charco</td>
                            <td>Charco – zona norte</td>
                            <td>Profª Ana Cristina</td>
                            <td>Alunos NEE – Grupo 1</td>
                            <td><span class="tag-ok">Concluída</span></td>
                        </tr>
                        <tr>
                            <td>05/03/2025</td>
                            <td>🌿 Corte de ervas altas junto ao caminho</td>
                            <td>Charco – acesso principal</td>
                            <td>Profª Carla Coutinho</td>
                            <td>10.º C</td>
                            <td><span class="tag-ok">Concluída</span></td>
                        </tr>
                        <tr>
                            <td>08/03/2025</td>
                            <td>💧 Medição do nível de água e registo na plataforma</td>
                            <td>Charco – ponto de medição</td>
                            <td>Profª Paula Nascimento</td>
                            <td>Alunos NEE – Grupo 2</td>
                            <td><span class="tag-pend">A vigiar</span></td>
                        </tr>
                        <tr>
                            <td>10/03/2025</td>
                            <td>🪑 Verificação da segurança do corrimão</td>
                            <td>Charco – plataforma de observação</td>
                            <td>Profª Ana Cristina</td>
                            <td>10.º D</td>
                            <td><span class="tag-pend">Planeado</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p class="manut-legenda">
                ✅ <strong>Concluída:</strong> tarefa terminada.
                🟡 <strong>A vigiar / Planeado:</strong> tarefa em preparação ou que precisa de nova observação.
            </p>
        </section>
    </main>
    <?php include("rodape.php"); ?>
</body>

</html>