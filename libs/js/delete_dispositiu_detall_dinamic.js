"use strict";

function main() {
  let btnEliminar = document.getElementById('btnEliminar');
  let dispositiuSelect = document.getElementById('dispositiu_select');
  let warningMessage = document.createElement('div');

  warningMessage.className = 'alert alert-warning';
  warningMessage.style.display = 'none'; // Inicialmente oculto
  warningMessage.textContent = 'Selecciona un dispositiu per eliminar.';
  dispositiuSelect.parentElement.appendChild(warningMessage);

  // Evento al hacer clic en "Eliminar"
  btnEliminar.addEventListener('click', function() {
    let dispositiuId = dispositiuSelect.value;

    if (dispositiuId) {
      warningMessage.style.display = 'none';
      document.getElementById('dispositiu_id_confirm').value = dispositiuId;
      $('#confirmModal').modal('show');
    } else {
      warningMessage.style.display = 'block'; 
    }
  });
}

document.addEventListener('DOMContentLoaded', main);