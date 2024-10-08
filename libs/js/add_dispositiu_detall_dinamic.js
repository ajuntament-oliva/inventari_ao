"use strict";

function main() {

  let monitorFields = document.getElementById('monitor-fields');
  let teclatFields = document.getElementById('teclat-fields');
  let torreFields = document.getElementById('torre-fields');
  let portatilFields = document.getElementById('portatil-fields');
  let radioButtons = document.querySelectorAll('input[name="dispositiu"]');

  function toggleFields(fields, isVisible) {
    let inputs = fields.querySelectorAll('input');
    inputs.forEach(input => {
      if (isVisible) {
        input.removeAttribute('disabled');
        input.setAttribute('required', 'required');
      } else {
        input.setAttribute('disabled', 'disabled');
        input.removeAttribute('required');
      }
    });
    fields.style.display = isVisible ? 'block' : 'none';
  }

  radioButtons.forEach(radio => {
    radio.addEventListener('change', function () {

      // Ocultar i deshabilitar tots els camps
      toggleFields(monitorFields, false);
      toggleFields(teclatFields, false);
      toggleFields(torreFields, false);
      toggleFields(portatilFields, false);

      // Mostrar i habilitar camps segons la selecció
      if (this.value === 'Monitor') {
        toggleFields(monitorFields, true);
      } else if (this.value === 'Teclat') {
        toggleFields(teclatFields, true);
      } else if (this.value === 'Torre') {
        toggleFields(torreFields, true);
      } else if (this.value === 'Portàtil') {
        toggleFields(portatilFields, true);
      }
    });
  });
}

document.addEventListener('DOMContentLoaded', main);