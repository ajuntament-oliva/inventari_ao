"use strict";

function main() {
    const monitorFields = document.getElementById('monitor-fields');
  const radioButtons = document.querySelectorAll('input[name="dispositiu"]');

  radioButtons.forEach(radio => {
    radio.addEventListener('change', function () {
      if (this.value === 'Monitor') {
        monitorFields.style.display = 'block'; // Mostrar campos de monitor
      } else {
        monitorFields.style.display = 'none'; // Ocultar campos de monitor
      }
    });
});
}

document.addEventListener('DOMContentLoaded', main);