window.onbeforeunload = function () {
    window.scrollTo(0, 0);
};
document.getElementById('botaoPDF').addEventListener('click', async () => {
    while (!window.chartReady) {
        await new Promise(res => setTimeout(res, 100));
    }
    gerarPDF();
});

async function inicializaDia() {
    document.getElementById('filtrarBtn').addEventListener('click', async (event) => {
        event.preventDefault();

        const dataSelecionada = document.getElementById('data').value;
        const periodo = document.getElementById('periodo').value;

        if (!dataSelecionada) {
            alert("Por favor, selecione uma data.");
            return;
        }

        const mensagemInicial = document.getElementById("mensagemInicial");
        const mensagemSemDados = document.getElementById("mensagemSemDados");

        if (mensagemInicial) {
            mensagemInicial.style.opacity = "1";
            mensagemInicial.style.transition = "opacity 0.5s ease-out";
            mensagemInicial.style.opacity = "0";
            setTimeout(() => {
                mensagemInicial.style.display = "none";
            }, 500);
        }

        try {
            document.getElementById("loadingSpinner").style.display = "block";
            const res = await fetch(`dados_dia.php?data=${dataSelecionada}&periodo=${periodo}`);
            document.getElementById("loadingSpinner").style.display = "none";

            const dados = await res.json();

            const horas = dados.map(item => item.datahoraDia.split(' ')[1].slice(0, 5));
            const temperaturas = dados.map(item => item.tempDia);
            const humidades = dados.map(item => item.humidadeDia);
            const pressoes = dados.map(item => item.pressaoDia);
            const luzes = dados.map(item => item.luxDia);
            const gases = dados.map(item => item.gasDia);
            const particulas = dados.map(item => item.particulasDia);

            if (dados.length === 0) {
                document.getElementById("mensagemSemDados").style.display = "block";
                document.getElementById("graficoDia").style.display = "none";
                document.getElementById("graficoTempHumPart").style.display = "none";
                document.getElementById("graficoPressGasLuz").style.display = "none";
                return;
            } else {
                document.getElementById("mensagemSemDados").style.display = "none";
                limparGrafico("graficoDia");
                limparGrafico("graficoTempHumPart");
                limparGrafico("graficoPressGasLuz");
                desenharGraficoDiaTempHumPartGas(horas, temperaturas, humidades, particulas, gases, dataSelecionada, periodo);
                desenharGraficoDiaPressLuz(horas, pressoes, luzes, dataSelecionada, periodo);
                document.getElementById("graficoTempHumPartGas").style.display = "block";
                document.getElementById("graficoPressLuz").style.display = "block";
            }
        } catch (erro) {
            console.error("Erro ao carregar dados:", erro);
        }
    });
}
function limparGrafico(canvasId) {
    const canvas = document.getElementById(canvasId);
    if (canvas) {
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
    } else {
        console.warn(`Canvas com id "${canvasId}" não encontrado.`);
    }
}
function desenharGraficoDiaTempHumPartGas(horas, temperaturas, humidades, particulas, gases, data, periodo) {
    const ctx = document.getElementById('graficoTempHumPartGas').getContext('2d');

    if (window.graficoTemp) {
        window.graficoTemp.destroy();
    }

    window.graficoTemp = new Chart(ctx, {
        type: 'line',
        data: {
            labels: horas,
            datasets: [
                {
                    label: 'Temperatura (°C)',
                    data: temperaturas,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: false,
                },
                {
                    label: 'Humidade (%)',
                    data: humidades,
                    borderColor: 'rgb(25, 136, 211)',
                    fill: false,
                },
                {
                    label: 'Gás (ppm)',
                    data: gases,
                    borderColor: 'rgb(122, 253, 231)',
                    fill: false,
                },
                {
                    label: 'Partículas',
                    data: particulas,
                    borderColor: 'rgba(255, 159, 64, 1)',
                    fill: false,
                }
            ]
        },
        options: {
            responsive: true,
            animation: {
                onComplete: () => {
                    window.chartReady = true;
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: `Monitorização Temp_Hum_Part_Gas - ${data} no período da ${periodo}`
                }
            },
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'Valores Sensores'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Hora'
                    }
                }
            }
        }
    });
}

function desenharGraficoDiaPressLuz(horas, pressoes, luzes, data, periodo) {
    const ctx = document.getElementById('graficoPressLuz').getContext('2d');

    if (window.graficoPress) {
        window.graficoPress.destroy();
    }

    window.graficoPress = new Chart(ctx, {
        type: 'line',
        data: {
            labels: horas,
            datasets: [
                {
                    label: 'Pressão (hPa)',
                    data: pressoes,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    fill: false,
                },
                {
                    label: 'Luz (Lux)',
                    data: luzes,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    fill: false,
                },
            ]
        },
        options: {
            responsive: true,
            animation: {
                onComplete: () => {
                    window.chartReady = true;
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: `Monitorização Press_Luz - ${data} no período da ${periodo}`
                }
            },
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'Valores Sensores'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Hora'
                    }
                }
            }
        }
    });
}

