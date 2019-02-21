var Inasistencias = {
    initialize: function(){
        console.log('Registro Initialize');
        this.handleModals();
        this.handleAbsencesForms();
    },

    handleModals: function(){
        $('#create_register').click(function(ev){
            ev.preventDefault();
            $('.form-control').val('');
            $('#new_register_modal').modal('show');
        });

        $("#alumno").select2({ dropdownParent: $("#new_register_modal") });

        if ($('#fecha_falta').length) {
            pikadayResponsive(document.getElementById("fecha_falta"),{
                classes : "form-control form-control-sm",
                placeholder: "Fecha de inasistencia",
                properties: 'required'
            });

            $('#fecha_falta-input').prop('autocomplete', 'off');
        }

        if ($('#fecha_contacto').length) {
            pikadayResponsive(document.getElementById("fecha_contacto"),{
                classes : "form-control form-control-sm",
                placeholder: "Fecha de contacto con alumno",
            });

            $('#fecha_contacto-input').prop('autocomplete', 'off');
        }

        if ($('#fecha_regreso').length) {
            pikadayResponsive(document.getElementById("fecha_regreso"),{
                classes : "form-control form-control-sm",
                placeholder: "Fecha de regreso del alumno",
            });

            $('#fecha_regreso-input').prop('autocomplete', 'off');
        }
    },

    handleAbsencesForms: function(){
        $('#frmNewRegister').submit(function(event){
            event.preventDefault();
            $('#general_snack').attr('data-content', "Funcionalidad no agregada");
            $('#general_snack').snackbar('show');
            $('.snackbar').addClass('snackbar-green');
        });
    },

    handleLoginUser: function() {
        let _this = this;
        $('#loginForm').submit(function(event){
            event.preventDefault();
            $.ajax({
                synch: 'true',
                type: 'POST',
                data: $('#loginForm').serialize(),
                url: _root_ + 'login/login'
            })
            .done(function(response){
                if (response.success) {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-green');
                } else {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-red');
                }
            })
            .fail(function(errno){
                console.log(errno.message)
                $('#general_snack').attr('data-content', 'ERROR:500, Error desconocido, reporte el incidente!');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-red');
                console.log(errno);
            });
        });
    }
};

Inasistencias.initialize();
