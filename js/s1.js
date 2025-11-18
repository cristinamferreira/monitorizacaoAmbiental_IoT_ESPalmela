let latitude, longitude, mapa, marker;
let ultimoSI = null;
let ultimoPGA = null;

function inicializaMapa(lat, lon) {
    if (!mapa) {
        mapa = L.map('map').setView([lat, lon], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(mapa);

        marker = L.marker([lat, lon]).addTo(mapa)
            .bindPopup(`<b>Latitude:</b> ${lat}<br><b>Longitude:</b> ${lon}`)
            .openPopup();
    } else {
        marker.setLatLng([lat, lon])
            .setPopupContent(`<b>Latitude:</b> ${lat}<br><b>Longitude:</b> ${lon}`)
            .openPopup();
        mapa.setView([lat, lon], 13);
    }
}

function atualizaMapa(lat, lon) {
    if (lat && lon && !isNaN(lat) && !isNaN(lon)) {
        if (mapa && marker) {
            marker.setLatLng([lat, lon])
                .setPopupContent(`<b>Latitude:</b> ${lat}<br><b>Longitude:</b> ${lon}`)
                .openPopup();
            mapa.setView([lat, lon], 13);
        }
    } else {
        console.error("Coordenadas inválidas", lat, lon);
    }
}
let isUpdating = false;
async function updateCharts() {
    if (isUpdating) return;
    isUpdating = true;
    try {
        const res = await fetch('dados.php');
        const data = await res.json();
        console.log("Dados recebidos:", data);
        const totalRegistos = data.totalRegistos || 0;
        const ultimaLeitura = data.ultimaLeitura;
        inicializaMapa(ultimaLeitura.latitudeAtual, ultimaLeitura.longitudeAtual);
        updateResumoValues(ultimaLeitura, data);

        const u = data.ultimaLeitura;
        atualizarEstadoAmbiente(
            Number(u.tempAtual),
            Number(u.humidadeAtual),
            Number(u.gasAtual),
            Number(u.particulasAtual),
            Number(u.luxAtual),
            Number(u.sismoSIAtual),
            Number(u.sismoPGAAtual)
        );
    } catch (error) {
        console.error("Erro ao buscar os dados:", error);
    } finally {
        isUpdating = false;
    }
}
function atualizarEstadoAmbiente(temp, hum, gas, particulas, luz, si, pga) {
    console.log("Atualizando estado ambiente com:", temp, hum, gas, particulas, luz, si, pga);

    const mensagem = document.getElementById("mensagemEstado");
    const box = document.getElementById("estadoGeral");

    if (!mensagem || !box) {
        console.error("Elementos #mensagemEstado ou #estadoGeral não encontrados.");
        return;
    }

    let estado = "ok";
    let texto = "Ambiente saudável e estável.";
    if (si >= 130 || pga >= 0.3) {
        estado = "critico";
        texto = "⛔ Emergência: atividade sísmica severa. Procure abrigo imediatamente.";
    } else if (si >= 70 || pga >= 0.2) {
        estado = "critico";
        texto = "🚨 Ambiente crítico: potencial sismo, vibração intensa detetada. Mantenha-se atento. ";
    } else if (gas > 500 || particulas > 20) {
        estado = "critico";
        texto = "🚨 Ambiente crítico: poluição elevada.";
    } else if (temp > 30 && hum < 30 && luz > 800) {
        estado = "critico";
        texto = "🚨 Ambiente muito quente, seco e com luz intensa. Risco elevado.";
    } else if (temp > 32 && hum < 25) {
        estado = "critico";
        texto = "🔥 Ambiente extremamente quente e seco. Risco de insolação.";
    } else if (gas > 300 && gas <= 500) {
        estado = "alerta";
        texto = "⚠️ Gás acima do normal. Ventile o ambiente.";
    } else if (particulas > 10 && particulas <= 20) {
        estado = "alerta";
        texto = "⚠️ Partículas no ar elevadas. Risco para pessoas sensíveis.";
    } else if (temp > 28 && hum < 40 && luz < 100) {
        estado = "alerta";
        texto = "⚠️ Ambiente quente, seco e escuro.";
    } else if (luz > 1000 && temp > 26) {
        estado = "alerta";
        texto = "🌞 Exposição solar intensa com temperatura elevada.";
    } else if (luz < 50 && temp < 18 && hum > 60) {
        estado = "alerta";
        texto = "🌫️ Ambiente escuro, húmido e frio. Baixo conforto.";
    } else if (temp < 10 && hum > 80) {
        estado = "alerta";
        texto = "❄️ Ambiente frio e húmido. Risco de bolor e desconforto.";
    } else if (temp < 18 || temp > 26 || hum > 70 || gas > 15 || particulas > 20 || si >= 25 || pga >= 0.1) {
        estado = "alerta";
        texto = "⚠️ Condições fora do ideal. Monitorize todos os parametros.";
    } else if (temp < 10 && hum > 80) {
        estado = "alerta";
        texto = "❄️ Ambiente frio e húmido. Risco de bolor e desconforto.";
    } else if (temp >= 22 && temp <= 26 && hum >= 40 && hum <= 60 && luz >= 300 && luz <= 800 && gas <= 50 && particulas <= 10 && si < 25 && pga < 0.1) {
        estado = "ok";
        texto = "🏡 Ambiente ideal: temperatura, humidade e ar equilibrados.";
    } else {
        estado = "ok";
        texto = "🏡 Ambiente saudável e estável.";
    }

    box.classList.remove("ok", "alerta", "critico");
    box.classList.add(estado);
    mensagem.innerText = texto;
}
function updateResumoValues(ultimaLeitura, data) {
    document.getElementById("dataAtualResumo").textContent = "Última atualização BD: " + ultimaLeitura.dataHoraAtual;
    document.getElementById("totalRegistosResumo").textContent = data.totalRegistos || 0;

    document.getElementById("tempResumo").textContent = ultimaLeitura.tempAtual;
    document.getElementById("humResumo").textContent = ultimaLeitura.humidadeAtual;
    document.getElementById("pressResumo").textContent = ultimaLeitura.pressaoAtual;
    document.getElementById("gasResumo").textContent = ultimaLeitura.gasAtual;
    document.getElementById("luxResumo").textContent = ultimaLeitura.luxAtual;
    document.getElementById("partResumo").textContent = ultimaLeitura.particulasAtual;
    document.getElementById("sismoSIResumo").textContent = ultimaLeitura.sismoSIAtual;
    document.getElementById("sismoPGAResumo").textContent = ultimaLeitura.sismoPGAAtual;

    document.getElementById("tempAtual").innerText = ultimaLeitura.tempAtual + " ºC";
    document.getElementById("pressaoAtual").innerText = ultimaLeitura.pressaoAtual + " hPa";
    document.getElementById("humidadeAtual").innerText = ultimaLeitura.humidadeAtual + " %";
    document.getElementById("luxAtual").innerText = ultimaLeitura.luxAtual + " lux";
    document.getElementById("gasAtual").innerText = ultimaLeitura.gasAtual + " ppm";
    document.getElementById("particulasAtual").innerText = ultimaLeitura.particulasAtual + " µg/m³";
    document.getElementById("sismoSIAtual").innerText = ultimaLeitura.sismoSIAtual + " cm/s";
    document.getElementById("sismoPGAAtual").innerText = ultimaLeitura.sismoPGAAtual + " m/s²";

    atualizarStatusTemperatura(ultimaLeitura.tempAtual);
    atualizarStatusPressao(ultimaLeitura.pressaoAtual);
    atualizarStatusGas(ultimaLeitura.gasAtual);
    atualizarStatusLuz(ultimaLeitura.luxAtual);
    atualizarStatusHumidade(ultimaLeitura.humidadeAtual);
    atualizarStatusParticulas(ultimaLeitura.particulasAtual);
    atualizarStatusSismoSI(ultimaLeitura.sismoSIAtual);
    atualizarStatusSismoPGA(ultimaLeitura.sismoPGAAtual);
}

function atualizarDataHora() {
    const agora = new Date();
    const data = agora.toLocaleDateString();
    const hora = agora.toLocaleTimeString();

    const dataEl = document.getElementById("dataAtual");
    const horaEl = document.getElementById("horaAtual");

    if (dataEl) dataEl.innerText = data;
    if (horaEl) horaEl.innerText = hora;
}

window.onload = async () => {
    atualizarDataHora();
    toggleMenu();
    await updateCharts();
    //    setInterval(updateCharts, 60000); // Atualiza a cada 60 segundos
};
function removerLinha(cod) {

    if (confirm('Tem certeza que deseja remover esta linha?')) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'remover_linha.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status == 200) {
                document.getElementById('linha-' + cod).remove();
                alert('Dado removido com sucesso!');
            } else {
                alert('Erro ao remover o dado.');
            }
        };
        xhr.send('cod_monitoriza=' + cod);
    }
}
function atualizarStatusPressao(valor) {
    const corPrss = getBarColor(valor, "Pressao");
    const textoPrss = getTextColor(valor, "Pressao");

    document.getElementById("card-press").style.backgroundColor = corPrss;
    document.getElementById("texto-press").textContent = textoPrss;

    document.getElementById("pressaoAtual").textContent = valor + " hPa";
}

