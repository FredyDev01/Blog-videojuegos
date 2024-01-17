import { showAlert, showConfirmAlert, onSaveRedirect, onSetSearch, onSetPagination } from './utils.js';


document.addEventListener('DOMContentLoaded', () => {
    const infoTable = document.getElementById('infoTable');
    
    infoTable?.addEventListener('click', (e) => {
        if(e.target.tagName === 'BUTTON') {
            const id = e.target.dataset.id;
            showConfirmAlert(
                {                    
                    title: '¿Seguro de eliminar la categoria?',
                    content: `
                        Si confirmas la accion la categoria se eliminara de manera
                        permantente junto a las entradas unicas pertenecientes a esta.
                    `
                },
                () => window.location.href = `actions/category/deleteCategory.php?id=${id}`                 
            );
        }
    });

    showAlert(
        'updateCategory',
        {
            success: {
                title: 'Categoria actualizada con exito',
                content: `
                    La categoria se actualizo dentro de la plataforma, ahora
                    los nuevos cambios ya son visibles.            
                `
            }
        }
    );

    showAlert(        
        'deleteCategory',
        {
            success: {
                title: 'Categoria eliminada con exito',
                content: `
                    La categoria se elimino de manera permanente, por lo que
                    ya no estara disponible en la plataforma.                
                `
            },
            error: {
                title: 'Error al eliminar categoria',
                content: `
                    El servidor no pudo al eliminar su categoria, verfique que 
                    tenga los permisos necesarios y vuelva a intentarlo.             
                ` 
            }
        }
    );
    
    onSaveRedirect('redirectCategories');

    onSetSearch();
    onSetPagination();
});