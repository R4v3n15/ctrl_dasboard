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
        this.changeGroupAllStudents();

        this.unsuscribeStudent();
        this.unsuscribeStudents();
        this.deleteStudent();
        this.deleteStudents()

        this.invoiceTable();
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
                $('[data-toggle="tooltip"]').tooltip()
                feather.replace();
                _this.countStudents();
                _this.navigationPage(curso);
                _this.addStudentInGroup();
                _this.openModals();

                _this.checkAllStudents();
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


    invoiceTable: function(){
        $('.btnInvoiceList').click(function() {
            $('#invoice_table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'print'
                ],
                "stateSave": true,
                "lengthMenu": [[20, 50, -1], [20, 50, "Todo"]],
                "ajax": _root_ + 'alumnos/tablaFacturacion',
                "columns": [
                    { "data": "name" },
                    { "data": "cellphone" },
                    { "data": "tutor" },
                    { "data": "phone" },
                    { "data": "phone2" },
                    { "data": "phone3" }
                ],
                "language": {
                    "lengthMenu": "Ver _MENU_ filas por página",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontró resultados",
                    "info": "_PAGE_ de _PAGES_ páginas",
                    "infoEmpty": "No records available",
                    "infoFiltered": "(filtrado de _MAX_ resultados)"
                }
            });

            $('#modalInvoiceList').modal('show');
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

    openModals: function(){
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

        // Dar de baja a un alumno
        $('.btnUnsuscribeStudent').on('click', function(event){
            event.preventDefault();

            let student = $(this).data('student'),
                name    = $(this).data('name');

            $('#unsuscribe_student').val(student);

            $('#unsuscribe_name').text(name);

            $('#modalUnsuscribeStudent').modal('show');
        });

        // Dar de baja a varios alumno a la vez
        $('.btnUnsuscribeStudents').on('click', function(event){
            event.preventDefault();

            $.each($('.selected-item'), function(i, item) {
                _this.vars.alumnos[i] = $(this).val();
            });

            if (_this.vars.alumnos.length > 0) {
                $('#unsuscribe_message').html('Dar de baja a: ' + _this.vars.alumnos.length + ' Alumnos seleccionados.');
                $('#modalUnsuscribeStudents').modal('show');
            } else {
                $('#general_snack').attr('data-content', 'Seleccione al menos a un Alumno de la lista!');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-green');
            }
        });

        // Eliminar alumno
        $('.btnDeleteStudent').on('click', function(event){
            event.preventDefault();

            let student = $(this).data('student'),
                name    = $(this).data('name');

            $('#delete_student').val(student);

            $('#delete_name').text(name);

            $('#modalDeleteStudent').modal('show');
        });

        // Eliminar alumnos seleccionados
        $('.btnDeleteStudents').on('click', function(event){
            event.preventDefault();

            $.each($('.selected-item'), function(i, item) {
                _this.vars.alumnos[i] = $(this).val();
            });

            if (_this.vars.alumnos.length > 0) {
                $('#delete_students').html(_this.vars.alumnos.length);
                $('#modalDeleteStudents').modal('show');
            } else {
                $('#general_snack').attr('data-content', 'Seleccione al menos a un Alumno de la lista!');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-green');
            }
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
                url: _root_ + 'alumnos/cambiarGrupoAlumno'
            })
            .done(function(response){
                if (response.success) {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-blue');
                } else {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-red');
                }
                $('#modalAddToGroup').modal('hide');
                _this.getStudentsTable(_this.getActiveView(), _this.vars.currentPage);
            })
            .fail(function(errno){
                console.log(errno);
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

    unsuscribeStudent: function(){
        let _this = this;
        $('#unsuscribeStudent').on('click', function(event){
            event.preventDefault();

            let student = $('#unsuscribe_student').val();

            $.ajax({
                data: { student: student },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'alumnos/bajaAlumno',
                success: function(response){

                    if (response.success) {
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-blue')
                    } else {
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red')
                    }
                    _this.getStudentsTable(_this.getActiveView(), _this.vars.currentPage);
                    $('#modalUnsuscribeStudent').modal('hide');
                }
            });
        });
    },

    unsuscribeStudents: function(){
        let _this = this;
        $('#unsuscribeStudents').on('click', function(event){
            event.preventDefault();

            $.ajax({
                data: { students: _this.vars.alumnos },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'alumnos/bajaAlumnos',
                success: function(response){

                    if (response.success) {
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-blue')
                    } else {
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red')
                    }
                    _this.vars.alumnos = [];
                    _this.getStudentsTable(_this.getActiveView(), _this.vars.currentPage);
                    $('#modalUnsuscribeStudents').modal('hide');
                }
            });
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

    deleteStudent: function(){
        let _this = this;
        $('#deleteStudent').on('click', function(event){
            event.preventDefault();

            let student = $('#delete_student').val();

            if (student !== '' && student !== undefined) {
                $.ajax({
                    data: { student: student },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumnos/eliminarAlumno',
                    success: function(response){
                        if (response.success) {
                            $('#general_snack').attr('data-content', response.message);
                            $('#general_snack').snackbar('show');
                            $('.snackbar').addClass('snackbar-blue')
                        } else {
                            $('#general_snack').attr('data-content', response.message);
                            $('#general_snack').snackbar('show');
                            $('.snackbar').addClass('snackbar-red')
                        }
                        _this.getStudentsTable(_this.getActiveView(), _this.vars.currentPage);
                        $('#modalDeleteStudent').modal('hide');
                    }
                });
            }
        });
    },

    deleteStudents: function(){
        let _this = this;
        $('#deleteStudents').on('click', function(event){
            event.preventDefault();

            if (_this.vars.alumnos.length > 0) {
                $.ajax({
                    data: { students: _this.vars.alumnos },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumnos/eliminarAlumnos',
                    success: function(response){

                        if (response.success) {
                            $('#general_snack').attr('data-content', response.message);
                            $('#general_snack').snackbar('show');
                            $('.snackbar').addClass('snackbar-blue')
                        } else {
                            $('#general_snack').attr('data-content', response.message);
                            $('#general_snack').snackbar('show');
                            $('.snackbar').addClass('snackbar-red')
                        }
                        _this.vars.alumnos = [];
                        _this.getStudentsTable(_this.getActiveView(), _this.vars.currentPage);
                        $('#modalDeleteStudents').modal('hide');
                    }
                });
            }
        });
    },



    ////////////////////////////////////////////
    // =  =   =  =  = O L D  =  =  =  =  =  = //
    ////////////////////////////////////////////

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
