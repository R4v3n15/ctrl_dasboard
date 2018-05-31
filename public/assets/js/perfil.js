var Perfil = {

    initialize: function(){
        console.log('Profile Initialize');
        this.setActiveForm();
        this.navigateForms();
    },

    setActiveForm: function(){
        // Iniciado desde main.js
        let form = sessionStorage.getItem('activeForm');
        $('#form-'+form).addClass('active');
        this.getUpdateForm(form);
    },

    getActiveForm: function(){
        return sessionStorage.getItem('activeForm');
    },

    getUpdateForm: function(activeForm=1) { 
        let _this = this;
        let alumno = $('#alumno').data('alumno');
        let tutor  = $('#alumno').data('tutor');
        $.ajax({
            synch: 'true',
            type: 'POST',
            data: {alumno: alumno, tutor: tutor, form: activeForm},
            url: _root_ + 'alumnos/editarAlumno',
            success: function(data){
                $('#editar_form').html(data);
                _this.activeData();
            }
        });
    },

    navigateForms: function(){
        let _this = this;
        $('.editar-datos').click(function(event) {
            event.preventDefault();
            let form = $(this).data('form');
            sessionStorage.setItem('activeForm', form);

            $('.editar-datos').removeClass('active');
            $(this).addClass('active')

            _this.getUpdateForm(form);
        });
    },



    activeData: function() {
        // $('#fecha_inicio').datetimepicker({
        //     format: 'YYYY-MM-DD',
        //     defaultDate: $('#birthdate').val()
        // });

        pikadayResponsive(document.getElementById("bdate"),{
            classes : "form-control form-control-sm",
            placeholder: "Fecha de Nacimiento"
        });

        $("#avatar").fileinput({
            showCaption: true,
            browseClass: "btn btn-info btn-sm btn-lg",
            fileType: "image"
        });
    },
    
};

Perfil.initialize();
