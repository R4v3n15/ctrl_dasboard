var Main = {
    opts: {
        activeMenu: 1
    },

    initialize: function(){
        this.toggleMenu();
        this.defaults();
        this.initSettings();
    },

    defaults: function(){
        let path = window.location.pathname.split( '/' );

        if (sessionStorage.getItem('exist') === null) {
            sessionStorage.setItem('toggledMenu', 0);
            sessionStorage.setItem('exist', true);
            sessionStorage.setItem('paginacion_alumnos', 10);
            sessionStorage.setItem('vista_alumnos', 'curso_1');
            sessionStorage.setItem('formNewStudent', 'tutor');
            sessionStorage.setItem('vista_clases', 1);
            sessionStorage.setItem('vista_pagos', 1);
        }

        if(path[2] !== 'perfil'){
            sessionStorage.setItem('activeForm', 1);
        }


        let toggled = parseInt(sessionStorage.getItem('toggledMenu'));
        console.log(toggled)
        if (toggled === 1) {
            // $('#sidebar-wrapper').fadeOut('450');
            setTimeout(function(){
                $("#wrapper").addClass("toggled")
            },300);
        } else {
            $("#wrapper").removeClass("toggled");
        }
    },

    toggleMenu: function(){
        $(".menu-toggle").click(function(e) {
            e.preventDefault();
            let toggled = parseInt(sessionStorage.getItem('toggledMenu'));
            $("#wrapper").toggleClass("toggled");
            if (toggled === 1) {
                sessionStorage.setItem('toggledMenu', 0);
            } else {
                sessionStorage.setItem('toggledMenu', 1);
            }
            console.log(toggled)
        });

    },

    initSettings: function(){
        let alumnos_activo = sessionStorage.getItem('vista_alumnos');
        $('#'+alumnos_activo).addClass('active');

        let nuevo_activo = sessionStorage.getItem('formNewStudent');
        $('#'+nuevo_activo).addClass('active');
    },
};

Main.initialize();