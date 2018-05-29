var Main = {

    initialize: function(){

        this.defaults();
        this.initSettings();
        // this.tabClasesDestroy();
        // this.tabStudentsActive();
        // this.tabClasesActive();
        // this.tabNewStActive();
    },

    defaults: function(){
        if (sessionStorage.getItem('exist') === null) {
            sessionStorage.setItem('exist', true);
            sessionStorage.setItem('paginacion_alumnos', 10);
            sessionStorage.setItem('vista_alumnos', 'curso_1');
            sessionStorage.setItem('formNewStudent', 'tutor');
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

    tabClasesDestroy: function(){
        $('.main_menu li').on('click', function(){
            sessionStorage.setItem("c_activo", 1);
            sessionStorage.setItem("st_alive", 1);
            sessionStorage.setItem("plist_alive", 1);
            sessionStorage.setItem("s_activo", 'info_tutor');
            sessionStorage.setItem("a_activo", 'list_club');
            sessionStorage.setItem("pview_activo", 'paylist_club');
        });
    },

    tabStudentsActive: function() {
        let that = this;
        var current = sessionStorage.getItem('a_activo');
        if (current !== '' && current !== undefined && current !== null) {
            $("."+current).addClass('active');
        } else {
            $(".list_club").addClass('active');
        }

        
        $('.menu_student_list li').on('click', function(){ 
            var actual = sessionStorage.getItem('a_activo');
            $("."+actual).removeClass('active');

            var activo = $(this).data('index');
            var alive  = $(this).data('group');
            sessionStorage.setItem("a_activo", activo);
            sessionStorage.setItem("st_alive", alive);
            $("."+activo).addClass('active');
        });


        var current_pay = sessionStorage.getItem('pview_activo');
        if (current_pay !== '' && current_pay !== undefined && current_pay !== null) {
            $("."+current_pay).addClass('active');
        } else {
            $(".paylist_club").addClass('active');
        }

        $('.menu_pay_list li').on('click', function(){ 
            let actual = sessionStorage.getItem('pview_activo');
            $("."+actual).removeClass('active');

            let activo = $(this).data('index');
            let alive  = $(this).data('group');
            sessionStorage.setItem("pview_activo", activo);
            sessionStorage.setItem("plist_alive", alive);
            $("."+activo).addClass('active');
        });
    },

    tabClasesActive: function() {
        let that = this;

        if (sessionStorage.getItem('c_activo') === '1') {
            $("li#t1").addClass('active');
            $("#v_clases").addClass('active');
        }
        if (sessionStorage.getItem('c_activo') === '2') {
            $("li#t2").addClass('active');
            $("#v_cursos").addClass('active');
        }
        if (sessionStorage.getItem('c_activo') === '3') {
            $("li#t3").addClass('active');
            $("#v_horario").addClass('active');
        }
        
        $('#menu_clase li').on('click', function(){ 
            /*Captura de datos escrito en los inputs*/ 
            var actual = sessionStorage.getItem('c_activo');
            $("#t"+actual).removeClass('active');  
            var activo = parseInt($(this).data('index')); 
            sessionStorage.setItem("c_activo", activo);
            $("#t"+activo).addClass('active');
            switch(activo) {
                case 1:
                    $("#v_clases").addClass('active');
                    $("#v_cursos").removeClass('active');
                    $("#v_horario").removeClass('active');
                    break;
                case 2:
                    $("#v_clases").removeClass('active');
                    $("#v_cursos").addClass('active');
                    $("#v_horario").removeClass('active');
                    break;
                case 3:
                    $("#v_clases").removeClass('active');
                    $("#v_cursos").removeClass('active');
                    $("#v_horario").addClass('active');
                    break;
            }
        }); 
    },

    tabNewStActive: function() {
        let that = this;

        if (sessionStorage.getItem('s_activo') !== '') {
            var current = sessionStorage.getItem('s_activo');
            $("."+current).addClass('active');
        } else {
            $(".info_tutor").addClass('active');
        }

        
        $('.menu_nuevo_alumno li').on('click', function(){ 
            var actual = sessionStorage.getItem('s_activo');
            $("."+actual).removeClass('active');

            var activo = $(this).data('index'); 
            sessionStorage.setItem("s_activo", activo);
            $("."+activo).addClass('active');

        }); 
    }
};

Main.initialize();

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();
}(jQuery));