function desenharGraficoDia(horas, temperaturas, humidades, pressoes, luzes, gases, particulas, data, periodo) {
    const ctx = document.getElementById('graficoDia').getContext('2d');

    if (window.graficoT) {
        window.graficoT.destroy();
    }

    window.graficoT = new Chart(ctx, {
        type: 'line',
        data: {
            labels: horas,
            datasets: [
                {
                    label: 'Temperatura (°C)',
                    data: temperaturas,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    fill: false,
                },
                {
                    label: 'Humidade (%)',
                    data: humidades,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false,
                },
                {
                    label: 'Pressão (hPa)',
                    data: pressoes,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    fill: false,
                },
                {
                    label: 'Luz (Lux)',
                    data: luzes,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    fill: false,
                },
                {
                    label: 'Gás (ppm)',
                    data: gases,
                    borderColor: 'rgba(255, 205, 86, 1)',
                    fill: false,
                },
                {
                    label: 'Partículas',
                    data: particulas,
                    borderColor: 'rgba(255, 159, 64, 1)',
                    fill: false,
                }
            ]
        },
        options: {
            responsive: true,

            plugins: {
                title: {
                    display: true,
                    text: `Monitorização Total - ${data} no período da ${periodo}`
                }
            },
            scales: {
                y: {
                    title: {
                        display: true,
                        text: 'Leitura dos sensores'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Hora'
                    }
                },
            },
            animation: {
                onComplete: () => {
                    window.chartReady = true;
                }
            }
        }
    });
}

async function inicializaSemanalMensal() {
    try {
        const res = await fetch('dados.php');
        const data = await res.json();
        console.log("Dados recebidos:", data);

        const dadosSemanais = data.dadosSemanal;
        const dadosMensais = data.dadosMensal;

        const { semanas, temp, pressao, humidade, lux, particulas, gas } = extrairDados(dadosSemanais);

        desenhaGraficoSemanalA(semanas, temp, humidade, particulas, gas);
        desenhaGraficoSemanalB(semanas, pressao, lux);

        const dadosMensaisFormatted = dadosMensais[0];
        desenhaGraficoMensalA(dadosMensaisFormatted);
        desenhaGraficoMensalB(dadosMensaisFormatted);

    } catch (error) {
        console.error("Erro ao buscar os dados:", error);
    }
}

function extrairDados(dados) {
    const semanas = dados.map(item => 'Semana ' + item.semana);
    const temp = dados.map(item => item.avgTemp);
    const pressao = dados.map(item => item.avgPress);
    const humidade = dados.map(item => item.avgHum);
    const lux = dados.map(item => item.avgLux);
    const particulas = dados.map(item => item.avgPart);
    const gas = dados.map(item => item.avgGas);

    return { semanas, temp, pressao, humidade, lux, particulas, gas };
}

function desenhaGraficoSemanalA(semanas, temp, humidade, particulas, gas) {
    window.chartReady = false;
    const ctx = document.getElementById('graficoSemanalA').getContext('2d');
    if (window.graficoSemA) window.graficoSemA.destroy();

    window.graficoSemA = new Chart(ctx, {
        type: 'line',
        data: {
            labels: semanas,
            datasets: [
                { label: 'Temperatura (°C)', data: temp, borderColor: 'rgba(255, 99, 132, 1)', fill: false },
                { label: 'Humidade (%)', data: humidade, borderColor: 'rgba(75, 192, 192, 1)', fill: false },
                { label: 'Partículas', data: particulas, borderColor: 'rgba(255, 159, 64, 1)', fill: false },
                { label: 'Gás (ppm)', data: gas, borderColor: 'rgba(255, 205, 86, 1)', fill: false }
            ]
        },
        options: {
            responsive: true,
            animation: {
                onComplete: () => {
                    window.chartReady = true;
                }
            }
        }
    });
}

function desenhaGraficoSemanalB(semanas, pressao, lux) {
    window.chartReady = false;

    const ctx = document.getElementById('graficoSemanalB').getContext('2d');
    if (window.graficoSemB) window.graficoSemB.destroy();

    window.graficoSemB = new Chart(ctx, {
        type: 'line',
        data: {
            labels: semanas,
            datasets: [
                { label: 'Pressão (hPa)', data: pressao, borderColor: 'rgba(54, 162, 235, 1)', fill: false },
                { label: 'Luz (Lux)', data: lux, borderColor: 'rgba(153, 102, 255, 1)', fill: false }
            ]
        },
        options: {
            responsive: true,
            animation: {
                onComplete: () => {
                    window.chartReady = true;
                }
            }
        }
    });
}

