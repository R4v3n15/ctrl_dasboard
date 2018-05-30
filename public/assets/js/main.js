var Main = {

    initialize: function(){
        this.defaults();
        this.initSettings();
    },

    defaults: function(){
        if (sessionStorage.getItem('exist') === null) {
            sessionStorage.setItem('exist', true);
            sessionStorage.setItem('paginacion_alumnos', 10);
            sessionStorage.setItem('vista_alumnos', 'curso_1');
            sessionStorage.setItem('formNewStudent', 'tutor');
            sessionStorage.setItem('activeForm', 1);
            sessionStorage.setItem('vista_clases', 1);
            sessionStorage.setItem('vista_pagos', 1);
        }
    },

    initSettings: function(){
        let that = this;
        let alumnos_activo = sessionStorage.getItem('vista_alumnos');
        $('#'+alumnos_activo).addClass('active');

        let nuevo_activo = sessionStorage.getItem('formNewStudent');
        $('#'+nuevo_activo).addClass('active');
    },
};

Main.initialize();