function atualizarStatusTemperatura(valor) {
    const corTemp = getBarColor(valor, "Temperatura");
    const textoTemp = getTextColor(valor, "Temperatura");

    document.getElementById("card-temp").style.backgroundColor = corTemp;
    document.getElementById("texto-temp").textContent = textoTemp;

    document.getElementById("tempAtual").textContent = valor + " °C";
}

function atualizarStatusHumidade(valor) {

    const corHum = getBarColor(valor, "Humidade");
    const textoHum = getTextColor(valor, "Humidade");
    const cardItem = document.getElementById("card-hum");
    const textoItem = document.getElementById("texto-hum");
    const humidadeAtual = document.getElementById("humidadeAtual");

    if (cardItem && textoItem && humidadeAtual) {
        cardItem.style.backgroundColor = corHum;
        textoItem.textContent = textoHum;
        humidadeAtual.textContent = valor + " %";
    } else {
        console.error("Elementos não encontrados no DOM!");
    }
}

function atualizarStatusLuz(valor) {

    const corTemp = getBarColor(valor, "Luz");
    const textoTemp = getTextColor(valor, "Luz");
    document.getElementById("card-luz").style.backgroundColor = corTemp;
    document.getElementById("texto-luz").textContent = textoTemp;
    document.getElementById("luxAtual").textContent = valor + " lux";
}
function atualizarStatusParticulas(valor) {
    const corTemp = getBarColor(valor, "Particulas");
    const textoTemp = getTextColor(valor, "Particulas");
    document.getElementById("card-part").style.backgroundColor = corTemp;
    document.getElementById("texto-partic").textContent = textoTemp;
    document.getElementById("particulasAtual").textContent = valor + " µg/m³";
}