function desenhaGraficoMensalA(dadosMensais) {
    window.chartReady = false;
    const ctx = document.getElementById('graficoMensalA').getContext('2d');

    if (window.graficoMenA) window.graficoSemA.destroy();

    window.graficoMenA = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Temp', 'Partículas', 'Humidade', 'Gás'],
            datasets: [{
                data: [
                    dadosMensais.avgTemp,
                    dadosMensais.avgPart,
                    dadosMensais.avgHum,
                    dadosMensais.avgGas
                ],
                backgroundColor: [
                    'rgba(255,99,132,0.6)',
                    'rgba(54,162,235,0.6)',
                    'rgba(75,192,192,0.6)',
                    'rgba(255,159,64,0.6)',
                ]
            }]
        },
        options: {
            responsive: true,
            animation: {
                onComplete: () => {
                    window.chartReady = true;
                }
            },
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Média Mensal: Temp-Hum-Part-Gás'
                },
            }
        }

    });
}
function desenhaGraficoMensalB(dadosMensais) {
    window.chartReady = false;
    const ctx = document.getElementById('graficoMensalB').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Lux', 'Pressão'],
            datasets: [{
                data: [
                    dadosMensais.avgLux,
                    dadosMensais.avgPress
                ],
                backgroundColor: [
                    'rgba(153,102,255,0.6)',
                    'rgba(255,205,86,0.6)'
                ]
            }]
        },
        options: {
            responsive: true,
            animation: {
                onComplete: () => {
                    window.chartReady = true;
                }
            },
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Média Mensal: Luz-Pressão'
                },
            }
        }
    });
}
window.onload = async () => {
    await inicializaSemanalMensal();
    await inicializaDia();
};
let periodoAtual = "";

function atualizarPeriodo(novoPeriodo) {
    const container = document.getElementById("periodoContainer");

    if (["manhã", "tarde", "noite"].includes(novoPeriodo)) {
        container.innerHTML = `<p>Período atual: ${novoPeriodo}</p>`;
    } else {
        container.innerHTML = `<p>Período inválido.</p>`;
    }
}
async function gerarPDF() {
    const { jsPDF } = window.jspdf;
    const dashboard = document.querySelector("#mainContent");

    const rodapeBase64 = await fetch("imagens/rodape_pdf.png")
        .then(res => res.blob())
        .then(blob => new Promise((resolve) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result);
            reader.readAsDataURL(blob);
        }));

    const canvas = await html2canvas(dashboard, { scale: 2 });
    const imgData = canvas.toDataURL("image/png");

    const pdf = new jsPDF("p", "mm", "a4");
    const pageWidth = pdf.internal.pageSize.getWidth();
    const pageHeight = pdf.internal.pageSize.getHeight();

    const imgProps = pdf.getImageProperties(imgData);
    const imgHeight = (imgProps.height * pageWidth) / imgProps.width;

    const totalPages = Math.ceil(imgHeight / pageHeight);

    for (let i = 0; i < totalPages; i++) {
        if (i > 0) pdf.addPage();

        const sourceY = (canvas.height / totalPages) * i;
        const sliceHeight = canvas.height / totalPages;

        const pageCanvas = document.createElement("canvas");
        const context = pageCanvas.getContext("2d");
        pageCanvas.width = canvas.width;
        pageCanvas.height = sliceHeight;

        context.drawImage(
            canvas,
            0,
            sourceY,
            canvas.width,
            sliceHeight,
            0,
            0,
            canvas.width,
            sliceHeight
        );

        const pageImgData = pageCanvas.toDataURL("image/png");
        pdf.addImage(pageImgData, "PNG", 0, 0, pageWidth, pageHeight - 20);

        pdf.setFontSize(8);
        pdf.text(
            "Tecnologia a cuidar do ambiente  |  Equipa LoRa – IoT Ambiental",
            14,
            pageHeight - 6
        );
        pdf.text(
            "Ana Cristina Ferreira & Carla Coutinho – © 2025, Escola Secundária de Palmela",
            14,
            pageHeight - 2
        );
        pdf.text(
            `Página ${i + 1} de ${totalPages}`,
            pageWidth - 40,
            pageHeight - 2
        );
    }

    const agora = new Date();
    const dataHora = agora.toISOString().slice(0, 19).replace(/[:T]/g, "_");
    pdf.save(`relatorio_${dataHora}.pdf`);
}
