import { showAlert, onSaveRedirect, onSetSearch, onSetPagination } from './utils.js';


document.addEventListener('DOMContentLoaded', () => {
    const infoTable = document.getElementById('infoTable');

    infoTable?.addEventListener('click', (e) => { 
        let checkbox = e.target.parentNode.querySelector('input[type="checkbox"]');
        checkbox.checked = !checkbox.checked;
        const userId = checkbox.dataset.id;
        const rolId = checkbox.checked ? '2' : '1';
        window.location.href = `actions/user/changeRol.php?id=${userId}&rol=${rolId}`;
    });

    showAlert(
        'changeRol',
        {
            success: {
                title: 'Rol cambiado con exito',
                content:  `
                    El usuario seleccionado ahora tiene los permisos cambiados
                    dentro de la plataforma.
                `
            },
            error: {
                title: 'Error al cambiar de rol',
                content: `
                    El servidor no pudo procesar el cambio de rol del usuario seleccionado,
                    le recomendamos revisar sus permisos.             
                `
            }
        }
    );

    onSaveRedirect('redirectUsers');

    onSetSearch();
    onSetPagination();
});