import {currentURL, showAlert, onSaveRedirect, onSetSearch, onSetPagination} from './utils.js';


document.addEventListener('DOMContentLoaded', ()=> {    
    const listCategories = document.getElementById('list-categories');

    listCategories?.addEventListener('click', (e) => {
        if(e.target.tagName === 'LABEL') {
            e.target.classList.toggle('active');
        }else if(e.target.tagName === 'BUTTON') {
            currentURL.searchParams.delete('categoria[]')
            listCategories?.querySelectorAll('input[type="checkbox"]').forEach((e) => {
                if(e.checked) {
                    currentURL.searchParams.append('categoria[]', e.value);                
                }
            });
            currentURL.searchParams.delete('pagina');
            window.location.href = currentURL.href;
        }
    })

    showAlert(
        'deleteEntry',
        {
            success: {
                title: 'Entrada eliminada con exito',
                content: `
                    La entrada se elimino de manera permanente, por lo que
                    ya no estara disponible en la plataforma.
                `
            }
        }
    );

    onSaveRedirect('redirectEntries');

    onSetSearch();
    onSetPagination();
});