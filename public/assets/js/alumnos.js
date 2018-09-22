var Alumnos = {
    vars : {
        alumnos: [],
        currentPage: 0,
        tableView: 1,
        dataTable: null,
        tableStatus: false
    },

    initialize: function(){
        console.log('Alumnos Initialize');
        this.setActiveView();
        this.changeViewStudent();
        this.changeAvatarStudent();
        this.addToGroup();
        this.getGroups();
        this.changeGroupAllStudents();
        this.openModals();

        this.unsuscribeStudent();
        this.unsuscribeStudents();
        this.deleteStudent();
        this.deleteStudents();

        this.checkAllStudents();

        this.invoiceTable();
    },

    setActiveView: function(){
        // Iniciado desde main.js
        let active_view = sessionStorage.getItem('vista_alumnos');
        this.studentsTable();

        if (sessionStorage.getItem('collapse_menu') !== null) {
            $('#students_menu').prop('aria-expanded', 'true');
            $('#studentsCollapse').addClass('show');
        } else {
            $('#students_menu').prop('aria-expanded', 'false');
            $('#studentsCollapse').removeClass('show');
        }

        $('#students_menu').click(function(event) {
            if (sessionStorage.getItem('collapse_menu') === null) {
                sessionStorage.setItem('collapse_menu', 'collapse');
            } else {
                sessionStorage.removeItem('collapse_menu');
                console.log(sessionStorage.getItem('collapse_menu'));
            }
        });
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

            _this.vars.tableView = $('#'+_new).data('curso');
            $('#'+_new).addClass('active');

            _this.vars.dataTable.destroy();
            _this.studentsTable();
        });
    },

    studentsTable: function() {
        let _this = this;

        let table = $('#table_students').DataTable({
                        "stateSave": true,
                        "lengthMenu": [[25, 50, 100], [25, 50, 100]],
                        "ajax": {
                            'url': _root_ + 'alumnos/getAlumnos',
                            "type": "POST",
                            'data': {
                                'curso': _this.getActiveView()
                            }
                        },
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": true,
                            "targets": 0
                        } ],
                        "columns": [
                            { "data": "count" },
                            { "data": "avatar" },
                            { "data": "surname" },
                            { "data": "name" },
                            { "data": "studies" },
                            { "data": "age" },
                            { "data": "group_name" },
                            { "data": "tutor_name" },
                            { "data": "options" }
                        ],
                        "rowCallback": function( row, data, index ) {
                            if(data.finished) {
                                $(row).addClass('bg-tr-danger')
                            }
                        }, 
                        buttons: [
                            { "extend": 'print',
                              "text":'Imprimir <i class="fa fa-print"></i>',
                              "className": 'btn btn-info btn-sm' }
                        ],
                        "language": {
                            "lengthMenu": "Ver _MENU_ filas",
                            "search": "Buscar:",
                            "zeroRecords": "No se encontró resultados",
                            "info": "_PAGE_ de _PAGES_ páginas",
                            "infoEmpty": "No records available",
                            "infoFiltered": "(filtrado de _MAX_ resultados)",
                            "print": "Imprimir"
                        }
                    });

        this.countStudents();
        this.vars.dataTable = table;

        // table.on( 'draw.dt', function () {
        //     let PageInfo = $('#table_students').DataTable().page.info();
        //     table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
        //         cell.innerHTML = i + 1 + PageInfo.start;
        //         table.cell(cell).invalidate('dom');
        //     } );
        // } );

        $('#table_students tbody').on('click', '.btnSetGroup', function(event){
            event.preventDefault();

            let student = $(this).data('student');
            $('#course').val('');
            $('#groups').val('');
            $('#alumno_id').val(student);
            $('#modalAddTitle').text('ASIGNAR A UN GRUPO');
            $('#extra_message').text('');

            $('#modalAddToGroup').modal('show');
        });

        $('#table_students tbody').on('change', '.check-item',function(event){
            let item = $(this).val(),
                reinscribir = $(this).data('inscribir') == '1' ? 1 : 0;

                alumno = item + ',' + reinscribir;
            if (_this.vars.alumnos.includes(alumno)) {
                let position = _this.vars.alumnos.indexOf(alumno);
                _this.vars.alumnos.splice(position, 1);
            } else {
                _this.vars.alumnos.push(alumno);
            }
            $(this).toggleClass('selected-item');
        });

        $('#table_students tbody').on('click', '.btnChangeGroup', function(event){
            event.preventDefault();

            let student = $(this).data('student'),
                grupo   = $(this).data('group'),
                reinscripcion = $(this).data('reinscripcion');

            $('#course').val('');
            $('#groups').val('');
            $('#reinscripcion').val(reinscripcion ? 1 : 0);
            $('#alumno_id').val(student);

            $('#modalAddTitle').text(!reinscripcion ? 'CAMBIAR DE GRUPO' : 'REINSCRIBIR ALUMNO');
            $('#extra_message').text('Grupo Actual: '+ grupo);

            $('#modalAddToGroup').modal('show');
        });

        $('#table_students tbody').on( 'click', '.btnSuscribeStudent', function () {
            let student = $(this).data('student'),
                name    = $(this).data('name');

            $('#suscribe_student').val(student);
            $('#suscribe_name').text(name);
            $('#modalSuscribeStudent').modal('show');
                
        });

        $('#table_students tbody').on( 'click', '.btnDeleteStudent', function () {
            let student = $(this).data('student'),
                name    = $(this).data('name');

            $('#delete_student').val(student);
            $('#delete_name').text(name);
            $('#modalDeleteStudent').modal('show');
                
        });

        $('#table_students tbody').on( 'click', '.btnChangeAvatar', function () {
            let student = $(this).data('student');

            $('#avatar_student').val(student);
            $('#avatar_file').val('');
            $('#modalChangeAvatar').modal('show'); 
        });

        $('#table_students_wrapper button').removeClass('dt-button');

        _this.vars.tableStatus = true;

        // Busqueda Por Categoria
        // $('.dataTables_filter input').unbind().bind('keyup', function() {
        //     let colIndex = parseInt($('#select').val());
        //     table.column( colIndex ).search( this.value ).draw();
        // });

        // $('#select').change(function() {
        //     table.columns().search('').draw();
        // });

        // table.columns().search('').draw();
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
                _this.activeFilter();
                _this.startSearch();
            }
        });
    },

    countStudents: function(){
        $.ajax({
            synch: 'true',
            type: 'POST',
            url: _root_ + 'alumnos/alumnosCurso',
            success: function(data){
                $.each(data, function(i, value){
                    if (parseInt(value) > 0) {
                        $('#'+i).text(value);
                        $('#'+i).parent().removeClass('d-none');
                    } else {
                        $('#'+i).parent().addClass('d-none');
                    }
                });
            }
        });
    },

    activeFilter: function(){
        $('#filter').change(function(event) {
            if ($(this).val() !== '') {
                switch ($(this).val()) {
                    case 'escuela':
                    case 'grupo':
                        $('.extra-field').addClass('d-none');
                        $('#field').prop('placeholder', 'Nombre del ' + $(this).val());
                        break;
                    case 'grado':
                        $('.extra-field').addClass('d-none');
                        $('#field').prop('placeholder', 'Grado escolar');
                        break;
                    case 'edad':
                        $('.extra-field').addClass('d-none');
                        $('#field').prop('placeholder', 'Edad del alumno');
                        break;
                    default:
                        $('.extra-field').removeClass('d-none');
                        $('#field').prop('placeholder', 'Nombre del ' + $(this).val());
                        break;
                }
            }
        });
    },

    startSearch: function(){
        let _this = this;
        let filtering = null;

        $('#param').keyup(function() {
            clearTimeout(filtering)
            if ($('#param').val() !== '') {
                filtering = setTimeout(function(){
                    _this.searchInDB($('#filter').val(), 
                                     $('#param').val(), 
                                     $('#param1').val(), 
                                     $('#param2').val());
                }, 500)
            }
        });
    },

    searchInDB: function(filtro, param, param1, param2){
        $.ajax({
            data: { filtro: filtro, 
                    param: param,
                    param1: param1,
                    param2: param2 },
            synch: 'true',
            type: 'POST',
            url: _root_ + 'alumnos/realizarBusqueda'
        })
        .done(function(response){
            console.log(response)
        })
        .fail(function(errno){
            console.log(errno);
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

    openModals: function(){
        let _this = this;

        $('#changeAll').click(function(event){
            event.preventDefault();

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

    // Asignar/Cambiar alumno de grupo
    addToGroup: function() {
        let _this = this;
        $('#addToGroup').on('click', function(event){
            event.preventDefault();

            var alumno = $('#alumno_id').val(),
                curso  = $('#course').val(),
                clase  = $('#groups').val(),
                reinscribir = $('#reinscripcion').val();
                // console.log(alumno, clase);
            $.ajax({
                data: { alumno: alumno, clase: clase, reinscribir: reinscribir },
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
                _this.vars.dataTable.destroy();
                _this.studentsTable();
            })
            .fail(function(errno){
                console.log(errno);
            });
        });
    },

    // Marcar/Desmarcar todos los checkboxs de tabla alumnos
    checkAllStudents: function(){
        let _this = this;
        $('#checkAll').change(function(){
            let status = $("#checkAll").is(":checked");
            $(".check-item").prop("checked", status);
            $(".check-item").toggleClass('selected-item');

            $.each($(".selected-item"), function(i, stds){
                let id = $(stds).val(),
                    reinscribir = $(stds).data('inscribir') == '1' ? 1 : 0;

                alumno = id + ',' + reinscribir;
                if (_this.vars.alumnos.includes(alumno)) {
                    let position = _this.vars.alumnos.indexOf(alumno);
                    _this.vars.alumnos.splice(position, 1);
                } else {
                    _this.vars.alumnos.push(alumno);
                }
            });
        });
    },


    changeGroupAllStudents: function(){
        let _this = this;
        $('#updateGroup').click(function(event) {
            event.preventDefault();
            let grupo = $('#updategroups').val();
            if(grupo !== ''){
                $.ajax({
                    data: { alumnos: _this.vars.alumnos, clase: grupo },
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
                        _this.vars.dataTable.destroy();
                        _this.studentsTable();
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
                    url: _root_ + 'alumnos/gruposPorNivel'
                })
                .done(function(grupos){
                    let options = '<option value="" hidden>Seleccione un grupo...</option>';

                    if(grupos !== null) {
                        $.each(grupos, function(i, grupo){
                            options += '<option value="'+grupo.class_id+'">'+grupo.group_name+'</option>';
                        });
                    } else {
                        options = '<option value="">Curso sin grupos</option>';
                    }

                    $('#groups').html(options);
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

    changeAvatarStudent: function(){
        let _this = this;
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
                    _this.vars.dataTable.destroy();
                    _this.studentsTable();
                } else {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-red')
                }
                $('#modalChangeAvatar').modal('hide');
            });
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
