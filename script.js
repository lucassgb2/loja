// =================================
// ELEMENTOS DO HTML
// =================================

const loginBox = document.getElementById("loginBox");
const openLogin = document.getElementById("openLogin");
const closeLogin = document.getElementById("closeLogin");
const loginForm = document.getElementById("loginForm");


// =================================
// ABRIR LOGIN
// =================================

openLogin.addEventListener("click", () => {
    loginBox.style.display = "block";
});


// =================================
// FECHAR LOGIN
// =================================

closeLogin.addEventListener("click", () => {
    loginBox.style.display = "none";
});


// =================================
// ENVIO DO FORMULÁRIO (GATILHO DOS MEMES)
// =================================

loginForm.addEventListener("submit", (event) => {
    event.preventDefault();

    // Fecha a caixa de login
    loginBox.style.display = "none";

    // Escolhe aleatoriamente qual meme mostrar
    const randomMeme = Math.random() > 0.5 ? "win95Popup" : "zombiePopup";
    document.getElementById(randomMeme).style.display = "block";
});


// =================================
// FUNÇÃO PARA FECHAR OS MEMES
// =================================

function fecharMeme(id) {
    document.getElementById(id).style.display = "none";
}