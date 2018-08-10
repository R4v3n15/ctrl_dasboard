var Perfil = {

    initialize: function(){
        console.log('Profile Initialize');
        this.setActiveForm();
        this.navigateForms();
        this.updateAvatar();
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

    updateAvatar: function(){
        let _this = this;
        $('#changeAvatar').click(function () {
            let student = $(this).data('alumno');

            $('#avatar_student').val(student);
            $('#avatar_file').val('');
            $('#modalChangeAvatar').modal('show'); 
        });

        $('#frmChangeAvatar').submit(function(event){
            event.preventDefault();
            let formData = new FormData($('#frmChangeAvatar')[0]);
            console.log('Change Image');

            $.ajax({
                url: _root_ + 'alumnos/cambiarFotoAlumno',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            })
            .done(function(response){
                if (response.success) {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-blue');
                    location.reload();
                } else {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-red')
                }
                $('#modalChangeAvatar').modal('hide');

            });
        });
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
                _this.updateAction();
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

        $('#table_students tbody').on( 'click', '.btnChangeAvatar', function () {
            let student = $(this).data('student'),
                name    = $(this).data('name');

            $('#avatar_student').val(student);
            $('#modalChangeAvatar').modal('show');
                
        });
    },

    updateAction: function(){
        let _this = this;
        // Update datos del alumno
        $('#update_student').click(function(event) {
            event.preventDefault();
            let student = $('#student_id').val(),
                name    = $('#name').val(),
                surname = $('#surname').val();

            if (student !== '' && name !== '' && surname !== '') {
                $.ajax({
                    synch: 'true',
                    type: 'POST',
                    data: $('#updateStudentForm').serialize(),
                    url: _root_ + 'alumnos/actualizarAlumno'
                })
                .then(function(response){
                    if (response.success) {
                        // Message confirmation
                        _this.getUpdateForm(_this.getActiveForm());
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-blue');
                    } else {
                        // Message Notification
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red');
                    }
                });
            } else {
                $('#general_snack').attr('data-content', 'Campos obligatorios vacios, revise e intente de nuevo');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-green');
            }
        });

        // Update datos del tutor
        $('#update_tutor').click(function(event) {
            event.preventDefault();
            let tutor   = $('#tutor_id').val(),
                name    = $('#name').val(),
                surname = $('#surname').val();

            if (tutor !== '' && name !== '' && surname !== '') {
                $.ajax({
                    synch: 'true',
                    type: 'POST',
                    data: $('#updateTutorForm').serialize(),
                    url: _root_ + 'alumnos/actualizarTutor'
                })
                .then(function(response){
                    if (response.success) {
                        // Message confirmation
                        _this.getUpdateForm(_this.getActiveForm());
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-blue');
                    } else {
                        // Message Notification
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red');
                    }
                });
            } else {
                $('#general_snack').attr('data-content', 'Campos obligatorios vacios, revise e intente de nuevo');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-green');
            }
        });

        // Update datos academicos
        $('#update_studies').click(function(event) {
            event.preventDefault();
            let student = $('#student').val() || undefined;

            if (student !== '' && student !== undefined) {
                $.ajax({
                    synch: 'true',
                    type: 'POST',
                    data: $('#updateStudiesForm').serialize(),
                    url: _root_ + 'alumnos/actualizarEstudios'
                })
                .then(function(response){
                    if (response.success) {
                        // Message confirmation
                        _this.getUpdateForm(_this.getActiveForm());
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-blue');
                    } else {
                        // Message Notification
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red');
                    }
                });
            } else {
                $('#general_snack').attr('data-content', 'Error: reporte este problema por favor!');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-green');
            }
        });
    },


    activeData: function() {
        if ($('#birthday').length) {
            pikadayResponsive(document.getElementById("birthday"),{
                classes : "form-control form-control-sm",
                placeholder: "Fecha de Nacimiento"
            });
        }
            
        $("#avatar").fileinput({
            showCaption: true,
            browseClass: "btn btn-info btn-sm btn-lg",
            fileType: "image"
        });

        $("#course").change(function(){
            if (parseInt($(this).val()) === 0) {
                $("#groups").attr('disabled', true);
            } else {
                $("#groups").attr('disabled', false);
            }
        });
    },
    
};

Perfil.initialize();
