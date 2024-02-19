if (localStorage.getItem("token") == null) {
  alert("Você precisa estar logado para acessar essa página");
  window.location.href = "./assets/html/signin.html";
} else {
  const userLogado = JSON.parse(localStorage.getItem("userLogado"));
  const logado = document.querySelector("#logado");
  logado.innerHTML = `Olá ${userLogado.nome}`;
}

function sair() {
  localStorage.removeItem("token");
  localStorage.removeItem("userLogado");
  window.location.href = "./assets/html/signin.html";
}

function togglePopup(popupId) {
  var popup = document.getElementById(popupId);
  popup.style.display = popup.style.display === "block" ? "none" : "block";
}

