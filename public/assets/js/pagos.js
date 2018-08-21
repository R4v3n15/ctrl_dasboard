var Pagos = {

    vars  : {
        currentPage : 0,
    },

    initialize: function(){
        console.log('Pagos Initialize');
        this.defaultActiveView();
        this.activeView();
        this.changePayTable();
        this.savePayment();
        this.saveComment();
        this.reportsViews();


        // Test Functions
        // this.studentsTable();
    },


    studentsTable: function() {
        let _this = this;
        let columnas = null;

        if (_ciclo == 'A') {
            columnas = [
                            { "data": "count" },
                            { "data": "name" },
                            { "data": "surname" },
                            { "data": "info" },
                            { "data": "aug" },
                            { "data": "sep" },
                            { "data": "oct" },
                            { "data": "nov" },
                            { "data": "dec" },
                            { "data": "opt" }
                        ];
        } else {
            columnas = [
                            { "data": "count" },
                            { "data": "name" },
                            { "data": "surname" },
                            { "data": "info" },
                            { "data": "jan" },
                            { "data": "feb" },
                            { "data": "mar" },
                            { "data": "apr" },
                            { "data": "may" },
                            { "data": "jun" },
                            { "data": "jul" },
                            { "data": "opt" }
                        ];
        }

        let table = $('#tabla_pagos').DataTable({
                        "stateSave": true,
                        "lengthMenu": [[25, 50, 100], [25, 50, 100]],
                        "ajax": {
                            'url': _root_ + 'pagos/tablaPagos',
                            "type": "POST",
                            'data': {
                                'curso': 1,
                                'ciclo': _ciclo
                            }
                        },
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        } ],
                        "order": [[ 1, 'asc' ]],
                        "columns": columnas,
                        "buttons": [
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
                            "infoFiltered": "(filtrado de _MAX_ resultados)"
                        }
                    });


        this.countStudents();
        this.vars.dataTable = table;

        table.on( 'draw.dt', function () {
            let PageInfo = $('#tabla_pagos').DataTable().page.info();
            table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
                table.cell(cell).invalidate('dom');
            } );
        } );


        $('#tabla_pagos tbody').on( 'click', '.btnChangeAvatar', function () {
            let student = $(this).data('student');

            $('#avatar_student').val(student);
            $('#avatar_file').val('');
            $('#modalChangeAvatar').modal('show'); 
        });

        $('#tabla_pagos_wrapper button').removeClass('dt-button');

    },

    defaultActiveView: function() {
        if (sessionStorage.getItem('payTable') === null) {
            sessionStorage.setItem('payTable', 'tabla_1');
        }
    },

    activeView: function() {
        let _this = this;
        let activeView  = sessionStorage.getItem('payTable');
        let activeTable = $('#'+activeView).data('table');
        // console.log(activeView, activeTable);
        $('#'+activeView).addClass('active');

        _this.tablePay(activeTable, _this.vars.currentPage);
    },

    getActiveTable: function() {
        let _this = this;
        let activeView  = sessionStorage.getItem('payTable');
        let activeTable = $('#'+activeView).data('table');
        return activeTable;
    },

    changePayTable: function(){
        let _this = this;
        $('.pays_view').click(function(event){
            event.preventDefault();
            let _new = $(this).attr('id');
            $('.pays_view').removeClass('active');
            sessionStorage.setItem('payTable', _new);

            _this.activeView();
        });
    },

    tablePay: function(curso, page=0){
        let _this = this;
        $.ajax({
            data: { curso: curso, page: page },
            synch: 'true',
            type: 'POST',
            url: _root_ + 'pagos/getTablaPagos',
            success: function(data){
                $('#tabla_pagos').html(data);
                _this.openModals();
                _this.countStudents();
                _this.navigationPage();
            }
        });
    },

    navigationPage: function(){
        let _this = this;
        $(".page-students").on('click', function(event){
            event.preventDefault();
            _this.vars.currentPage = parseInt($(this).data("students"));
            _this.tablePay(_this.getActiveTable, _this.vars.currentPage);
        });
    },

    openModals: function() {
        let that = this;
        // Modal para marcar pagos
        $('.check_pay').on('click', function(event){
            event.preventDefault();
            let student_name = $(this).data('name'),
                student_id   = $(this).data('student'),
                month_title  = $(this).data('title'),
                month_id     = $(this).data('month'),
                status       = $(this).data('status');
            $('#student_name').text(student_name);
            $('#student_id').val(student_id);
            $('#month_to_pay').val(month_id);
            $('#month_name').text(month_title)
            $("#pay_action option[value='" + status +"']").attr("selected", true);
            $('#modalPayMonth').modal('show');
        });

        // Modal para agregar comentarios
        $('.add_comment').click(function(event){
            event.preventDefault();
            $('#id_alumno').val($(this).data('student'));
            $('#comment').val($(this).data('comment'));
            $('#modalAddComment').modal('show');
        });
    },

    savePayment: function() {
        let _this = this;
        // Enviar formulario de pago mensual a PHP
        $('#toggle_pay').on('click', function(event){
            event.preventDefault();
            let student    = $('#student_id').val(),
                month      = $('#month_to_pay').val(),
                pay_action = $('#pay_action').val(); // 0/null:Adeudo, 1:pagar, 2:becado, 3:no aplica

            console.log(student, month, pay_action);
            if (student !== '' && month !== '' && pay_action !== '') {
                $.ajax({
                    data: { student:  student, month: month, action: pay_action },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'pagos/pagarMes',
                    success: function(data){
                        if (data.success) {
                            let page = _this.vars.currentPage;
                            $('#modalPayMonth').modal('hide');
                            $('#general_snack').attr('data-content', 'Pago de Mensualidad Actualizado!');
                            $('#general_snack').snackbar('show');
                            $('.snackbar').addClass('snackbar-blue');
                            _this.tablePay(_this.getActiveTable(), page);
                        } else {
                            console.log(data);
                            $('#response').html("Error Desconocido: Por favor reporte este problema.");
                        }
                        $("#pay_action").val("");
                    }
                });
            } else {
                $('#response').html('Falta Información para terminar');
            }
        });
    },

    saveComment: function(){
        let _this = this;
        $('#save_comment').click(function(event){
            event.preventDefault();
            let student = $('#id_alumno').val(),
                comment = $('#comment').val();

            if (student !== '') {
                $.ajax({
                    data: {student:  student, comment: comment },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'pagos/guardarComentario',
                    success: function(data){
                        if (data.success) {
                            var list = sessionStorage.getItem('plist_alive');
                            $('#general_snack').attr('data-content', 'Comentario Actualizado!');
                            $('#general_snack').snackbar('show');
                            $('.snackbar').addClass('snackbar-blue');
                            _this.tablePay(_this.getActiveTable(), _this.vars.currentPage);
                        } else {
                            $('#general_snack').attr('data-content', 'Error: Intente de Nuevo o reporte el problema!');
                            $('#general_snack').snackbar('show');
                            $('.snackbar').addClass('snackbar-red');
                        }

                        $('#modalAddComment').modal('hide');
                        
                    }
                });
            }
        });
    },

    countStudents: function(){
        let _this = this;
        $.ajax({
            synch: 'true',
            type: 'POST',
            data: {curso: _this.getActiveTable},
            url: _root_ + 'pagos/numeroAlumnos',
            success: function(data){
                // console.log(data);
                $.each(data, function(i, value){
                    if (parseInt(value) > 0) {
                        $('#'+i).text(value);
                    } else {
                        $('#'+i).parent().addClass('d-none');
                    }
                });
            }
        });
    },

    reportsViews: function(){
        $('#lista-becarios').click(function(event) {
            event.preventDefault();
            alert('Mostrar lista de becarios')
        });

        $('#lista-adeudos').click(function(event) {
            event.preventDefault();
            alert('Mostrar lista de adeudos')
        });
    },









    defaultPaylist: function(){
        let that = this;
        var list = sessionStorage.getItem('plist_alive');
        that.showPaylist(list); 
    },

    getPaylist: function(){
        let that = this;
        $('.menu_pay_list li').on('click', function(){ 
            var list = $(this).data('group');
            that.showPaylist(list);
            
        }); 
    },

    showPaylist: function(lista, page=0){
        let that = this;
        $.ajax({
            data: { lista :  lista, page: page },
            synch: 'true',
            type: 'POST',
            url: _root_ + 'pagos/obtenerListaPagos',
            success: function(data){
                switch(parseInt(lista)) {
                    case 1: $('#pay_club').html(data);
                        break;
                    case 2: $('#pay_primary').html(data); 
                        break;
                    case 3: $('#pay_adolescent').html(data);
                        break;
                    case 4: $('#pay_adult').html(data);
                        break;
                    case 6: $('#pay_all').html(data);
                        break;
                }
                $.material.init();
                that.showModalPay();
                that.addComment();
                that.navigationPage(lista);
            }
        });
    },


    // TEST SECTION
    defaultValues: function(){
        let that = this;
        var ciclo = $('#ciclo').val();
        $.ajax({
            data: {ciclo:  ciclo },
            synch: 'false',
            type: 'POST',
            url: _root_ + 'pagos/mesesCiclo',
            success: function(data){
                $('#month').html(data);
            }
        }); 
    },

    sarchList: function(){
        let that = this;
        $('#ciclo').on('change', function(){ 
            let ciclo = $(this).val();
            $.ajax({
                data: {ciclo:  ciclo },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'pagos/mesesCiclo',
                success: function(data){
                    $('#month').html(data);
                }
            });
        });

        $('#search_list').on('click', function(){ 
            let list  = $('#list').val(),
                year  = $('#year').val(),
                ciclo = $('#ciclo').val(),
                month = $('#month').val();
            if (list !== '' && year !== '' && ciclo !== '' && month !== '') {
                $.ajax({
                    data: {grupo: list, anio: year, ciclo: ciclo, mes: month },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'pagos/listaAdeudos',
                    success: function(data){
                        $('#pay_list').html(data);
                    }
                });
            }
        });
    },

    showList: function(lista, mes){
        let that = this;
        $.ajax({
            data: { grupo:  lista, mes: mes },
            synch: 'true',
            type: 'POST',
            url: _root_ + 'pagos/obtenerPagosMes',
            success: function(data){
                switch(parseInt(lista)) {
                    case 1: $('#list_club').html(data);
                        break;
                    case 2: $('#list_primary').html(data); 
                        break;
                    case 3: $('#list_adolescent').html(data);
                        break;
                    case 4: $('#list_adult').html(data);
                        break;
                    case 6: $('#list_all').html(data);
                        break;
                }
                $.material.init();
                // that.showModalPay();
                that.tablesList(lista);
            }
        });
    },

    tablesList: function(list) {
        $('#tbl_list_'+list).DataTable();
    },
};

Pagos.initialize();
