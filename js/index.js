
function sair() {
  localStorage.removeItem("token");
  localStorage.removeItem("userLogado");
  window.location.href = "../";
}

function togglePopup(popupId) {
  var popup = document.getElementById(popupId);
  popup.style.display = popup.style.display === "block" ? "none" : "block";
}
function togglePopup(popupId) {
  var popup = document.getElementById(popupId);
  popup.style.display = (popup.style.display === "block") ? "none" : "block";
}

