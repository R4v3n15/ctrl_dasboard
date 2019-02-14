var Inscripcion = {
    opts: {
        groupRequired: false
    },

    initialize: function(){
        console.log('Inscripcion Initialize');
        this.setActiveForm();
        this.navigateInscriptionForms();
    },

    setActiveForm: function(){
        // Iniciado desde main.js
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
            url: _root_ + 'alumnos/formularioInscripcion',
            success: function(data){
                $('#formulario').html(data);
                _this.studentHasTutor();
                _this.tutorExist();
                _this.useTutorData();
                _this.studentExist();
                _this.handleFormActions();

                // Process forms
                _this.resetTutorForm();
                _this.createTutor();
                _this.cancelStudentRegister();
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
                    url: _root_ + 'alumnos/validarTutor',
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
    	let _this = this;
        $('#useTutor').click(function(){
            let tutor = $(this).data('tutor');
            $.ajax({
                data: {
                    tutor: tutor
                },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'alumnos/obtenerDatosTutor',
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
                        $('#toggle-hastutor').addClass('d-none');
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

    resetTutorForm: function(){
    	$('#reset_form').click(function(event) {
    		event.preventDefault();
    		if (localStorage.getItem('tutor_id') !== null) {
	            localStorage.removeItem('tutor_id');
	            localStorage.removeItem('tutor_name');
	            localStorage.removeItem('address');
	            localStorage.removeItem('street');
	            localStorage.removeItem('number');
	            localStorage.removeItem('between');
	            localStorage.removeItem('colony');
        	}

        	console.log(localStorage.getItem('tutor_id'));
        	$('#info_tutor').removeClass('d-none');
        	$('#toggle-hastutor').removeClass('d-none');
            $('#continue').addClass('d-none');
            $('#exist_tutor').addClass('border').removeClass('d-none');
    	});
    },

    studentHasTutor: function() {
        let _this = this;
        $('.has_tutor').click(function(event) {
            if ($(this).val() == 'si') {
                $('#info_tutor').removeClass('d-none');
                $('#reset_form').removeClass('d-none');
                $('#continue').addClass('d-none');
            } else {
                $('#info_tutor').addClass('d-none');
                $('#reset_form').addClass('d-none');
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
                    url: _root_ + 'alumnos/crearTutor'
                })
                .then(function(response){
                    // console.log(response);
                    if (response.success) {
                        // Message confirmation
                        localStorage.setItem('tutor_id', response.tutor);
                        localStorage.setItem('tutor_name', response.name);
                        localStorage.setItem('address', response.address);
                        localStorage.setItem('street', response.street);
                        localStorage.setItem('number', response.number);
                        localStorage.setItem('between', response.between);
                        localStorage.setItem('colony', response.colony);

                        let seguir = '<a href="#" class="btn btn-sm btn-info btn_forms" id="form_alumno" data-form="alumno">Continuar <i class="fa fa-arrow-right"></i></a>';
                        $('#formulario').addClass('mt-5 text-center').html(seguir);
                        _this.navigateInscriptionForms();

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
                    url: _root_ + 'alumnos/validarAlumno',
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

        $('#dissmiss').click(function(event) {
        	event.preventDefault();
        	$('#student-name').text('');
            $('#student-group').text('');
            $('#student-status').text('');
            $('#exist_student').removeClass('border').addClass('d-none');
        });
    },

    cancelStudentRegister: function(){
    	let _this = this;
        $('#cancelStudentRegister').click(function(event) {
            event.preventDefault();
            _this.resetAllResgistration();
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
                    url: _root_ + 'alumnos/crearAlumno'
                })
                .then(function(response){
                    // console.log(response);
                    if(response.success){
                        localStorage.setItem('student_id', response.student);
                        localStorage.setItem('student_name', response.name);

                        let seguir = '<a href="#" class="btn btn-sm btn-info btn_forms" id="form_estudios" data-form="estudios">Continuar <i class="fa fa-arrow-right"></i></a>';
                        $('#formulario').addClass('mt-5 text-center').html(seguir);
                        _this.navigateInscriptionForms();

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
                                url: _root_ + 'alumnos/crearAvatar'
                            })
                            .then(function(feeback){
                                // console.log(feeback);
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
                    url: _root_ + 'alumnos/crearEstudios'
                })
                .then(function(response){
                    // console.log(response);
                    if (response.success) {
                        let _urlMapa  = _root_ + 'mapa/u/'+response.student,
                            _urlFin   = _root_ + 'alumnos',
                            _urlNuevo = _root_ + 'alumnos/inscribir';
                        let btnMapa   = '<a href="'+_urlMapa+'" class="btn btn-info mr-2">Agregar Croquis</a>',
                            btnFin    = '<a href="'+_urlFin+'" class="btn btn-success mx-2">Finalizar</a>',
                            btnNuevo  = '<a href="'+_urlNuevo+'" class="btn btn-primary mx-2">Registrar otro alumno</a>';

                        _this.cleanHelperInfo();

                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-green');

                        sessionStorage.setItem('formNewStudent', 'tutor');

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
                if (localStorage.getItem('tutor_id') !== null) {
		            deleteTutorInfo(localStorage.getItem('tutor_id'));
		        }
            }

            if (parseInt(steep) === 2) {
            	let tutor   = localStorage.getItem('tutor_id'),
            		student = localStorage.getItem('student_id');

            	if (student !== null) {
		            deleteStudentInfo(student);
		        }

            	if (tutor !== null) {
		            deleteTutorInfo(tutor);
		        }
            }

            $('.form-control').val('');
            _this.resetAllResgistration();
        });

        function deleteStudentInfo(student){
        	$.ajax({
                synch: 'true',
                type: 'POST',
                data: {student: student},
                url: _root_ + 'clean/student'
            })
            .done(function(response){
            	_this.cleanHelperInfo();
            	console.log(response.message)
            });
        }

        function deleteTutorInfo(tutor){
        	$.ajax({
                synch: 'true',
                type: 'POST',
                data: {tutor: tutor},
                url: _root_ + 'clean/tutor'
            })
            .done(function(response){
            	_this.cleanHelperInfo();
            	console.log(response.message)
            });
        }
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

    resetAllResgistration: function() {
        $('.btn_forms').removeClass('active');
        sessionStorage.setItem('formNewStudent', 'tutor');
        $('#form_tutor').addClass('active');
        this.getInscriptionForm('tutor');
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
        if ($('#fecha_inicio').length) {
            pikadayResponsive(document.getElementById("fecha_inicio"),{
                classes : "form-control form-control-sm",
                placeholder: "Fecha de Inicio"
            });
        }

        // Datepicker fecha de inicio
        if ($('#fecha_inscripcion').length) {
            pikadayResponsive(document.getElementById("fecha_inscripcion"),{
                classes : "form-control form-control-sm",
                placeholder: "Fecha de Inscripción"
            });
        }

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
                    url: _root_ + 'alumnos/gruposPorNivel',
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
                    url: _root_ + 'alumnos/datosClase',
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






    selectAction: function(){
        $('#btn_cancel').click(function(){
            $.ajax({
                synch: 'true',
                type: 'POST',
                url: _root_ + 'alumnos/cancelarRegistro',
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
};

Inscripcion.initialize();
