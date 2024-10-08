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
  
  // Funció per alternar camps segons la selecció de dispositiu
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

  // Mostrar/ocultar camps segons el tipus de dispositiu seleccionat
  radioButtons.forEach(radio => {
    radio.addEventListener('change', function () {
      toggleFields(monitorFields, false);
      toggleFields(teclatFields, false);
      toggleFields(torreFields, false);
      toggleFields(portatilFields, false);

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

  // Validació per a que haja un propietari
  function validatePropietari() {
    if (nomField.value.trim() !== '' || cognomField.value.trim() !== '') {
      propietariSelect.removeAttribute('required');
    } else if (!propietariSelect.value) {
      alert("Has de seleccionar un propietari existent o afegir-ne un de nou.");
      return false;
    }
    return true;
  }

  form.addEventListener('submit', function (event) {
    let isValid = true;
    let requiredInputs = form.querySelectorAll('input[required]');
    
    requiredInputs.forEach(input => {
      if (!input.value) {
        isValid = false;
        input.classList.add('is-invalid');
      } else {
        input.classList.remove('is-invalid');
      }
    });

    if (!validatePropietari()) {
      isValid = false;
    }

    if (!isValid) {
      event.preventDefault();
      alert("Per favor, plena tots els camps requerits.");
    }
  });

  nomField.addEventListener('input', function() {
    if (this.value.trim() !== '') {
      propietariSelect.value = '';
      propietariSelect.setAttribute('disabled', 'disabled');
    } else {
      propietariSelect.removeAttribute('disabled');
    }
  });

  cognomField.addEventListener('input', function() {
    if (this.value.trim() !== '') {
      propietariSelect.value = '';
      propietariSelect.setAttribute('disabled', 'disabled');
    } else {
      propietariSelect.removeAttribute('disabled');
    }
  });

  propietariSelect.addEventListener('change', function() {
    if (this.value) {
      nomField.setAttribute('disabled', 'disabled');
      cognomField.setAttribute('disabled', 'disabled');
    } else {
      nomField.removeAttribute('disabled');
      cognomField.removeAttribute('disabled');
    }
  });
}

document.addEventListener('DOMContentLoaded', main);