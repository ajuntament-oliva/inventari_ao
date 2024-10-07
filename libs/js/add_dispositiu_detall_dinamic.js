"use strict";

function main() {
    const monitorFields = document.getElementById('monitor-fields');
    const teclatFields = document.getElementById('teclat-fields');
    const torreFields = document.getElementById('torre-fields');
    const portatilFields = document.getElementById('portatil-fields');
    const radioButtons = document.querySelectorAll('input[name="dispositiu"]');
  
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function () {
            // Ocultar todos los campos dinámicos primero
            monitorFields.style.display = 'none';
            teclatFields.style.display = 'none';
            torreFields.style.display = 'none';
            portatilFields.style.display = 'none';

            // Mostrar campos según la selección
            if (this.value === 'Monitor') {
                monitorFields.style.display = 'block'; 
            } else if (this.value === 'Teclat') {
                teclatFields.style.display = 'block'; 
            } else if (this.value === 'Torre') {
                torreFields.style.display = 'block'; 
            } else if (this.value === 'Portàtil') { 
                portatilFields.style.display = 'block'; 
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', main);