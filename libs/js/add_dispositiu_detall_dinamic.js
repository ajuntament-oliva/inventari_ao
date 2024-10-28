"use strict";

function main() {
  let monitorFields = document.getElementById('monitor-fields');
  let teclatFields = document.getElementById('teclat-fields');
  let torreFields = document.getElementById('torre-fields');
  let portatilFields = document.getElementById('portatil-fields');
  let radioButtons = document.querySelectorAll('input[name="dispositiu"]');
  let form = document.querySelector('form');

  let nomField = document.querySelector('input[name="nom"]');
  let cognomField = document.querySelector('input[name="cognom"]');
  let propietariSelect = document.querySelector('select[name="propietari_exist"]');

  // Funció per alternar la visibilitat dels camps del dispositiu seleccionat
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

  // Mostrar o ocultar camps segons el dispositiu seleccionat
  radioButtons.forEach(radio => {
    radio.addEventListener('change', function () {
      toggleFields(monitorFields, false);
      toggleFields(teclatFields, false);
      toggleFields(torreFields, false);
      toggleFields(portatilFields, false);

      if (radio.value === 'Monitor') {
        toggleFields(monitorFields, true);
      } else if (radio.value === 'Teclat') {
        toggleFields(teclatFields, true);
      } else if (radio.value === 'Torre') {
        toggleFields(torreFields, true);
      } else if (radio.value === 'Portàtil') {
        toggleFields(portatilFields, true);
      }
    });
  });

  // Selecció de propietari existent o nou
  propietariSelect.addEventListener('change', function () {
    if (propietariSelect.value) {
      nomField.setAttribute('disabled', 'disabled');
      cognomField.setAttribute('disabled', 'disabled');
      nomField.removeAttribute('required');
      cognomField.removeAttribute('required');
    } else {
      nomField.removeAttribute('disabled');
      cognomField.removeAttribute('disabled');
      nomField.setAttribute('required', 'required');
      cognomField.setAttribute('required', 'required');
    }
  });

  // Validar formulari abans d'enviar
  form.addEventListener('submit', function (event) {
    let selectedDevice = Array.from(radioButtons).some(radio => radio.checked);
    if (!selectedDevice) {
      event.preventDefault();
      alert("Por favor, selecciona un tipo de dispositivo.");
    }
  });
}

document.addEventListener('DOMContentLoaded', main);