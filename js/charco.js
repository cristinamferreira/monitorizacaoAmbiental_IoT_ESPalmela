(function () {
    const SIMULAR = true;

    const TH = {
        tempAr: { ok: [10, 30], low: 5, high: 35 },
        tempAgua: { ok: [8, 28], low: 6, high: 30 },
        humAr: { ok: [40, 80], low: 35, high: 90 },
        lux: { ok: [1000, 20000], high: 60000 },
        part: { okBelow: 20, warnFrom: 21, warnTo: 44, critFrom: 45 },
        nivelAgua: { ok: [12, 20], low: 10, high: 30 }
    };

    const $ = id => document.getElementById(id);

    function setEstado(el, classe, label) {
        if (!el) return;
        el.className = "kpi__status " + (classe || "ok");
        el.textContent = label || "Ideal";
    }

    function faixa(v, [min, max]) {
        if (!isFinite(v)) return "na";
        if (v < min) return "baixo";
        if (v > max) return "alto";
        return "ok";
    }

    function nowStr() {
        const d = new Date();
        return {
            data: d.toLocaleDateString("pt-PT"),
            hora: d.toLocaleTimeString("pt-PT", { hour: "2-digit", minute: "2-digit" })
        };
    }
    function checklistKey() {
        const d = new Date();
        const iso = d.toISOString().slice(0, 10);
        return "charcoChecklist:" + iso;
    }
    function loadChecklist() {
        try { return JSON.parse(localStorage.getItem(checklistKey()) || "{}"); }
        catch { return {}; }
    }
    function saveChecklist(state) {
        localStorage.setItem(checklistKey(), JSON.stringify(state || {}));
    }

    function gerarAcoesNEE(vals) {
        const acoes = [];

        if (isFinite(vals.nivelAgua)) {
            if (vals.nivelAgua < TH.nivelAgua.low) {
                acoes.push("Repor água do charco com baldes/torneira (até ~15 cm).");
                acoes.push("Verificar se há fugas nas margens ou evaporação excessiva.");
            } else if (vals.nivelAgua > TH.nivelAgua.high) {
                acoes.push("Verter excedente com cuidado para balde ou abrir ligeiro escoamento.");
                acoes.push("Confirmar se a chuva encheu demasiado.");
            }
        }

        if (isFinite(vals.tempAgua)) {
            if (vals.tempAgua > TH.tempAgua.high) {
                acoes.push("Colocar sombra parcial (tela/ramos) sobre o charco.");
                acoes.push("Adicionar lentamente um pouco de água mais fresca (sem cloro).");
            } else if (vals.tempAgua < TH.tempAgua.low) {
                acoes.push("Evitar mexer nos animais/plantas; apenas observar.");
            }
        }

        if (isFinite(vals.lux) && vals.lux > TH.lux.high) {
            acoes.push("Instalar sombra parcial para reduzir luz direta.");
        }

        if (isFinite(vals.humAr) && vals.humAr < TH.humAr.low) {
            acoes.push("Nebulizar ligeiramente plantas das margens (não mexer na água).");
        }

        if (isFinite(vals.part)) {
            if (vals.part >= TH.part.critFrom) {
                acoes.push("Recolher folhas/lixo nas margens (usar luvas).");
                acoes.push("Verificar fontes de poeira próximas (caminho/obras).");
            } else if (vals.part >= TH.part.warnFrom && vals.part <= TH.part.warnTo) {
                acoes.push("Varredura leve nas margens para reduzir poeiras.");
            }
        }

        if (isFinite(vals.tempAr)) {
            if (vals.tempAr < TH.tempAr.low) {
                acoes.push("Atividade curta ao ar livre; observar leitura e regressar à sala.");
            } else if (vals.tempAr > TH.tempAr.high) {
                acoes.push("Trabalhar à sombra e hidratar; evitar exposição longa.");
            }
        }
        const precisaTrabalhoFisico = acoes.some(t =>
            /repor água|verter excedente|abrir.*escoamento|varredura|recolher folhas|lixo|instalar sombra|nebulizar|adicionar.*água/i.test(t)
        );

        if (precisaTrabalhoFisico) {
            acoes.unshift("Confirmar EPI: luvas, colete, óculos (se necessário) e calçado fechado.");
            acoes.push("Fazer registo fotográfico do antes/depois e anexar ao relatório.");
        }

        if (!acoes.length) acoes.push("Observação guiada: identificar flora/fauna e observar leituras.");
        return acoes;
    }

    function rotularEstados(vals) {
        {
            const s = faixa(vals.tempAr, TH.tempAr.ok);
            setEstado($("charcoTempArEstado"),
                s === "ok" ? "ok" : (s === "baixo" || vals.tempAr < TH.tempAr.low) ? "warn"
                    : (vals.tempAr > TH.tempAr.high) ? "crit" : "warn",
                s === "ok" ? "Ideal" : (vals.tempAr < TH.tempAr.low) ? "Baixo" : "Alto"
            );
        }
        {
            const s = faixa(vals.tempAgua, TH.tempAgua.ok);
            setEstado($("charcoTempAguaEstado"),
                s === "ok" ? "ok" : (vals.tempAgua > TH.tempAgua.high) ? "crit"
                    : (vals.tempAgua < TH.tempAgua.low) ? "warn" : "warn",
                s === "ok" ? "Ideal" : (vals.tempAgua > TH.tempAgua.high) ? "Elevada" : "Baixa"
            );
        }
        {
            const s = faixa(vals.humAr, TH.humAr.ok);
            setEstado($("charcoHumArEstado"),
                s === "ok" ? "ok" : (s === "baixo" ? "warn" : "crit"),
                s === "ok" ? "Ideal" : (s === "baixo" ? "Seco" : "Muito húmido")
            );
        }
        {
            const s = faixa(vals.lux, TH.lux.ok);
            setEstado($("charcoLuxEstado"),
                s === "ok" ? "ok" : (vals.lux > TH.lux.high ? "crit" : "warn"),
                s === "ok" ? "Ideal" : (vals.lux > TH.lux.high ? "Sol direto" : "Baixa")
            );
        }
        {
            const pm = vals.part;
            let classe = "", label = "—";
            if (isFinite(pm)) {
                if (pm >= TH.part.critFrom) { classe = "crit"; label = "Acima OMS"; }
                else if (pm >= TH.part.warnFrom && pm <= TH.part.warnTo) { classe = "warn"; label = "Alerta"; }
                else if (pm < TH.part.okBelow) { classe = "ok"; label = "Ar Limpo"; }
                else { classe = "warn"; label = "Alerta"; } // pm == 20 cai aqui se quiseres tratar como aviso
            }
            setEstado($("charcoPartEstado"), classe, label);
        }
        {
            const v = vals.nivelAgua;
            let classe = "ok", label = "Normal";
            if (isFinite(v) && v < TH.nivelAgua.low) { classe = "crit"; label = "Baixo"; }
            else if (isFinite(v) && v > TH.nivelAgua.high) { classe = "warn"; label = "Alto"; }
            setEstado($("charcoNivelAguaEstado"), classe, label);
        }
    }

    function popular(valores) {
        const set = (id, text) => { const el = $(id); if (el) el.textContent = text; };
        const n1 = v => (isFinite(v) ? Number(v).toFixed(1) : "--");

        set("charcoTempArValor", n1(valores.tempAr));
        set("charcoTempAguaValor", n1(valores.tempAgua));
        set("charcoHumArValor", n1(valores.humAr));
        set("charcoLuxValor", isFinite(valores.lux) ? String(Math.round(valores.lux)) : "--");
        set("charcoPartValor", n1(valores.part));
        set("charcoNivelAguaValor", n1(valores.nivelAgua));

        rotularEstados(valores);

        const acoes = gerarAcoesNEE(valores);
        if (typeof renderAcoesLista === "function") {
            renderAcoesLista(acoes);
        } else {
            const ul = $("charcoAcoes");
            if (ul) {
                ul.innerHTML = "";
                acoes.forEach(txt => {
                    const li = document.createElement("li");
                    li.textContent = txt;
                    ul.appendChild(li);
                });
            }
        }

        const { data, hora } = nowStr();
        set("charcoData", data);
        set("charcoHora", hora);

        const textoAcoes = acoes.join(" | ");
        let msg = "🌿 Ideal: condições gerais favoráveis.";
        if (/Repor água|Acima OMS|Sol direto|Elevad[ao]|nível.*Baixo|Muito húmido|Muito seco/i.test(textoAcoes)) {
            msg = "⚠️ Atenção: executar as ações indicadas.";
        } else if (/Nebulizar|Varredura|Observação|Instalar sombra|Varredura leve/i.test(textoAcoes)) {
            msg = "ℹ️ Observação: pequenas ações de manutenção recomendadas.";
        }
        set("mensagemEstadoCharco", msg);
    }

    function renderAcoesLista(acoes) {
        const ul = document.getElementById("charcoAcoes");
        if (!ul) return;
        const state = loadChecklist();
        ul.innerHTML = "";

        acoes.forEach((txt, idx) => {
            const id = "acao_" + idx;
            const li = document.createElement("li");
            li.style.listStyle = "none";
            li.style.margin = "4px 0";

            const label = document.createElement("label");
            label.setAttribute("for", id);
            label.style.display = "flex";
            label.style.alignItems = "center";
            label.style.gap = "8px";

            const cb = document.createElement("input");
            cb.type = "checkbox";
            cb.id = id;
            cb.checked = !!state[id];

            cb.addEventListener("change", () => {
                const s = loadChecklist();
                s[id] = cb.checked;
                saveChecklist(s);
            });

            const span = document.createElement("span");
            span.textContent = txt;

            if (/Confirmar EPI/i.test(txt)) span.style.fontWeight = "800";
            if (/registo fotográfico/i.test(txt)) span.style.fontStyle = "italic";

            label.appendChild(cb);
            label.appendChild(span);
            li.appendChild(label);
            ul.appendChild(li);
        });

        const btn = document.getElementById("charcoAcoesReset");
        if (btn) {
            btn.onclick = () => {
                localStorage.removeItem(checklistKey());
                renderAcoesLista(acoes);
            };
        }
    }
    function simular() {
        const h = new Date().getHours();
        const tempAr = 8 + Math.random() * 18;                 // 8–26
        const tempAgua = 7 + Math.random() * 16;                 // 7–23
        const humAr = 50 + Math.sin(h / 24 * Math.PI) * 25 + (Math.random() * 8 - 4);
        const lux = Math.max(0, (h > 7 && h < 19) ? 1200 + Math.random() * 16000 : Math.random() * 120);
        const part = Math.max(2, 10 + (Math.random() * 20 - 10)); // 2–30
        const nivelAgua = 9 + Math.random() * 16;                 // 9–25 
        return { tempAr, tempAgua, humAr, lux, part, nivelAgua };
    }

    async function fetchReal() {
        return simular();
    }

    async function tick() {
        const vals = SIMULAR ? simular() : await fetchReal();
        popular(vals);
    }

    if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", tick);
    else tick();
    setInterval(tick, 10000);
})();

