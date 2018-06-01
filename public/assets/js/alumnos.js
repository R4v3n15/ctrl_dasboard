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
        this.getGroups();
        this.changeGroupAllStudents()

        // this.getStudents();
        // this.defaultGetStudents();
        // this.activeData();
        
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

                _this.checkAllStudents();
                _this.changeGroupAllStudentsModal();
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
        $('.add_to_group').on('click', function(event){
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

    // Marcar/Desmarcar checkboxs de todos los alumnos de la tabla
    checkAllStudents: function(){
        let _this = this;
        $('#checkAll').change(function(){
            let status = $("#checkAll").is(":checked");
            $(".check-item").prop("checked", status);
            $(".check-item").toggleClass('selected-item');
        });

        $('.check-item').change(function(event){
            let item = $(this).val();
            if (_this.vars.alumnos.includes(item)) {
                let position = _this.vars.alumnos.indexOf(item);
                _this.vars.alumnos.splice(position, 1);
            } else {
                _this.vars.alumnos.push(item);
            }
            $(this).toggleClass('selected-item');
        });
    },


    changeGroupAllStudentsModal: function(){
        let _this = this;
        $('#changeAll').click(function(event){
            event.preventDefault();

            $.each($('.selected-item'), function(i, item) {
                _this.vars.alumnos[i] = $(this).val();
            });

            if (_this.vars.alumnos.length > 0) {
                $('#updateMessage').html('Cambiar de grupo a: ' + _this.vars.alumnos.length + ' Alumnos.');
                $('#updatecourse').val('');
                $('#updategroups').val('');
                $('#modalChangeGroup').modal('show');
            } else {
                $('#general_snack').attr('data-content', 'Seleccione al menos a un Alumno!');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-green');
            }
        });
    },

    changeGroupAllStudents: function(){
        let _this = this;
        $('#updateGroup').click(function(event) {
            event.preventDefault();
            let grupo = $('#updategroups').val();
            if(grupo !== ''){
                $.ajax({
                    data: { alumnos: _this.vars.alumnos, grupo: grupo },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumnos/cambiarGrupoAlumnos',
                    success: function(update){
                        if (update.success) {
                            $('#general_snack').attr('data-content', update.message);
                            $('#general_snack').snackbar('show');
                            $('.snackbar').addClass('snackbar-blue');
                        } else {
                            $('#general_snack').attr('data-content', update.message);
                            $('#general_snack').snackbar('show');
                            $('.snackbar').addClass('snackbar-red');
                        }
                        _this.vars.alumnos = [];
                        _this.getStudentsTable(_this.getActiveView(), _this.vars.currentPage);
                        $('#modalChangeGroup').modal('hide');
                    }
                });
            }else {
                $('#general_snack').attr('data-content', 'Seleccione un curso y un grupo por favor!');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-green');
            }
        });
    },

    getGroups: function() {
        $('#course').change(function(){
            let curso = parseInt($(this).val());
            if (curso !== '' && curso !== 0) {
                $("#groups").attr('disabled', false);
                $.ajax({
                    data: {
                        curso: curso
                    },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumnos/gruposPorNivel',
                    success: function(grupos){
                        let options = '<option value="" hidden>Seleccione un grupo...</option>';

                        if(grupos !== null) {
                            $.each(grupos, function(i, grupo){
                                options += '<option value="'+grupo.class_id+'">'+grupo.group_name+'</option>';
                            });
                        } else {
                            options = '<option value="">Curso sin grupos</option>';
                        }

                        $('#groups').html(options);
                    }
                });
            }

            if (curso === 0) {
                $("#groups").attr('disabled', true);
            }
        });

        $('#updatecourse').change(function(){
            let curso = parseInt($(this).val());
            if (curso !== '' && curso !== 0) {
                $("#updategroups").attr('disabled', false);
                $.ajax({
                    data: {
                        curso: curso
                    },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumnos/gruposPorNivel',
                    success: function(grupos){
                        let options = '<option value="" hidden>Seleccione un grupo...</option>';

                        if(grupos !== null) {
                            $.each(grupos, function(i, grupo){
                                options += '<option value="'+grupo.class_id+'">'+grupo.group_name+'</option>';
                            });
                        } else {
                            options = '<option value="">Curso sin grupos</option>';
                        }

                        $('#updategroups').html(options);
                    }
                });
            }

            if (curso === 0) {
                $("#updategroups").attr('disabled', true);
            }
        });
    },



    ////////////////////////////////////////////
    // =  =   =  =  = O L D  =  =  =  =  =  = //
    ////////////////////////////////////////////

    setStudentID: function(){
        let that = this;
        
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

        $('.add_to_group').on('click', function(){
            var student = $(this).data('student');
            $('#course').val('');
            $('#groups').val('');
            $('#alumno_id').val(student);
            $('#modalAddToGroup').modal('show');
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
