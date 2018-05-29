var Inscripcion = {
    opts: {
        groupRequired: false
    },

    initialize: function(){
        console.log('Inscripcion Initialize');
        this.setActiveForm();
        this.navigateInscriptionForms();
        
        this.existStudent();
        this.getGroupsByCourse();
        this.getClassInfo();
        this.activeData();
    },

    setActiveForm: function(){
        form = sessionStorage.getItem('formNewStudent');
        $('#form_'+form).addClass('active');
        this.getInscriptionForm(form);
    },

    getActiveForm: function(){
        let activeView = sessionStorage.getItem('formNewStudent');
        return activeView;
    },

    getInscriptionForm: function(form='tutor') {
        let _this = this;
        $.ajax({
            synch: 'false',
            type: 'GET',
            data: {formulario: form},
            url: _root_ + 'alumno/formularioInscripcion',
            success: function(data){
                $('#formulario').html(data);
                _this.studentHasTutor();
                _this.tutorExist();
                _this.useTutorData();
                _this.studentExist();
                _this.handleFormActions();

                // Process forms
                _this.createTutor();
                _this.createStudent();
                _this.createStudies();

                _this.cancelRegister();

                // Helpers
                _this.setHelperInfo();
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
            _this.getInscriptionForm(form);
        });
    },


    /////////////////////////////////////
    // =  =  =  = Tutor  =  =  =  =  = //
    /////////////////////////////////////
    
    tutorExist: function(){
        let _this = this;
        $('#name_tutor').keydown(function() {
            if ($('#name_tutor').val().length >= 2) {
                $.ajax({
                    data: {
                        name: $('#name_tutor').val(),
                        surname: $('#surname_t').val(),
                        lastname: $('#lastname_t').val()
                    },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumno/validarTutor',
                    success: function(data){
                        if (data !== null) {
                            let name = data.namet+' '+data.surnamet+' '+data.lastnamet;
                            $('#tutor-name').text(name);
                            $('#tutor-job').text(data.job);
                            $('#exist_tutor').removeClass('d-none').addClass('border');
                            $('#useTutor').attr('data-tutor', data.id_tutor);
                        }
                    }
                });
            } else {
                $('#exist_tutor').removeClass('border').addClass('d-none');
            }
        });
    },

    useTutorData: function(){
        $('#useTutor').click(function(){
            let tutor = $(this).data('tutor');
            $.ajax({
                data: {
                    tutor: tutor
                },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'alumno/obtenerDatosTutor',
                success: function(response){
                    if (response !== null) {
                        let name = response.namet + ' ' + response.surnamet +' '+ response.lastnamet;
                        localStorage.setItem('tutor_id', response.id_tutor);
                        localStorage.setItem('tutor_name', name);
                        localStorage.setItem('address', response.id_address);
                        localStorage.setItem('street', response.street);
                        localStorage.setItem('number', response.st_number);
                        localStorage.setItem('between', response.st_between);
                        localStorage.setItem('colony', response.colony);

                        $('#info_tutor').addClass('d-none');
                        $('#continue').removeClass('d-none');
                        $('#exist_tutor').removeClass('border').addClass('d-none');
                        _this.navigateInscriptionForms();
                    }
                }
            });
        });

        $('#notUseTutor').click(function(event){
            event.preventDefault();
            $('.data_tutor').val('');
            $('#exist_tutor').removeClass('border').addClass('d-none');
        });
    },

    studentHasTutor: function() {
        let _this = this;
        $('.has_tutor').click(function(event) {
            if ($(this).val() == 'si') {
                $('#info_tutor').removeClass('d-none');
                $('#continue').addClass('d-none');
            } else {
                $('#info_tutor').addClass('d-none');
                $('#continue').removeClass('d-none');
                _this.navigateInscriptionForms();
            }
        });
    },

    createTutor: function() {
        let _this = this;
        $('#createTutor').click(function(event){
            event.preventDefault();

            let name     = $('#name').val(),
                surname  = $('#surname').val(),
                relation = $('#relation');
            if (name !== '' && surname !== '' && relation !== '') {
                $.ajax({
                    synch: 'true',
                    type: 'POST',
                    data: $('#tutorForm').serialize(),
                    url: _root_ + 'alumno/crearTutor'
                })
                .then(function(response){
                    if (response.success) {
                        // Message confirmation
                        localStorage.setItem('tutor_id', response.tutor);
                        localStorage.setItem('tutor_name', response.name);
                        localStorage.setItem('address', response.address);
                        localStorage.setItem('street', response.street);
                        localStorage.setItem('number', response.number);
                        localStorage.setItem('between', response.between);
                        localStorage.setItem('colony', response.colony);
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-green');
                    } else {
                        // Message Notification
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red');
                    }
                });
            } else {
                // Message Notification
                $('#general_snack').attr('data-content', 'Falta nombre-apellido o parentesco');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-blue');
            }
        });
    },


    //////////////////////////////////////////
    // =  =  =  =  =  Alumno  =  =  =  =  = //
    //////////////////////////////////////////
    studentExist: function() {
        let _this = this;

        $('#name').keydown(function() {
            if ($('#name').val().length >= 2) {
                $.ajax({
                    data: {
                        name: $('#name').val(),
                        surname: $('#surname').val(),
                        lastname: $('#lastname').val()
                    },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumno/validarAlumno',
                    success: function(data){
                        if (data !== null) {
                            let status = data.estado;
                            $('#student-name').text(data.name);
                            $('#student-group').text(data.grupo);

                            data.eliminado == 1 ? status += ' (Está en Alumnos/Eliminados)' : '';

                            $('#student-status').text(status);
                            $('#exist_student').removeClass('d-none').addClass('border');
                        }
                    }
                });
            } else {
                $('#exist_student').removeClass('border').addClass('d-none');
            }
        });
    },
    
    createStudent: function() {
        let _this = this;
        $('#createStudent').click(function(event){
            event.preventDefault();

            let name      = $('#name').val(),
                surname   = $('#surname').val(),
                hasAvatar = false;
            if (name !== '' && surname !== '') {
                let formData = new FormData();

                // formData.append('data', )
                if ($('#avatar').val() !== '') {
                    hasAvatar = true;
                    let avatar = $('#avatar').prop("files")[0];
                    formData.append('avatar_file',avatar);
                }

                $.ajax({
                    synch: 'true',
                    type: 'POST',
                    data: $('#studentForm').serialize(),
                    url: _root_ + 'alumno/crearAlumno'
                })
                .then(function(response){
                    console.log(response);
                    if(response.success){
                        localStorage.setItem('student_id', response.student);
                        localStorage.setItem('student_name', response.name);
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-green');
                        if(hasAvatar){
                            formData.append('student',response.student_id);
                            formData.append('genre',response.genre);
                            $.ajax({
                                synch: 'true',
                                type: 'POST',
                                contentType: false,
                                processData: false,
                                data: formData,
                                url: _root_ + 'alumno/crearAvatar'
                            })
                            .then(function(feeback){
                                console.log(feeback);
                                if(!feeback.success){
                                    setTimeout(function(){
                                        $('#general_snack').attr('data-content', feeback.message);
                                        $('#general_snack').snackbar('show');
                                        $('.snackbar').addClass('snackbar-blue');
                                    }, 4280);
                                }
                            });
                        }
                    } else {
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red');
                    }                   
                });
            } else {
                // Message Notification
                $('#general_snack').attr('data-content', 'Falta nombre-apellido');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-blue');
            }
        });
    },

    createStudies: function() {
        let _this = this;
        $('#createStudies').click(function(event){
            event.preventDefault();

            // Validar que se haya seleccionado un grupo
            if(_this.opts.groupRequired && $('#groups').val() === ''){
                $('#general_snack').attr('data-content', 'Seleccione un grupo, por favor!');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-red');
                return false;
            }

            let course = $('#course').val();
            if (course !== '' && course !== undefined) {
                $.ajax({
                    synch: 'true',
                    type: 'POST',
                    data: $('#studiesForm').serialize(),
                    url: _root_ + 'alumno/crearEstudios'
                })
                .then(function(response){
                    if (response.success) {
                        let _urlMapa  = _root_ + 'mapa/u/'+response.student,
                            _urlFin   = _root_ + 'alumno',
                            _urlNuevo = _root_ + 'alumno/inscribir';
                        let btnMapa   = '<a href="'+_urlMapa+'" class="btn btn-info mr-2">Agregar Croquis</a>',
                            btnFin    = '<a href="'+_urlFin+'" class="btn btn-success mx-2">Finalizar</a>',
                            btnNuevo  = '<a href="'+_urlNuevo+'" class="btn btn-primary mx-2">Registrar otro alumno</a>';

                        _this.cleanHelperInfo();

                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-green');

                        console.log(localStorage.getItem('student_name'))

                        $('#formulario').addClass('mt-5 text-center').html(btnMapa + btnFin + btnNuevo);
                    } else {
                        // Message Notification
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red');
                    }
                });
            } else {
                // Message Notification
                $('#general_snack').attr('data-content', 'Seleccione un curso de la lista por favor..');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-blue');
            }
        });
    },

    cancelRegister: function(){
        let _this = this;
        $('#cancelRegister').click(function(event) {
            event.preventDefault();

            let steep = $(this).data('steep');
            if (parseInt(steep) === 1) {
                $.ajax({
                    synch: 'true',
                    type: 'POST',
                    data: {student: form},
                    url: _root_ + 'clean/student',
                    success: function(data){
                        _this.cleanHelperInfo();
                    }
                });
            } else {
                $.ajax({
                    synch: 'true',
                    type: 'POST',
                    data: {tutor: form},
                    url: _root_ + 'clean/tutor',
                    success: function(data){
                        _this.cleanHelperInfo();
                    }
                });
            }
        });
    },

    setHelperInfo: function(){
        if (localStorage.getItem('tutor_id') !== null) {
            $('#tutor_name').text('(Tutor: '+ localStorage.getItem('tutor_name') + ')');
            $('#tutor_id').val(localStorage.getItem('tutor_id'));
            $('#address_id').val(localStorage.getItem('address'));
            $('#street_s').val(localStorage.getItem('street'));
            $('#number_s').val(localStorage.getItem('number'));
            $('#between_s').val(localStorage.getItem('between'));
            $('#colony_s').val(localStorage.getItem('colony'));
        }

        if (localStorage.getItem('student_id') !== null) {
            $('#student_name').text('(Alumno: '+ localStorage.getItem('student_name') + ')');
            $('#student_id').val(localStorage.getItem('student_id'));
        }
    },

    cleanHelperInfo: function(){
        if (localStorage.getItem('tutor_id') !== null) {
            localStorage.removeItem('tutor_id');
            localStorage.removeItem('tutor_name');
            localStorage.removeItem('address');
            localStorage.removeItem('street');
            localStorage.removeItem('number');
            localStorage.removeItem('between');
            localStorage.removeItem('colony');
        }

        if (localStorage.getItem('student_id') !== null) {
            localStorage.removeItem('student_id');
            localStorage.removeItem('student_name');
        }
    },

    handleFormActions: function() {
        let _this = this;
        $('[data-toggle="tooltip"]').tooltip();

        // Radios: padece alguna enfermedad
        $('.isSick').click(function(event) {
            if ($(this).val() == 'Si') {
                $('.sicknes_detail').removeClass('d-none');
            } else {
                $('.sicknes_detail').addClass('d-none');
            }
        });

        // Radios: Ha tomado un curso previo
        $('.isPrevious').click(function() {
            if ($(this).val() == '1') {
                $('#describa').removeClass('d-none');
            } else {
                $('#describa').addClass('d-none');
            }
        });

        // Fileinput custom
        $("#avatar").fileinput({
            showCaption: true,
            browseClass: "btn btn-info btn-sm btn-lg",
            fileType: "image"
        });

        // Datepicker fecha de inicio
        $('#fecha_inicio').datetimepicker({format: 'YYYY-MM-DD'});

        // Helper para especificar lugar de trabajo
        $('#ocupation').on('change', function(){
            let ocupacion = $('#ocupation').val();

            if (ocupacion === "Estudiante") {
                $('#lugar_trabajo').attr('placeholder','¿Dónde Estudia?');
            }else if (ocupacion === "Trabajador") {
                $('#lugar_trabajo').attr('placeholder','¿Dónde Trabaja?');
            } else {
                $('#lugar_trabajo').attr('placeholder','. . .');
            }
        });

        // Mostra/ocultar grados de estudio
        $('#nivel').change(function(){
            let nivel = $(this).val();
            if (nivel === 'Primaria' || nivel === 'Licenciatura') {
                $('.extra').removeClass('d-none');
            } else {
                $('.extra').addClass('d-none');
            }
        });

        // Obtener grupos activos
        $('#course').change(function(){
            let curso = parseInt($(this).val());
            $('#clasedata').addClass('d-none');

            if (curso !== '' && curso !== 0) {
                $.ajax({
                    data: { curso: curso },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumno/gruposPorNivel',
                    success: function(grupos){
                        let options = '<option value="" hidden>Seleccione un grupo...</option>';

                        if(grupos !== null) {
                            _this.opts.groupRequired = true;
                            $('#groups').addClass('required');
                            $.each(grupos, function(i, grupo){
                                options += '<option value="'+grupo.class_id+'">'+grupo.group_name+'</option>';
                            });
                        } else {
                            _this.opts.groupRequired = false;
                            $('#groups').removeClass('required');
                            options = '<option value="">Curso sin grupos</option>';
                        }

                        $('#groups').html(options);
                    }
                });
            }

            if (curso === 0) {
                let option = '<option value="0">En Espera</option>';
                $('#groups').removeClass('required');
                $('#clase_id').val(0);
                $('#groups').html(option);
            }
        });

        $('#groups').change(function(){
            var clase = $(this).val();
            if (clase !== '') {
                $.ajax({
                    data: { clase: clase },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumno/datosClase',
                    success: function(data){
                        if (data !== null) {
                            $('#clasedata').removeClass('d-none');
                            $('#clasedata').addClass('border bg-light');
                            $('#groups').removeClass('required');

                            $('#clase_id').val(data.class_id);
                            $('#clase_name').html('<strong>Clase: </strong>'+data.course+' '+data.group_name);
                            $('#schedule').html('<strong>Horario: </strong>'+data.hour_init+' - '+data.hour_end);
                            $('#date_start').html('<strong>Inicia: </strong>'+data.date_init);
                            $('#date_end').html('<strong>Termina: </strong>'+data.date_init);
                            $('#fecha_inicio').val(data.date_init);

                            let days = '';
                            if (data.days !== null) {
                                $.each(data.days, function(i, item){
                                    (i+1) < data.days.length ? days += item.day + ', ' : days += item.day; 
                                });
                            }
                            $('#days').html('<strong>Días: </strong>'+days);
                        }
                    }
                });
            }
        });
    },




    existStudent: function() {
        let that = this;

        $('#name').keydown(function() {
            if ($('#name').val().length >= 2) {
                $.ajax({
                    data: {
                        name: $('#name').val(),
                        surname: $('#surname').val(),
                        lastname: $('#lastname').val()
                    },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumno/existeAlumno',
                    success: function(data){
                        if (data != 'null') {
                            var res = JSON.parse(data);
                            var head  = 'El alumno: ';
                            var foot  = ' <br /> Ya está registrado, se encuentra en '+res.grupo+'.<br /> ¿Desea continuar con el registro actual?';
                            var student = head+res.name+' '+res.surname+' '+res.lastname+foot;
                            var btnOk = '<a id="btn_continue" class="btn btn-sm btn-info btn-raised">Continuar Registro</a>';
                            var btnNo = ' <a id="btn_cancel" class="btn btn-sm btn-warning btn-raised">Cancelar Registro</a>';
                            
                            $('#exist_student').html('<p class="text-center">'+student+'</p>'+btnNo+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+btnOk);

                            that.selectAction();
                        }
                    }
                });
            } else {
                $('#exist_student').html('');
                $('#exist_student').removeClass('mini-box');
            }
        });
    },

    selectAction: function(){
        $('#btn_cancel').click(function(){
            $.ajax({
                synch: 'true',
                type: 'POST',
                url: _root_ + 'alumno/cancelarRegistro',
                success: function(data){
                    $('#name').val('');
                    $('#surname').val('');
                    $('#lastname').val('');
                    $('#street_s').val('');
                    $('#number_s').val('');
                    $('#between_s').val('');
                    $('#colony_s').val('');
                    $('#btn_continue').click();
                    $('#general_snack').attr('data-content', 'Registro cancelado correctamente!');
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-green');
                    // window.location(_root_+'alumno/nuevo');
                }
            });
        });

        $('#btn_continue').click(function(){
            $('#exist_student').hide();
            $('#exist_student').html('');
            $('#exist_student').removeClass('mini-box');
        });
    },

    getGroupsByCourse: function() {
        $('#course').change(function(){
            var curso = $(this).val();
            if (curso !== '' && curso !== '0') {
                $.ajax({
                    data: {
                        curso: curso
                    },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumno/obtenerNivelesCurso',
                    success: function(data){
                        var option = '<option value="" hidden>Seleccione grupo...</option>';
                        if (data !== 'null') {
                            var res = JSON.parse(data);
                            for (var i = 0; i < res.length; i++) {
                                option = option + '<option value="'+res[i].class_id+'">'+res[i].group_name+'</option>';
                            }
                        } else {
                            option = '<option value="">Curso sin grupos</option>';
                        }
                        $('#groupList').attr('required', true);
                        $('#groupList').attr('disabled', false);
                        $('#groupList').html(option);
                    }
                });
            }

            if (curso === '0') {
                var option = '<option value="0">En Espera</option>';
                $('#groupList').attr('required', false);
                $('#groupList').attr('disabled', true);
                $('#clase_id').val(0);
                $('#groupList').html(option);
            }
        });
    },

    //Informacion de la clase->inscribir alumno.
    getClassInfo: function() {
        $('#groupList').change(function(){
            var clase = $(this).val();
            // console.log(clase);
            if (clase !== '') {
                $.ajax({
                    data: { clase: clase },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumno/obtenerInfoClase',
                    success: function(a){
                        // console.log(a);
                        if (a !== 'null') {
                            $('#clasedata').addClass('rounded');
                            var res = JSON.parse(a);
                            // console.log(res);
                            $('#clase_id').val(res.class_id);
                            $('#clase_name').html('<strong>Clase: </strong>'+res.course+' '+res.group_name);
                            $('#horario_c').html('<strong>Horario: </strong>'+res.hour_init+' - '+res.hour_end);
                            $('#f_inicio').html('<strong>Inicia: </strong>'+res.date_init);
                            $('#f_fin').html('<strong>Termina: </strong>'+res.date_init);

                            $('#fecha_inicio').val(res.date_init);

                            $.ajax({
                                data: { clase: res.schedul_id },
                                synch: 'true',
                                type: 'POST',
                                url: _root_ + 'alumno/obtenerDiasClase',
                                success: function(data){
                                    console.log(data);
                                    if (data !== 'null') {
                                        var r = JSON.parse(data);
                                        var day = '';
                                        for (var i = 0; i < r.length; i++) {
                                            day !== '' ? day = day+', '+ r[i].day : day = day + r[i].day;
                                        }
                                        $('#dias').html('<strong>Días: </strong>'+day);
                                    }
                                }
                            });
                        }
                    }
                });
            }
        });
    },

    activeData: function() {

        
    },
    
};

Inscripcion.initialize();
