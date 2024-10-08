"use strict";

function main() {
  let monitorFields = document.getElementById('monitor-fields');
  let teclatFields = document.getElementById('teclat-fields');
  let torreFields = document.getElementById('torre-fields');
  let portatilFields = document.getElementById('portatil-fields');
  let radioButtons = document.querySelectorAll('input[name="dispositiu"]');
  let form = document.querySelector('form');

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

    if (!isValid) {
      event.preventDefault();
      alert("Per favor,  plena tots els camps requerits.");
    }
  });
}

document.addEventListener('DOMContentLoaded', main);