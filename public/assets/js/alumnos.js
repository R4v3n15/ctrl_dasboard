var Alumnos = {
    vars : {
        alumnos: [],
        currentPage: 0
    },

    initialize: function(){
        console.log('Alumnos Initialize');
        this.setActiveView();
        this.changeViewStudent();
        this.addToGroup();

        this.feedGroupsList();
        // this.getStudents();
        // this.defaultGetStudents();
        // this.activeData();
        // this.getLevels();
        // this.getGroupsByCourse();
        // this.checkoutStudent();
        // this.displayStudentsCheckout();
        // this.confirmDelete();
        // this.confirmDeleteStudents();
    },

    setActiveView: function(){
        // Iniciado desde main.js
        let active_view = sessionStorage.getItem('vista_alumnos');
        this.getStudentsTable($('#'+active_view).data('curso'));
    },

    getActiveView: function(){
        let active_view = sessionStorage.getItem('vista_alumnos');
        return $('#'+active_view).data('curso');
    },

    changeViewStudent: function(){
        let _this = this;
        $('.students_view').click(function(event){
            event.preventDefault();
            let _new = $(this).attr('id');
            $('.students_view').removeClass('active');
            sessionStorage.setItem('vista_alumnos', _new);
            $('#'+_new).addClass('active');

            _this.setActiveView();
        });
    },


    getStudentsTable: function(curso, page=0){
        let _this = this;
        $.ajax({
            data: { curso: curso, page: page },
            synch: 'true',
            type: 'POST',
            url: _root_ + 'alumnos/tablaAlumnos',
            success: function(data){
                $('#tabla_alumnos').html(data);
                feather.replace();
                _this.countStudents();
                _this.navigationPage(curso);
                _this.addStudentInGroup();
                _this.changeStudentGroup();
            }
        });
    },

    countStudents: function(){
        let that = this;
        $.ajax({
            synch: 'true',
            type: 'POST',
            url: _root_ + 'alumnos/alumnosCurso',
            success: function(data){
                $.each(data, function(i, value){
                    $('#'+i).text(value);
                });
            }
        });
    },

    navigationPage: function(){
        let _this = this;
        $(".page-students").on('click', function(event){
            event.preventDefault();
            _this.vars.currentPage = parseInt($(this).data("students"));
            _this.getStudentsTable(_this.getActiveView(), $(this).data("students"));
        });
    },

    addStudentInGroup: function() {
        let _this = this;
        $('.adding_group').on('click', function(event){
            event.preventDefault();

            let student = $(this).data('student');
            $('#course').val('');
            $('#groups').val('');
            $('#alumno_id').val(student);
            $('#modalAddTitle').text('Agregar a Grupo');
            $('#extra_message').text('');

            $('#modalAddToGroup').modal('show');
        });
    },

    changeStudentGroup: function(){
        let _this = this;
        $('.change_group').on('click', function(event){
            event.preventDefault();

            let student = $(this).data('student'),
                grupo   = $(this).data('group');

            $('#course').val('');
            $('#groups').val('');
            $('#alumno_id').val(student);

            $('#modalAddTitle').text('Cambiar de Grupo');
            $('#extra_message').text('Grupo Actual: '+ grupo);

            $('#modalAddToGroup').modal('show');
        });
    },

    addToGroup: function() {
        let _this = this;
        $('#add_in_group').on('click', function(event){
            event.preventDefault();

            var alumno = $('#alumno_id').val(),
                curso  = $('#course').val(),
                clase  = $('#groups').val();
                // console.log(alumno, clase);
            $.ajax({
                data: { alumno: alumno, clase: clase },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'alumnos/agregarAlumnoGrupo',
                success: function(data){
                    if (data !== '0') {
                        // Close Modal
                        $('#modalAddToGroup').modal('hide');

                        // Message confirmation
                        $('#general_snack').attr('data-content', 'Alumno agregado al grupo correctamente!');
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-blue');
                    } else {
                        // Message Notification
                        $('#general_snack').attr('data-content', 'No se agregó el alumno al grupo, intente de nuevo!');
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red');
                    }

                    //Verificar en que vista y pagina debe mantenerse al usuario
                    _this.getStudentsTable(_this.getActiveView, _this.vars.currentPage); 
                }
            });
        });
    },

    // Cargar grupos en select al escoger un curso
    feedGroupsList: function() {
        let _this = this;
        $('#course').change(function(event){
            event.preventDefault();

            var curso = $(this).val();
            if (curso !== '') {
                $.ajax({
                    data: {
                        curso: curso
                    },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumnos/obtenerGrupos',
                    success: function(data){
                        let option = '<option value="" hidden>Seleccione...</option>';
                        if (data !== null) {
                            $.each(data, function(i, course){
                               option += '<option value="'+course.class_id+'">'+course.group_name+'</option>';
                            });
                        } else {
                            option = '<option value="0">Curso sin grupos</option>';
                        }

                        $('#groups').html(option);
                    }
                });
            }
        });
    },




    ////////////////////////////////////////////
    // =  =   =  =  = O L D  =  =  =  =  =  = //
    ////////////////////////////////////////////

    getStudentProfile: function() {
        let that = this;
        $('.profile').on('click', function(){
            var curso   = $(this).data('curso'),
                student = $(this).data('student'),
                tutor   = $(this).data('tutor'),
                clase   = $(this).data('clase');
            that.showStudentProfile(curso, student, tutor, clase);
        });
    },

    showStudentProfile: function(curso, student, tutor, clase){
        let that = this;
        $.ajax({
            data: {
                student: student,
                tutor:   tutor,
                clase:   clase
            },
            synch: 'true',
            type: 'POST',
            url: _root_ + 'alumnos/obtenerPerfilAlumno',
            success: function(data){
                $('#myTabContent').removeClass('well-content');
                $('#myTabContent').addClass('body-content');
                $('#head_menu').hide();
                $('#second_head').show();
                switch(parseInt(curso)) {
                    case 1: $('#club').html(data);
                        break;
                    case 2: $('#primary').html(data);
                        break;
                    case 3: $('#adolescent').html(data);
                        break;
                    case 4: $('#adult').html(data);
                        break;
                    case 4: $('#penddings').html(data);
                        break;
                    case 6: $('#all_students').html(data);
                        break;
                }

                $('#return_list').click(function(){
                    $('#myTabContent').addClass('well-content');
                    $('#myTabContent').removeClass('body-content');
                    that.displayStudents(curso);
                });
            }
        });
    },

    displayStudentsCheckout: function(){
        let that = this;
        $.ajax({
            synch: 'true',
            type: 'POST',
            url: _root_ + 'alumnos/obtenerAlumnosBaja',
            success: function(data){
                $('#checkout_list').html(data);
                $('#tbl_checkout').DataTable();
                that.checkinStudent();
                that.getStudentProfile();
            }
        });
    },

    getLevels: function() {
        $('#course').change(function(){
            var curso = $(this).val();
            if (curso !== '') {
                $.ajax({
                    data: {
                        curso: curso
                    },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumnos/obtenerNivelesCurso',
                    success: function(a){
                        var option = '<option value="">Seleccione...</option>';
                        if (a !== 'null') {
                            var res = JSON.parse(a);
                            console.log(res);
                            for (var i = 0; i < res.length; i++) {
                                option = option + '<option value="'+res[i].class_id+'">'+res[i].group_name+'</option>';
                            }
                        } else {
                            option = '<option value="">Curso sin grupos</option>';
                        }
                        
                        $('#levelList').html(option);
                    }
                });
            }
        });
    },

    getGroupsByCourse: function(){
        let that = this;
        $('#course').on('change', function(){
            if ($(this).val() !== '') {
                $.ajax({
                    data: { curso: $(this).val() },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumnos/obtenerNivelesCurso',
                    success: function(data){
                        var option = '<option value="">Seleccione...</option>';
                        if (data !== 'null') {
                            var res = JSON.parse(data);
                            for (var i = 0; i < res.length; i++) {
                                option = option + '<option value="'+res[i].class_id+'">'+res[i].group_name+'</option>';
                            }
                        } else {
                            option = '<option value="">Curso sin grupos</option>';
                        }
                        
                        $('#groups').html(option);
                    }
                });
            }
        });
    },

    setStudentID: function(){
        let that = this;
        $('.adding_group').on('click', function(){
            var student = $(this).data('student');
            $('#course').val('');
            $('#groups').val('');
            $('#alumno_id').val(student);
            $('#add_to_group').modal('show');
        });
    },

    selectMultipleStudents: function(activo){
        $('#select_all_'+activo).change(function(){
            var opt = $("#select_all_"+activo).prop("checked");
            console.log(opt);
            if (opt) {
                $(".check_one").prop("checked", true);
            } else {
                $(".check_one").prop("checked", false);
            }
        });
    },

    changeGroupStudent: function(){
        let that = this;
        $('.change_group').on('click', function(){;
            var student = $(this).data('student'),
                curso   = $(this).data('course'),
                grupo   = $(this).data('group');
            $('#alumno_number').val(student);
            console.log(curso);
            $("#course_list option[value='" + curso +"']").attr("selected", true);
            $("#course_list option[value!='" + curso +"']").attr("selected", false);
            $.ajax({
                data: { curso: curso },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'alumnos/obtenerNivelesCurso',
                success: function(data){
                    console.log(data);
                    var option = '';
                    if (data !== 'null') {
                        var res = JSON.parse(data);
                        for (var i = 0; i < res.length; i++) {
                            var attr = res[i].group_id == grupo ? 'selected' : '';
                            option = option + '<option '+attr+' value="'+res[i].class_id+'">'+res[i].group_name+'</option>';
                        }
                    }
                    $('#grupos').html(option);
                }
            });

            $('#change_group').modal('show');
        });

        $('#course_list').on('change', function(){
            if ($(this).val() !== '' && $(this).val() !== '0') {
                $.ajax({
                    data: { curso: $(this).val() },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumno/obtenerNivelesCurso',
                    success: function(data){
                        var option = '<option value="">Seleccione...</option>';
                        if (data !== 'null') {
                            var res = JSON.parse(data);
                            for (var i = 0; i < res.length; i++) {
                                option = option + '<option value="'+res[i].class_id+'">'+res[i].group_name+'</option>';
                            }
                        } else {
                            option = '<option value="">Curso sin grupos</option>';
                        }
                        
                        $('#grupos').html(option);
                    }
                });
            } else {
                if ($(this).val() === '0') {
                    $('#grupos').html('<option value="0">En espera</option>');
                }
            }
        });

        $('#do_change_group').on('click', function(){;
            var alumno = $('#alumno_number').val(),
                clase  = $('#grupos').val();

            if (alumno !== '0' && alumno !== '') {
                that.changeStudent(alumno, clase);
            } else if(alumno === '0' && alumno !== '') {
                that.changeStudents(clase);
            }
        });
    },

    changeStudent: function(alumno, clase) {
        let that = this;
        $.ajax({
            data: { alumno: alumno, clase: clase },
            synch: 'true',
            type: 'POST',
            url: _root_ + 'alumnos/cambiarGrupoAlumno',
            success: function(data){
                if (data === '1') {
                    $('#general_snack').attr('data-content', 'Reasignación de grupo realizado!');
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-blue');
                } else {
                    if (data === '0') {
                        $('#general_snack').attr('data-content', 'Falto información para cambio de grupo!');
                        $('#general_snack').snackbar('show');
                    } else {
                        $('#general_snack').attr('data-content', 'Error desconocido: no se realizó el cambio!');
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red');
                    }
                }

                $('#change_group').modal('hide');
                var view = sessionStorage.getItem('st_alive');
                that.displayStudents(view); 
            }
        });
    },

    changeStudents: function(clase){
        let that = this;
        if (clase !== '') {
            if (that.vars.alumnos.length > 0) {
                var alumnos = that.vars.alumnos;
                $.ajax({
                    data: { alumnos: alumnos, clase: clase },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumnos/cambiarGrupoAlumnos',
                    success: function(data){
                        if (data === '1') {
                            $('#general_snack').attr('data-content', 'Reasignación de grupo realizado!');
                            $('#general_snack').snackbar('show');
                            $('.snackbar').addClass('snackbar-blue');
                        } else {
                            if (data === '0') {
                                $('#general_snack').attr('data-content', 'Falto información para cambio de grupo!');
                            } else {
                                $('#general_snack').attr('data-content', 'Error desconocido: No se completó la acción!');
                                $('#general_snack').snackbar('show');
                                $('.snackbar').addClass('snackbar-red');
                            }
                        }
                        $('#change_group').modal('hide');
                        var view = sessionStorage.getItem('st_alive');
                        that.displayStudents(view); 
                    }
                });
                that.vars.alumnos = [];
            }
        } else {
            $('#general_snack').attr('data-content', 'Dejó campos vacios!');
            $('#general_snack').snackbar('show');
            $('.snackbar').addClass('snackbar-green');
        }
    },

    changeGroupStudentMultiple: function(){
        let that = this;
        $('.change_multi').click(() => {
            var alumnos = [], j=0;
            $.each($('.check_one'), function(i, item) {
                if ($(this).prop("checked")) {
                    alumnos[j] = $(this).val();
                    j++;
                }
            });

            $("#course_list").val('');
            $("#grupos").val('');

            if (alumnos.length > 0) {
                $('#alumno_number').val(0);
                that.vars.alumnos = alumnos;
                $('#change_group').modal('show');
            } else {
                $('#general_snack').attr('data-content', 'Seleccione al menos a un Alumno!');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-green');
            }
        });
    },

    //Dar de baja al alumno
    checkoutStudent: function() {
        let that = this;
        $('#check_out').change(function(){
            var opt          = $("#check_out").prop("checked"),
                student_id   = $("#check_out").data('id'),
                student_name = $("#check_out").data('name');
            if (opt) {
                $('#alumno_id').val(student_id);
                $('#student_name').text(student_name);
                $('#checkout').modal('show');
            } else {
                $('#id_alumno').val(student_id);
                $('#alumno_name').text(student_name);
                $('#checkin').modal('show');
            }
            console.log(opt);
        });

        $('#checkout_student').click(function(){
            var alumno = $('#alumno_id').val();
            $.ajax({
                data: { alumno: alumno, estado: 1 },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'alumnos/bajaAlumno',
                success: function(data){
                    if (data === '1') {
                        $('#student_snack').attr('data-content', 'Alumno dado de BAJA correctamente!');
                    } else {
                        $('#student_snack').attr('data-content', 'No fue posible dar de baja al alumno!');
                    }
                    $('#student_snack').snackbar('show');
                    $('#checkout').modal('hide');
                }
            });
        });

        $('#checkin_student').click(function(){
            var alumno = $('#id_alumno').val();
            $.ajax({
                data: { alumno: alumno, estado: 0 },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'alumnos/bajaAlumno',
                success: function(data){
                    if (data === '1') {
                        $('#student_snack').attr('data-content', 'Alumno dado de ALTA correctamente!');
                    } else {
                        $('#student_snack').attr('data-content', 'No fue posible dar de ALTA al alumno!');
                    }
                    $('#student_snack').snackbar('show');
                    $('#checkin').modal('hide');
                }
            });
        });

        $('#no_checkout').click(function(){
            $("#check_out").prop("checked", false);
        });
    },

    //Dar de alta alumno
    checkinStudent: function() {
        let that = this;
        $('.checkin_student').click(function(){
            var student_id   = $(this).data('alumno'),
                student_name = $(this).data('nombre');

            console.log(student_id, student_name);
            $('#num_alumno').val(student_id);
            $('#nombre_alumno').text(student_name);
            $('#checkin_st').modal('show');
        });

        $('#checked_student').click(function(){
            var alumno = $('#num_alumno').val();
            $.ajax({
                data: { alumno: alumno, estado: 0 },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'alumnos/bajaAlumno',
                success: function(data){
                    if (data === '1') {
                        $('#general_snack').attr('data-content', 'Alumno dado de ALTA correctamente!');
                    } else {
                        $('#general_snack').attr('data-content', 'No fue posible dar de ALTA al alumno!');
                    }
                    $('#general_snack').snackbar('show');
                    $('#checkin_st').modal('hide');
                    that.displayStudentsCheckout();
                }
            });
        });
    },

    getInvoiceListStudents: function(){
        let that = this;

        $('.invoice_list').click(function() {
            $.ajax({
                synch: 'true',
                type: 'POST',
                url: _root_ + 'alumnos/obtenerListaFactura',
                success: function(data){
                    $('#invoice_students_list').html(data);
                    $('#invoice_list').modal('show');
                    $('#tbl_invoice').DataTable();
                }
            });
        });
    },

    //TODO: pendigs
    deleteStudent: function(){
        let that = this;
        $('.btnDeleteStudent').on('click', function(){
            var alumno_id = $(this).attr('id'),
                alumno_name = $(this).data('name');
            $('#alumno_id').val(alumno_id);
            $('#alumno_name').text(alumno_name);
            $('#modalDeleteStudent').modal('show');
        });
    },

    confirmDelete: function(){
        let that = this;
        $('#btnConfirmDeleteStudent').on('click', function(){
            var alumno = $('#alumno_id').val();
            $.ajax({
                data: { alumno: alumno },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'alumnos/eliminarAlumno',
                success: function(data){
                    var response = JSON.parse(data);
                    if (response === 1) {
                        console.log(response);
                        $('#general_snack').attr('data-content', 'Alumno eliminado correctamente!');
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-blue');
                    } else {
                        $('#general_snack').attr('data-content', 'No fue posible eliminar al alumno!');
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red');
                    }
                    $('#modalDeleteStudent').modal('hide');
                    var view = sessionStorage.getItem('st_alive');
                    that.displayStudents(view);
                }
            });
        });
    },

    deleteSelectedStudents: function(){
        let that = this;
        $('.delete_multi').click(() => {
            var alumnos = [], j=0;
            $.each($('.check_one'), function(i, item) {
                if ($(this).prop("checked")) {
                    alumnos[j] = $(this).val();
                    j++;
                }
            });

            $("#course_list").val('');
            $("#grupos").val('');

            if (alumnos.length > 0) {
                $('#selected_students').text(j);
                that.vars.alumnos = alumnos;
                $('#modalDeleteSelectedStudent').modal('show');
            } else {
                $('#general_snack').attr('data-content', 'Seleccione al menos a un Alumno!');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-green');
            }
        });
    },

    confirmDeleteStudents: function(clase){
        let that = this;
        $('#btnConfirmDeleteStudents').on('click', function(){
            if (that.vars.alumnos.length > 0) {
                var alumnos = that.vars.alumnos;
                $.ajax({
                    data: { alumnos: alumnos },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumnos/eliminarAlumnos',
                    success: function(data){
                        var response = JSON.parse(data);
                        if (response === 1) {
                            $('#general_snack').attr('data-content', 'Alumnos eliminados correctamente!');
                            $('#general_snack').snackbar('show');
                            $('.snackbar').addClass('snackbar-blue');
                        } else {
                            $('#general_snack').attr('data-content', 'Error desconocido: Intente de nuevo!');
                            $('#general_snack').snackbar('show');
                            $('.snackbar').addClass('snackbar-red');
                        }
                        $('#modalDeleteSelectedStudent').modal('hide');
                        var view = sessionStorage.getItem('st_alive');
                        that.displayStudents(view); 
                    }
                });
                that.vars.alumnos = [];
            }
        });
    },

    activeData: function() {
        $("#avatar").fileinput({
            showCaption: true,
            browseClass: "btn btn-info btn-sm btn-lg",
            fileType: "image"
        });
    },

    tables: function(group) {
        $('#tbl_students_'+group).DataTable();
        $('#tbl_primary').DataTable();
        $('#tbl_adols').DataTable();
        $('#tbl_adults').DataTable();
    },
    
};

Alumnos.initialize();
