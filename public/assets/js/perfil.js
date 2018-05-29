var Perfil = {
    initialize: function(){
        console.log('Profile Initialize');
        this.setActiveForm();
        this.getUpdateForm();

        // this.navigateInscriptionForms();
    },

    setActiveForm: function(){
        
    },

    getActiveForm: function(){
        
    },

    getUpdateForm: function() {
        let _this = this;
        let alumno = $('#alumno').data('alumno');
        let tutor  = $('#alumno').data('tutor');
        $.ajax({
            synch: 'false',
            type: 'POST',
            // data: {alumno: alumno},
            // data: {tutor: tutor},
            data: {alumno: alumno},
            // url: _root_ + 'alumno/datosAlumno',
            // url: _root_ + 'alumno/datosTutor',
            url: _root_ + 'alumnos/datosAcademicos',
            success: function(data){
                $('#editar_form').html(data);
            }
        });
    },

    navigateInscriptionForms: function(){
        let _this = this;
        $('.btn_forms').click(function(event) {
            event.preventDefault();
            let form = $(this).data('form');
            $('.btn_forms').removeClass('active');
            sessionStorage.setItem('formNewStudent', form);
            $('#form_'+form).addClass('active');
            console.log(_this.getActiveForm());
            _this.getInscriptionForm(form);
        });
    },



    activeData: function() {

        $('#fecha_inicio').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });

        $('#bornday').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });

        $("#avatar").fileinput({
            showCaption: true,
            browseClass: "btn btn-info btn-sm btn-lg",
            fileType: "image"
        });
    },
    
};

Perfil.initialize();
