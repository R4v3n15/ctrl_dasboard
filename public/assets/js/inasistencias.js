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

        $('.edit_absence').click(function(ev){
            ev.preventDefault();
            $('#edit_absenceId').val($(this).data('absence'));
            $('#edit_student_name').text($(this).data('student'));
            $('#edit_absence_date').val($(this).data('absencedate'));
            $('#edit_absence_date-input').val($(this).data('absencedate'));
            $("#edit_teacher option[value='" + $(this).data('teacher') +"']").attr("selected", true);
            $('#edit_teacher_note').val($(this).data('teachernote'));
            $('#edit_absence_note').val($(this).data('absencenote'));
            $('#edit_contact_date').val($(this).data('contactdate'));
            $('#edit_contact_date-input').val($(this).data('contactdate'));
            $('#edit_return_date').val($(this).data('returndate'));
            $('#edit_return_date-input').val($(this).data('returndate'));
            $('#edit_register_modal').modal('show');
        });

        $('.delete_absence').click(function(ev){
            ev.preventDefault();
            $('#delete_absenceId').val($(this).data('absence'));
            $('#delete_student_name').text($(this).data('student'));
            $('#delete_register_modal').modal('show');
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

        if ($('#edit_absence_date').length) {
            pikadayResponsive(document.getElementById("edit_absence_date"),{
                classes : "form-control form-control-sm",
                placeholder: "Fecha de inasistencia",
                properties: 'required'
            });

            $('#edit_absence_date-input').prop('autocomplete', 'off');
        }

        if ($('#edit_contact_date').length) {
            pikadayResponsive(document.getElementById("edit_contact_date"),{
                classes : "form-control form-control-sm",
                placeholder: "Fecha de contacto con alumno",
            });

            $('#edit_contact_date-input').prop('autocomplete', 'off');
        }

        if ($('#edit_return_date').length) {
            pikadayResponsive(document.getElementById("edit_return_date"),{
                classes : "form-control form-control-sm",
                placeholder: "Fecha de regreso del alumno",
            });

            $('#edit_return_date-input').prop('autocomplete', 'off');
        }
    },

    handleAbsencesForms: function(){
        $('#frmNewRegister').submit(function(event){
            event.preventDefault();
            $.ajax({
                synch: 'true',
                type: 'POST',
                data: $('#frmNewRegister').serialize(),
                url: _root_ + 'alumnos/guardar_inasistencia'
            })
            .done(function(response){
                if (response.success) {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-green');
                    setTimeout(() => {
                        location.reload(true);
                    }, 1000);
                } else {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-red');
                }
            })
            .fail(function(errno){
                $('#general_snack').attr('data-content', 'ERROR:500, Error desconocido, reporte el incidente!');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-red');
            });
        });


        $('#frmUpdateRegister').submit(function(event){
            event.preventDefault();
            $.ajax({
                synch: 'true',
                type: 'POST',
                data: $('#frmUpdateRegister').serialize(),
                url: _root_ + 'alumnos/actualizar_inasistencia'
            })
            .done(function(response){
                if (response.success) {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-green');
                    setTimeout(() => {
                        location.reload(true);
                    }, 1000);
                } else {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-red');
                }
            })
            .fail(function(errno){
                $('#general_snack').attr('data-content', 'ERROR:500, Error desconocido, reporte el incidente!');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-red');
            });
        });

        $('#frmDeleteRegister').submit(function(event){
            event.preventDefault();
            $.ajax({
                synch: 'true',
                type: 'POST',
                data: $('#frmDeleteRegister').serialize(),
                url: _root_ + 'alumnos/eliminar_inasistencia'
            })
            .done(function(response){
                if (response.success) {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-green');
                    setTimeout(() => {
                        location.reload(true);
                    }, 1000);
                } else {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-red');
                }
            })
            .fail(function(errno){
                $('#general_snack').attr('data-content', 'ERROR:500, Error desconocido, reporte el incidente!');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-red');
            });
        });
    },
};

Inasistencias.initialize();
