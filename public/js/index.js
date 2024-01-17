import { currentURL, onClickOutside } from './utils.js';


document.addEventListener('DOMContentLoaded', () => {    
    // VARIABLES PARA EL MENU RESPONSIVE
    const nav = document.getElementById('nav');
    const ul = nav.querySelector('ul'); 
    const li = ul.querySelectorAll('li');
    const moreOptions = document.getElementById('moreOptions');
    const openOptions = document.getElementById('openOptions');
    const ulOptions = moreOptions.querySelector('ul');
    const widthElements = [...li].map(target => ({target, width: target.clientWidth}));
    const widthMoreOptions = moreOptions.clientWidth;

    // VARIABLES PARA EL CAMBIO DE SECCION
    const changeSection = document.getElementById('changeSections');
    const listChangeButtons = changeSection.querySelectorAll('a'); 
    const sections = document.querySelectorAll('.section');

    // FUNCION ENCARGADA DE REORGANIZAR LOS ELEMENTOS DEL MENU RESPONSIVE
    const onStartResiseNav = () => {
        const initialSpace = nav.clientWidth - widthMoreOptions;
        let finalSpace = initialSpace;
        widthElements.forEach(element => {
            const widthElement = element.width;
            if(widthElement > finalSpace) {               
                ulOptions.appendChild(element.target);
            }else {
                ul.appendChild(element.target);
            }
            finalSpace -= widthElement;
        });
        const liOptions = ulOptions.querySelectorAll('li');
        if(liOptions.length) {
            moreOptions.classList.remove('hidden');
        }else {
            moreOptions.classList.add('hidden');
        }
    }

    // FUNCION ENCARGADA DEL CAMBIO ENTRE SECCIONES
    const onStartHashChange = (e) => {
        let hash = new URL(e?.newURL ?? currentURL.href).hash;        
        if(currentURL.hash !== hash) {
            currentURL.hash = hash;
        }
        let txtHash = hash.length ? hash.substring(1) : 'main';
        if(!document.getElementById(txtHash)) {
            txtHash = 'main';
            currentURL.hash = '';
            history.replaceState(null, null, currentURL.href);
        }
        const section = document.getElementById(txtHash);
        const btnSection =  document.getElementById('change-' + txtHash);        
        sections.forEach(section => section.classList.remove('active'));
        listChangeButtons.forEach(button => button.classList.remove('active'));
        section.classList.add('active');
        btnSection.classList.add('active');
    }

    // ASIGNACION DE EVENTOS PARA LAS ANTERIORES FUNCIONALIDADES
    window.addEventListener('resize', onStartResiseNav);
    window.addEventListener('hashchange', onStartHashChange);
    openOptions.addEventListener('click', (e) => {        
        e.target.classList.toggle('active');
    });

    onClickOutside([moreOptions], false, () => openOptions.classList.remove('active'));
    onStartHashChange();
    onStartResiseNav();
});