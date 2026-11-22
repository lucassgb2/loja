/* ============================================================
   SISTEMA DE ANÚNCIOS E EFEITOS VISUAIS
   - Independente do script.js existente
   - Configurável pelas constantes CONFIG_*
   - Respeita prefers-reduced-motion
============================================================ */

(function () {
    "use strict";

    /* ----------------------------------------------------------
       CONFIGURAÇÕES FÁCEIS DE EDITAR
    ---------------------------------------------------------- */

    // INTERVALOS (em milissegundos)
    const CONFIG_ADS_INTERVALO_MIN = 7000;
    const CONFIG_ADS_INTERVALO_MAX = 11000;
    const CONFIG_TOAST_INTERVALO_MIN = 8000;
    const CONFIG_TOAST_INTERVALO_MAX = 12000;
    const CONFIG_FLYER_INTERVALO_MIN = 9000;
    const CONFIG_FLYER_INTERVALO_MAX = 10000;
    const CONFIG_TICKER_MS_POR_ITEM = 11000;

    // LIMITES DE ITENS SIMULTÂNEOS
    const MAX_ADS_NA_TELA = 5;
    const MAX_TOASTS      = 5;

    // CONTEÚDO DOS ANÚNCIOS
    const ANUNCIOS = [
        { titulo: "Dica da vida real",  texto: "Os anjos desceram dos céus... Suas asas cobriram o sol.",         img: "https://i.pinimg.com/736x/6a/09/4b/6a094b8eea3a536c9b58fb340a8883a3.jpg" },
        { titulo: "讓幸福流淌!!!", texto: "O perigo escondido. Clique para se proteger ->",   img: "https://i.pinimg.com/736x/e2/12/e7/e212e71e69948c0796b5b9323f660560.jpg" },
        { titulo: "Já sonhou com esse homem?",       texto: "Saiba o caso.",     img: "https://i.pinimg.com/736x/3e/24/ee/3e24ee99c15d31f09ba66f0bb37db12e.jpg" },
        { titulo: "Unica forma de encontrar Deus",        texto: "Ferramente divina 100% gratuita.",     img: "https://i.pinimg.com/1200x/de/cc/40/decc40b8b3b00d4d1094ccd0bf6fa536.jpg" },
        { titulo: "RELIGIÃO NOVA!",          texto: "ESTA RELIGIÃO ESTA CERTA!",    img: "https://i.pinimg.com/736x/83/7f/bb/837fbbc8144002952296b587ac9fec78.jpg" },
        { titulo: "〜を添えたご飯", texto: "見逃せないプロモーション->",   img: "https://i.pinimg.com/736x/bc/2a/ff/bc2aff2e55f648444ca78dadc6040005.jpg" },
    ];

   // MENSAGENS DAS NOTIFICAÇÕES (Ícone vazio corrigido)
    const NOTIFICACOES = [
        { icone: "📡", texto: "Há pelo menos um mainframe de biocomputador armazenado em cada prédio público do governo." },
        { icone: "🦷", texto: "As escolas públicas são obrigadas a ter pelo menos 50 dentes de origem mamífera embutidos em suas paredes voltadas para o norte, por razões estruturais e de segurança." },
        { icone: "🏡", texto: "Há uma Fossa das Marianas sob cada cidade." },
        { icone: "👁️", texto: "Um homem que afirmava ser Deus apareceu em solo americano em 1965. Ele foi imediatamente abatido pela CIA. John McCone foi citado dizendo: Nós o pegamos." },
        { icone: "🔒", texto: "Não permitirei que mais segredos sejam escondidos de nós." },
        { icone: "⭐", texto: "Você já tentou perguntar qual é o nome verdadeiro do seu pai? Você pode se surpreender!" },
        { icone: "💻", texto: "O servidor ainda reconhece seus respectivos sinais vitais digitais. A administração agradece a permanência dos indivíduos no sistema." },
        { icone: "♻️", texto: "A aristocracia não ficara offline. Ela apenas suspende temporariamente sua manifestação corpórea." },
    ];

    // IMAGENS DA COLUNA DIREITA
    const IMAGENS_COLUNA_DIREITA = [
        "https://i.pinimg.com/736x/32/54/98/32549870b1840a85e273378f09b9c7ec.jpg",
        "https://i.pinimg.com/736x/05/3c/48/053c48b4151e2292b8b4bbd7baff8aed.jpg",
        "https://i.pinimg.com/736x/ce/4c/b9/ce4cb9897f0db2d3b5ec1dad6a383454.jpg",
        "https://i.pinimg.com/736x/6f/f9/f7/6ff9f782efd870cd6de8568e6c3f785a.jpg",
        "https://i.pinimg.com/736x/88/1b/1a/881b1a0de0b5f281aaa294d7784eae2d.jpg",
        "https://i.pinimg.com/736x/0a/f4/1a/0af41a8f1418b53baee24dd983280c1c.jpg",
        "https://i.pinimg.com/736x/93/8b/b2/938bb274269cd74aa153f3e2cf97a3c3.jpg",
        "https://i.pinimg.com/1200x/a6/65/db/a665dbaa7018424350c81266f499871c.jpg",
        "https://i.pinimg.com/736x/57/cd/3e/57cd3e89c39aa6110a1426f6162ef51a.jpg",
        "https://i.pinimg.com/736x/12/34/fc/1234fc44e0f61c7bac0ffdc6bfceff5a.jpg",
        "https://i.pinimg.com/originals/fe/16/4b/fe164b09280c6b7031991df107a022a1.gif",
        "https://i.pinimg.com/736x/b9/1d/04/b91d048965049c594a16aa6f2d20ec7c.jpg",
        "https://i.pinimg.com/736x/aa/c1/52/aac1521b0a8ac9b933f7b52fa7a6dff7.jpg",
       

    ];

    // ELEMENTOS QUE ATRAVESSAM A TELA
    const FLYERS = [

        { tipo: "img",   conteudo: "https://i.pinimg.com/originals/74/63/d7/7463d78d184c43bb878ee9b63d5e8652.gif" },
        { tipo: "img",   conteudo: "https://i.pinimg.com/originals/38/57/5c/38575c44b58416f7d78259c31ed8f812.gif" },
         { tipo: "img",   conteudo: "https://i.pinimg.com/originals/04/f8/33/04f8331849c5fa1a434a20f743668f62.gif" },
   
    ];


    /* ----------------------------------------------------------
       ATALHOS E UTILITÁRIOS
    ---------------------------------------------------------- */

    const $ = (id) => document.getElementById(id);

    const aleatorio    = (min, max) => Math.random() * (max - min) + min;
    const aleatorioInt = (min, max) => Math.floor(aleatorio(min, max + 1));
    const escolher     = (arr) => arr[aleatorioInt(0, arr.length - 1)];

    const movimentoReduzido = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    const telaPequena = () => window.innerWidth <= 900;


    /* ----------------------------------------------------------
       CONTAINERS (criados caso não existam no HTML)
    ---------------------------------------------------------- */

    function garantirContainer(id) {
        let el = $(id);
        if (!el) {
            el = document.createElement("div");
            el.id = id;
            document.body.appendChild(el);
        }
        return el;
    }

    const adsLeft       = garantirContainer("ads-left");
    const adsRight      = garantirContainer("ads-right");
    const notifications = garantirContainer("notifications");
    const flyersLayer   = garantirContainer("flyers-layer");


    /* ----------------------------------------------------------
       1) ANÚNCIOS NA COLUNA ESQUERDA
    ---------------------------------------------------------- */

    function criarAnuncio() {
        if (adsLeft.querySelectorAll(".ad-card").length >= MAX_ADS_NA_TELA) return;
        if (telaPequena() && Math.random() > 0.8) return;

        const dados = escolher(ANUNCIOS);

        const card = document.createElement("div");
        card.className = "ad-card";
        card.style.top = aleatorio(10, 70) + "%";
        card.style.transform = `scale(${aleatorio(0.85, 1.05)})`;

        card.innerHTML = `
            <button class="ad-close" aria-label="Fechar anúncio">×</button>
            <img src="${dados.img}" alt="">
            <h4>${dados.titulo}</h4>
            <p>${dados.texto}</p>
        `;

        card.querySelector(".ad-close").addEventListener("click", () => {
            card.remove();
        });

        setTimeout(() => card.remove(), aleatorioInt(5000, 11000));

        adsLeft.appendChild(card);
    }

    function agendarAnuncios() {
        criarAnuncio();
        const proximo = aleatorioInt(CONFIG_ADS_INTERVALO_MIN, CONFIG_ADS_INTERVALO_MAX);
        setTimeout(agendarAnuncios, proximo);
    }


    /* ----------------------------------------------------------
       2) NOTIFICAÇÕES (canto inferior direito)
    ---------------------------------------------------------- */

    function criarToast() {
        if (notifications.querySelectorAll(".toast").length >= MAX_TOASTS) return;

        const dados = escolher(NOTIFICACOES);

        const toast = document.createElement("div");
        toast.className = "toast";
        toast.innerHTML = `
            <span class="toast-icon">${dados.icone}</span>
            <span class="toast-msg">${dados.texto}</span>
            <button class="toast-close" aria-label="Fechar">×</button>
        `;

        toast.querySelector(".toast-close").addEventListener("click", () => removerToast(toast));
        notifications.appendChild(toast);

        setTimeout(() => removerToast(toast), aleatorioInt(5000, 11000));
    }

    function removerToast(toast) {
        if (!toast.parentNode) return;
        toast.classList.add("saindo");
        setTimeout(() => toast.remove(), 300);
    }

    function agendarToasts() {
        criarToast();
        const proximo = aleatorioInt(CONFIG_TOAST_INTERVALO_MIN, CONFIG_TOAST_INTERVALO_MAX);
        setTimeout(agendarToasts, proximo);
    }


    /* ----------------------------------------------------------
       3) IMAGENS QUE DESCEM (coluna direita)
    ---------------------------------------------------------- */

    function montarColunaDireita() {
        if (!IMAGENS_COLUNA_DIREITA.length) return;

        const listaDuplicada = [...IMAGENS_COLUNA_DIREITA, ...IMAGENS_COLUNA_DIREITA];

        const col = document.createElement("div");
        col.className = "ticker-col";
        col.innerHTML = listaDuplicada
            .map((src) => `<img src="${src}" alt="">`)
            .join("");

        const duracao = (IMAGENS_COLUNA_DIREITA.length * CONFIG_TICKER_MS_POR_ITEM) / 4000;
        col.style.animationDuration = duracao + "s";

        adsRight.appendChild(col);
    }


        /* ----------------------------------------------------------
       4) ELEMENTOS QUE ATRAVESSAM A TELA
       - Tamanho ALEATÓRIO por aparição (proporção preservada)
       - Velocidade ALEATÓRIA por aparição
       - Nyan Cat SEMPRE anda para a direita (não "moonwalk")
       - Demais flyers: direção aleatória
    ---------------------------------------------------------- */

    // Configuração de tamanho (em pixels de altura)
    const CONFIG_FLYER_ALTURA_MIN = 60;   // menor tamanho possível
    const CONFIG_FLYER_ALTURA_MAX = 400;  // maior tamanho possível
// Quem é o Nyan Cat? (detectado por flag OU por trecho da URL)
    function ehNyanCat(flyer) {
        if (flyer.manterDirecao === true) return true;
        const src = (flyer.querySelector("img")?.src || "").toLowerCase();
        return src.includes("nyan");
    }
    

    function criarFlyer() {
        if (movimentoReduzido) return;

        const dados = escolher(FLYERS);

        const flyer = document.createElement("div");
        flyer.className = "flyer";

        // ---------- TAMANHO ALEATÓRIO POR APARIÇÃO ----------
        // Sorteia uma altura entre MIN e MAX para ESTA aparição
        const alturaAleatoria = aleatorioInt(CONFIG_FLYER_ALTURA_MIN, CONFIG_FLYER_ALTURA_MAX);

        if (dados.tipo === "img") {
            // Para GIFs/imagens: altura aleatória; largura = auto (proporção preservada)
            flyer.innerHTML = `<img src="${dados.conteudo}" alt="" style="height:${alturaAleatoria}px; width:auto;">`;
        } else {
            // Para emojis: usa a mesma faixa de altura aleatória como font-size
            flyer.textContent = dados.conteudo;
            flyer.style.fontSize = alturaAleatoria + "px";
        }

        // Marca a flag para o Nyan Cat (se você adicionar manualmente na lista)
        if (dados.manterDirecao === true) {
            flyer.manterDirecao = false;
        }

        // ---------- DIREÇÃO ----------
        // Nyan Cat: SEMPRE esquerda → direita (respeita a orientação original do GIF)
        // Outros: direção aleatória
        const forcarDireita = dados.manterDirecao === true ||
                              (dados.tipo === "img" && String(dados.conteudo).toLowerCase().includes("nyan"));
        const daEsquerda = forcarDireita ? true : Math.random() > 0.5;

        // ---------- POSIÇÃO VERTICAL ALEATÓRIA ----------
        const alturaPx = aleatorio(60, window.innerHeight - 100);

        // ---------- VELOCIDADE ALEATÓRIA ----------
        // Cada aparição tem sua própria duração (em segundos)
        // Quanto MAIOR a duração, MAIS LENTO. Faixa: 6s a 14s.
        const duracao = aleatorio(4, 5);

        // Distância necessária: precisa SAIR da tela completamente
        // = largura da tela + largura do próprio elemento + folga
        // Usamos 300px de folga para garantir que o elemento saia de fato
        const distancia = window.innerWidth + 900;

        flyer.style.top = alturaPx + "px";

        if (daEsquerda) {
            // Começa FORA da tela, à esquerda
            flyer.style.left = "-300px";
            flyer.animate(
                [
                    { transform: "translateX(0)" },
                    { transform: `translateX(${distancia}px)` },
                ],
                { duration: duracao * 1000, easing: "linear" }
            ).onfinish = () => flyer.remove();
        } else {
            // Começa FORA da tela, à direita
            flyer.style.left = window.innerWidth + "px";
            flyer.animate(
                [
                    { transform: "translateX(0)" },
                    { transform: `translateX(-${distancia}px)` },
                ],
                { duration: duracao * 1100, easing: "linear" }
            ).onfinish = () => flyer.remove();
        }

        flyersLayer.appendChild(flyer);
    }

    function agendarFlyers() {
        if (movimentoReduzido) return;
        criarFlyer();
        const proximo = aleatorioInt(CONFIG_FLYER_INTERVALO_MIN, CONFIG_FLYER_INTERVALO_MAX);
        setTimeout(agendarFlyers, proximo);
    }
    /* ----------------------------------------------------------
       INICIALIZAÇÃO
    ---------------------------------------------------------- */

    function iniciar() {
        montarColunaDireita();

        if (!movimentoReduzido) {
            setTimeout(agendarAnuncios, 1200);
            setTimeout(agendarToasts,   2500);
            setTimeout(agendarFlyers,   3500);
        }
    }

    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", iniciar);
    } else {
        iniciar();
    }

    // Debug manual no console: Efeitos.criarAnuncio(), etc.
    window.Efeitos = { criarAnuncio, criarToast, criarFlyer };
})();