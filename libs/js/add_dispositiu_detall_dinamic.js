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

  // Mostrar o amagar camps segons el dispositiu seleccionat
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

  // Validació del formulari
  form.addEventListener('submit', function (event) {
    let isValid = true;
  
    // Comprovar selecció d'un dispositiu
    let dispositivoSeleccionado = Array.from(radioButtons).some(radio => radio.checked);
    if (!dispositivoSeleccionado) {
      isValid = false;
      document.querySelector('.form-group .radio').classList.add('is-invalid');
    } else {
      document.querySelector('.form-group .radio').classList.remove('is-invalid');
    }
  
    let requiredInputs = form.querySelectorAll('input[required]');
    requiredInputs.forEach(input => {
      if (!input.value.trim()) {
        isValid = false;
        input.classList.add('is-invalid');
      } else {
        input.classList.remove('is-invalid');
      }
    });
  
    // Validació propietari
    if (!validatePropietari()) {
      isValid = false;
    }
  
    if (!isValid) {
      event.preventDefault();
    }
  });

  // Habilitar/deshabilitar camps de nom/cognom segons propietari
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