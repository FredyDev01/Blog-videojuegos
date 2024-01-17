import { showAlert, showConfirmAlert } from './utils.js';


document.addEventListener('DOMContentLoaded', ()=> {
    const btnDeleteEntry = document.getElementById('btnDeleteEntry')

    btnDeleteEntry?.addEventListener('click', (e) => {
        const id = e.target.dataset.id;
        showConfirmAlert(
            {
                title: '¿Seguro de eliminar la entrada?',
                content: `
                    Si confirmas la accion la entrada se eliminara de manera 
                    permantente dentro de la plataforma.
                `
            },
            () => window.location.href = `actions/entry/deleteEntry.php?id=${id}` 
        );
    });

    showAlert(
        'updateEntry',
        {
            success: {
                title: 'Entrada actualizada con exito',
                content: `
                    La entrada se actualizo dentro de la plataforma, ahora
                    los nuevos cambios ya son visibles.                  
                `
            }
        }
    );

    showAlert(
        'deleteEntry',
        {
            error: {
                title: 'Error al eliminar entrada',
                content: `
                    El servidor no pudo al eliminar su entrada, verfique que 
                    sea de su propiedad y vuelva a intentearlo.                
                `
            }
        }
    );
});