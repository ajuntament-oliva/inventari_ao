"use strict";

function main() {
  document.getElementById('dispositiu').addEventListener('change', function() {
    let dispositiu_id = this.value;
    
    // Solicitut AJAX al servidor
    let xhr = new XMLHttpRequest();
    xhr.open('POST', 'get_propietari.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        let propietari = JSON.parse(xhr.responseText);
        
        document.getElementById('nom').value = propietari.nom || '';
        document.getElementById('cognom').value = propietari.cognom || '';
      }
    };
  
    xhr.send('dispositiu_id=' + dispositiu_id);
  });
}

document.addEventListener('DOMContentLoaded', main);