function atualizarStatusGas(valor) {
    const corTemp = getBarColor(valor, "Gas");
    const textoTemp = getTextColor(valor, "Gas");
    document.getElementById("card-gas").style.backgroundColor = corTemp;
    document.getElementById("texto-gas").textContent = textoTemp;
    document.getElementById("gasAtual").textContent = valor + " ppm";
}
function atualizarStatusSismoSI(si) {
    ultimoSI = Number(si);

    const corSI = getBarColor(ultimoSI, "Sismo_SI");
    const textoSI = getTextColor(ultimoSI, "Sismo_SI");

    const card = document.getElementById("card-si");
    const texto = document.getElementById("texto-si");
    const spanValor = document.getElementById("sismoSIAtual");

    if (card) card.style.backgroundColor = corSI;
    if (texto) texto.textContent = textoSI;
    if (spanValor) spanValor.textContent = ultimoSI + " cm/s";

    if (ultimoPGA !== null) {
        atualizarRichterGlobal(ultimoSI, ultimoPGA);
    }
}

function atualizarStatusSismoPGA(pga) {
    ultimoPGA = Number(pga);

    const corPGA = getBarColor(ultimoPGA, "Sismo_PGA");
    const textoPGA = getTextColor(ultimoPGA, "Sismo_PGA");

    const card = document.getElementById("card-pga");
    const texto = document.getElementById("texto-pga");
    const spanValor = document.getElementById("sismoPGAAtual");

    if (card) card.style.backgroundColor = corPGA;
    if (texto) texto.textContent = textoPGA;
    if (spanValor) spanValor.textContent = ultimoPGA + " m/s²";

    if (ultimoSI !== null) {
        atualizarRichterGlobal(ultimoSI, ultimoPGA);
    }
}
function getBarColor(valor, tipo) {
    switch (tipo) {
        case "Temperatura":
            if (valor < 18) return "#3c8dbc";
            if (valor < 26) return "#00a65a";
            if (valor < 30) return "#f39c12";
            return "#dd4b39";

        case "Humidade":
            if (valor > 80) return "#3498db";
            if (valor < 30) return "#f1c40f";
            return "#2ecc71";

        case "Pressao":
            if (valor > 1020) return "#8e44ad";
            if (valor < 980) return "#e67e22";
            return "#2ecc71";

        case "Gas":
            if (valor > 700) return "#c0392b";
            if (valor > 400) return "#f39c12";
            return "#2ecc71";

        case "Luz":
            if (valor > 800) return "#f1c40f";
            if (valor < 200) return "#849ed6";
            return "#2ecc71";

        case "Particulas":
            if (valor > 100) return "#e74c3c";
            if (valor > 50) return "#f39c12";
            return "#2ecc71";
        case "Sismo_SI":
            if (valor < 25) return "#00a65a";
            if (valor < 60) return "#fae903ff";
            if (valor < 130) return "#dd8903ff";
            return "#dd4b39";
        case "Sismo_PGA":
            if (valor < 0.1) return "#2ecc71";
            if (valor < 0.2) return "#f39c12";
            return "#e74c3c";

        default:
            return "#bdc3c7"; // Valor desconhecido
    }
}

