export const currentURL = new URL(window.location.href);
export const icons = {
    success: 'success',
    error: 'error',
    question: 'question'
};


// FUNCIONALIDAD GENERAL PARA MOSTRAR MENSAJES AL USUARIO
export const showAlert = (parameter, msg = {success: {title, content}, error: {title, content}}) => {
    const currentParameter = currentURL.searchParams.get(parameter)

    var infoSwal = {
        show: false,
        icon: '',
        title: '',
        content: ''
    };

    if(currentParameter) {
        infoSwal.show = true;
        switch(currentParameter) {
            case '1':
                infoSwal.icon = icons.success;
                infoSwal.title = msg.success.title;
                infoSwal.content = msg.success.content;
                break;
            case '0':
                infoSwal.icon = icons.error;
                infoSwal.title = msg.error.title;
                infoSwal.content = msg.error.content;                
        }
        currentURL.searchParams.delete(parameter);
    }

    if(infoSwal.show) {
        history.replaceState(null, null, currentURL.href);
        Swal.fire(infoSwal.title, infoSwal.content, infoSwal.icon);
    }    
}


// FUNCIONALIDAD GENERAL PARA MOSTRAR CONFIRMACIONES AL USUARIO
export const showConfirmAlert = ({title, content}, fn) => {
    Swal.fire({
        icon: icons.question,
        title: title,
        text: content,
        showCancelButton: true,
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if(result.isConfirmed) {
            fn();
        }
    });
}

// FUNCIONALIDAD GENERAL PARA DETECTAR CLICKS FUERA DE UN ELEMENTO
export const onClickOutside = (elements, sibling, fn) => {
    document.addEventListener('click', (e) => {
        elements.forEach((element) => {            
            const elementClicked = e.target;
            const isParent = elementClicked.contains(element);
            const isChild = element.contains(elementClicked);
            if(
                element !== elementClicked &&
                (isParent || !isChild) && 
                (!sibling || (element.previousElementSibling !== elementClicked))
            ) {
                fn(element);                
            }
        });
    });
}

// FUNCIONALIDAD GENERAL PARA GUARDAR LA REDIRECCION EN LA COOKIE
export const onSaveRedirect = (nameCookie) => {    
    const urlSave = new URL(currentURL.href);
    urlSave.hash = "";
    document.cookie = `${nameCookie}=${urlSave.href}; path=/`
}

// FUNCIONALIDAD GENERAL DEL BUSCADOR
export const onSetSearch = () => {
    const search = document.getElementById('search');
    const currentSearch = currentURL.searchParams.get('busqueda') ?? '';

    search?.addEventListener('keyup', (e) => {
        if(e.key === 'Enter') {
            const changeSearch = (e.target.value).trim();
            if(currentSearch != changeSearch) {
                if(changeSearch === '') {
                    currentURL.searchParams.delete('busqueda');    
                }else {
                    currentURL.searchParams.set('busqueda', changeSearch);
                }                
                window.location.href = currentURL.href;
            }
        }
    });
}


// FUNCIONALIDAD GENERAL DE LA PAGINACION
export const onSetPagination = () => {
    const pagination = document.getElementById('pagination');
    const currentPage = currentURL.searchParams.get('pagina') ?? '1';
    
    pagination?.addEventListener('click', (e) => {    
        if(e.target.tagName === 'BUTTON') {        
            const changePage = e.target.dataset.page;
            if(currentPage != changePage) {                
                if(changePage === '1') {
                    currentURL.searchParams.delete('pagina');    
                }else {
                    currentURL.searchParams.set('pagina', changePage);
                }
                window.location.href = currentURL.href;
            }
        }
    });
}