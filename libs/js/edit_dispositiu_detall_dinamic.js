"use strict";

function main() {
  let dispositiuSelect = document.getElementById("dispositiu");
  let nomInput = document.getElementById("nom");
  let cognomInput = document.getElementById("cognom");
  let propietariIdInput = document.getElementById("propietari_id");

  dispositiuSelect.addEventListener("change", function () {
    let selectedOption = dispositiuSelect.options[dispositiuSelect.selectedIndex];

    nomInput.value = selectedOption.getAttribute("data-nom") || '';
    cognomInput.value = selectedOption.getAttribute("data-cognom") || '';
    propietariIdInput.value = selectedOption.getAttribute("data-propietari-id") || '';
  });
}

document.addEventListener("DOMContentLoaded", main);