function getTextColor(valor, tipo) {
    switch (tipo) {
        case "Temperatura":
            if (valor < 18) return "🥶 Baixa";
            if (valor < 26) return "🌞 Ideal";
            if (valor < 30) return "☀️ Elevada";
            return "🔥 Muito elevada";
        case "Humidade":
            if (valor > 80) return "💧 Alta";
            if (valor < 30) return "🌵 Baixa";
            return "🌳 Ideal";

        case "Pressao":
            if (valor > 1020) return "🔴 Alta";
            if (valor < 980) return "🔵 Baixa";
            return "🟢 Ideal";

        case "Gas":
            if (valor > 700) return "⚠️ Nível Elevado";
            if (valor > 400) return "⚡ Nível Moderado";
            return "✅ Nível Baixo";

        case "Luz":
            if (valor > 800) return "💡 Intensa";
            if (valor < 200) return "🌑 Fraca";
            return "🌞 Ideal";

        case "Particulas":
            if (valor > 100) return "🌫️ Nível Elevado";
            if (valor > 50) return "🌬️ Nível Moderado";
            return "🌿 Ar Limpo";
        case "Sismo_SI":
            if (valor < 25) return "🌏 Baixo";
            if (valor < 60) return "⚡ Atenção";
            if (valor < 130) return "🚨 Alerta";
            return "⛔ Emergência";

        case "Sismo_PGA":
            if (valor < 0.1) return "🌏 Baixo";
            if (valor < 0.2) return "⚠️ Atenção";
            if (valor < 0.3) return "🚨 Alerta";
            return " ⛔ Emergência";

        default:
            return "❓ Valor Desconhecido";
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
    pdf.save(`dashboard_${dataHora}.pdf`);
}
function toggleMenu() {
    const menuIcon = document.getElementById('menu-icon');
    const navLinks = document.querySelector('.nav-links');

    if (menuIcon && navLinks) {
        menuIcon.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            menuIcon.classList.toggle('active');
        });
    }
}
function classeRichterPorSI(si) {
    if (si <= 0) return 0;  // <1.0
    if (si <= 25.0) return 1;  // ≈1.0–2.5
    if (si <= 50.0) return 2;  // ≈2.5–3.5
    if (si <= 100.0) return 3;  // ≈3.5–4.5
    if (si <= 200.0) return 4;  // ≈4.5–5.0
    if (si <= 400.0) return 5;  // ≈5.0–5.5
    return 6;                    // >6.0
}

function classeRichterPorPGA(pga) {
    if (pga <= 0) return 0;   // <1.0
    if (pga <= 0.98) return 1;   // ≈1.0–2.5
    if (pga <= 1.96) return 2;   // ≈2.5–3.5
    if (pga <= 3.92) return 3;   // ≈3.5–4.5
    if (pga <= 7.84) return 4;   // ≈4.5–5.0
    if (pga <= 10.0) return 5;   // ≈5.0–5.5
    return 6;                     // >6.0
}

function labelRichterPorClasse(classe) {
    switch (classe) {
        case 0: return "< 1.0 (muito fraco)";
        case 1: return "≈ 1.0 – 2.5 (fraco)";
        case 2: return "≈ 2.5 – 3.5 (ligeiro)";
        case 3: return "≈ 3.5 – 4.5 (moderado)";
        case 4: return "≈ 4.5 – 5.0 (forte)";
        case 5: return "≈ 5.0 – 5.5 (muito forte)";
        default: return "> 6.0 (extremo/catastrófico)";
    }
}

function atualizarRichterGlobal(si, pga) {
    const classeSI = classeRichterPorSI(si);
    const classePGA = classeRichterPorPGA(pga);

    const classeGlobal = Math.max(classeSI, classePGA);
    const label = labelRichterPorClasse(classeGlobal);

    const span = document.getElementById("richterGlobal");
    if (span) {
        span.textContent = label;
    }
}

atualizarStatusSismoSI(si);
atualizarStatusSismoPGA(pga);
atualizarRichterGlobal(si, pga);

document.addEventListener("DOMContentLoaded", toggleMenu);