(function () {
    const root = document.querySelector(".charco-carousel");
    if (!root) return;

    const track = root.querySelector(".charco-carousel__track");
    const slides = Array.from(root.querySelectorAll(".charco-carousel__slide"));
    const btnPrev = root.querySelector(".charco-carousel__btn--prev");
    const btnNext = root.querySelector(".charco-carousel__btn--next");
    const dotsWrap = root.querySelector(".charco-carousel__dots");

    let index = 0;
    const last = slides.length - 1;
    const AUTOPLAY_MS = 4000;
    let timer = null, isHover = false;

    const dots = slides.map((_, i) => {
        const b = document.createElement("button");
        if (i === 0) b.classList.add("is-active");
        b.addEventListener("click", () => goTo(i, true));
        dotsWrap.appendChild(b);
        return b;
    });

    function update() {
        track.style.transform = `translateX(-${index * 100}%)`;
        dots.forEach((d, i) => d.classList.toggle("is-active", i === index));
    }
    function goTo(i, stopAuto) {
        index = (i + slides.length) % slides.length;
        update();
        if (stopAuto) restartAuto();
    }
    function next() { goTo(index + 1, true); }
    function prev() { goTo(index - 1, true); }

    btnNext.addEventListener("click", next);
    btnPrev.addEventListener("click", prev);

    function startAuto() {
        stopAuto();
        timer = setInterval(() => { if (!isHover && !document.hidden) goTo(index + 1, false); }, AUTOPLAY_MS);
    }
    function stopAuto() { if (timer) clearInterval(timer); timer = null; }
    function restartAuto() { startAuto(); }

    root.addEventListener("mouseenter", () => { isHover = true; });
    root.addEventListener("mouseleave", () => { isHover = false; });
    document.addEventListener("visibilitychange", () => { if (document.hidden) stopAuto(); else startAuto(); });

    let startX = 0, dx = 0, dragging = false;
    track.addEventListener("touchstart", (e) => { startX = e.touches[0].clientX; dragging = true; stopAuto(); }, { passive: true });
    track.addEventListener("touchmove", (e) => { if (!dragging) return; dx = e.touches[0].clientX - startX; }, { passive: true });
    track.addEventListener("touchend", () => {
        if (Math.abs(dx) > 40) { (dx < 0 ? next() : prev()); }
        dx = 0; dragging = false; startAuto();
    });

    update();
    startAuto();
})();

