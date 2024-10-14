"use strict";

function main() {
  document.getElementById('dispositiu').addEventListener('change', function() {
    let selectedOption = this.options[this.selectedIndex];
    let nom = selectedOption.getAttribute('data-nom');
    let cognom = selectedOption.getAttribute('data-cognom');
    let propietariId = selectedOption.getAttribute('data-propietari-id');

    document.getElementById('nom').value = nom;
    document.getElementById('cognom').value = cognom;
    document.getElementById('propietari_id').value = propietariId;
  });
}

document.addEventListener('DOMContentLoaded', main);