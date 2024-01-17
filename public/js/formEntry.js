import { onClickOutside } from './utils.js';


document.addEventListener('DOMContentLoaded', () => {
    const containerMultiSelect = document.querySelectorAll('.container-multi-select');
    
    // CONFIGURAR LA APERTURA DEL MULTI SELECT CON LOS CLICKS INTERNOS
    containerMultiSelect.forEach((elm) => {
        elm?.parentElement.querySelector('label').addEventListener('click', () => {
            elm.click();
        });
        
        elm.addEventListener('click', (e) => {
            if(['DIV', 'SPAN', 'I'].indexOf(e.target.tagName) != -1) {
                elm.classList.toggle('active');
            }else if(e.target.tagName === 'LABEL') {
                let totalSelect = 0;
                setTimeout(() => {
                    elm.querySelectorAll('input[type="checkbox"]').forEach((inp) => {
                        if(inp.checked) {
                            totalSelect++;
                        }
                    });
                    elm.querySelector('span').textContent = `${totalSelect} categorias seleccionadas`;
                    e.target.classList.toggle('active');
                });
            }
        });
    });

    onClickOutside(containerMultiSelect, true, (e) => e.classList.remove('active'));
});