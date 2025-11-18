const toggleBtn = document.querySelector('.menu-toggle');
const navLinks = document.querySelector('.nav-links');

toggleBtn.addEventListener('click', () => {
    navLinks.classList.toggle('show');
});

document.addEventListener("DOMContentLoaded", function () {
    const menuIcon = document.getElementById('menu-icon');
    const navLinks = document.querySelector('.nav-links');
    const btnTopo = document.getElementById("btnTopo");

    if (menuIcon && navLinks) {
        menuIcon.addEventListener('click', () => {
            menuIcon.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
    }

    if (btnTopo) {
        window.addEventListener("scroll", toggleBtnTopoVisibility);
        btnTopo.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }
});
function toggleBtnTopoVisibility() {
    const btnTopo = document.getElementById("btnTopo");
    if (btnTopo) {
        btnTopo.style.display = window.scrollY > 300 ? "block" : "none";
    }
}


function scrollToSection(id) {
    const section = document.getElementById(id);
    if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
    }
}

function fecharTabelaPopup() {
    document.getElementById("popupTabela").style.display = "none";
}

function abrirTabelaPopup() {
    document.getElementById("popupTabela").style.display = "flex";
    fetch('get_dados_tabela.php')
        .then(response => response.json())
        .then(data => {
            const corpo = document.getElementById("corpo-tabela");
            corpo.innerHTML = "";
            data.forEach(reg => {
                const linha = `<tr>
                    <td>${reg.data_hora}</td>
                    <td>${reg.temperatura}</td>
                    <td>${reg.humidade}</td>
                    <td>${reg.pressao}</td>
                    <td>${reg.particulas}</td>
                    <td>${reg.lux}</td>
                    <td>${reg.gas}</td>
                    <td>${reg.sismo_si}</td>
                    <td>${reg.sismo_pga}</td>
                </tr>`;
                corpo.innerHTML += linha;
            });
            document.getElementById("popupTabela").scrollIntoView({ behavior: 'smooth' });
        })
        .catch(error => {
            console.error("Erro ao carregar dados:", error);
        });
}

function gerarPDFTab() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();

    const logoImg = new Image();
    logoImg.src = 'imagens/sala_aula.png';

    logoImg.onload = function () {
        doc.addImage(logoImg, 'PNG', 15, 10, 25, 25);

        doc.setFontSize(16);
        doc.text("Tabela dos Dados da Monitorização IoT Ambiental", 45, 25);

        doc.autoTable({
            html: '#tabela-dados',
            startY: 40,
            margin: { top: 30, bottom: 30 },
            didDrawPage: function () {
                const strFooter1 = "Tecnologia a cuidar do ambiente  |  Equipa LoRa – IoT Ambiental";
                const strFooter2 = "Ana Cristina Ferreira & Carla Coutinho – © 2025, Escola Secundária de Palmela";

                doc.setFontSize(10);
                doc.text(strFooter1, pageWidth / 2, pageHeight - 20, { align: "center" });

                doc.setFontSize(9);
                doc.text(strFooter2, pageWidth / 2, pageHeight - 13, { align: "center" });

                const pageNumber = doc.internal.getNumberOfPages();
                doc.text(`Página ${pageNumber}`, pageWidth - 30, pageHeight - 10);
            }
        });

        const pageCount = doc.internal.getNumberOfPages();
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            doc.setFontSize(9);
            doc.text(`Página ${i} de ${pageCount}`, pageWidth - 30, pageHeight - 10);
        }

        const agora = new Date();
        const dataHora = agora.toISOString().slice(0, 19).replace(/[:T]/g, "_");
        doc.save(`tabela_dados_${dataHora}.pdf`);
    };
}

async function exportarCSV() {
    const resp = await fetch('dados.php');
    const data = await resp.json();

    const historico = data.historico;

    const cabecalho = ['Temperatura', 'Pressao', 'Humidade', 'Gas', 'Lux', 'Particulas', 'Sismo SI', 'Sismo PGA', 'Data Hora'];

    const linhas = historico.map(item => [
        item.temperatura, item.pressao, item.humidade, item.gas, item.lux,
        item.particulas, item.sismo_si, item.sismo_pga, item.data_hora
    ]);

    const csvArray = [cabecalho, ...linhas];
    const csvString = csvArray.map(e => e.join(",")).join("\n");

    const agora = new Date();
    const pad = n => n.toString().padStart(2, '0');
    const dataStr = `${pad(agora.getDate())}-${pad(agora.getMonth() + 1)}-${agora.getFullYear()}`;
    const horaStr = `${pad(agora.getHours())}-${pad(agora.getMinutes())}`;
    const nomeFicheiro = `iotAmbiental_${dataStr}_${horaStr}.csv`;

    const blob = new Blob([csvString], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = nomeFicheiro;
    a.click();
    URL.revokeObjectURL(url);
}
