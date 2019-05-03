var Pagos = {

    vars  : {
        PAGE : 1,
        TABLE: null,
        FULLTABLE: null,
        GRUPO: 1
    },

    initialize: function(){
        console.log('Pagos Initialize');
        let _this = this;
        let path = window.location.pathname.split('/');
        if (path[1] === 'pagos') {
            if(path[2] === undefined || path[2] === 'index'){
                this.changePayTable();
                this.savePayment();
                this.saveComment();
                this.reportsViews();
                this.definePayTable();
            } else if(path[2] === 'pagos') {
                $('.pays_view').removeClass('active');
                $('#tabla_'+_this.vars.GRUPO).addClass('active');
                this.changeFullPayTable();
                this.setPayment();
                this.setComment();
                this.fullPayTable();
                this.changeCourseCostsModal();
                this.changeCourseCosts();
            }
        } 
    },

    fullPayTable: function(){
        let _this = this;
        let pay_table = $('#tabla_pagos_completo').DataTable({
                        "stateSave": true,
                        "lengthMenu": [[25, 50, 100], [25, 50, 100]],
                        "ajax": {
                            'url': _root_ + 'pagos/tablaPagosFull',
                            "type": "POST",
                            'data': {
                                'curso': _this.vars.GRUPO,
                                'ciclo': _ciclo
                            }
                        },
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        } ],
                        "order": [[ 1, 'asc' ]],
                        "columns": [
                            { "data": "count" },
                            { "data": "name" },
                            { "data": "status", "searchable" : false, "orderable": false },
                            { "data": "info" },
                            { "data": "jan", "searchable" : false, "orderable": false },
                            { "data": "feb", "searchable" : false, "orderable": false },
                            { "data": "mar", "searchable" : false, "orderable": false },
                            { "data": "apr", "searchable" : false, "orderable": false },
                            { "data": "may", "searchable" : false, "orderable": false },
                            { "data": "jun", "searchable" : false, "orderable": false },
                            { "data": "aug", "searchable" : false, "orderable": false },
                            { "data": "sep", "searchable" : false, "orderable": false },
                            { "data": "oct", "searchable" : false, "orderable": false },
                            { "data": "nov", "searchable" : false, "orderable": false },
                            { "data": "dec", "searchable" : false, "orderable": false },
                            { "data": "comment", "searchable" : false, "orderable": false  },
                            { "data": "opt", "searchable" : false, "orderable": false  }
                        ],
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
        this.vars.TABLE = pay_table;

        pay_table.on( 'draw.dt', function () {
            let PageInfo = $('#tabla_pagos_completo').DataTable().page.info();
            pay_table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
                pay_table.cell(cell).invalidate('dom');
            } );
        } );


        $('#tabla_pagos_completo tbody').on( 'click', '.payMonth', function () {
            let student = $(this).data('student'),
                month   = $(this).data('title'),
                code    = $(this).data('month'),
                status  = $(this).data('status');

            $('#month_name').text(month);
            $('#student_id').val(student);
            $('#month_to_pay').val(code);
            $("#pay_action option[value='" + status +"']").prop("selected", true);
            $('#modalPayMonth').modal('show'); 
        });

        $('#tabla_pagos_completo tbody').on( 'click', '.payAction', function () {
            let student   = $(this).data('student'),
                tutor     = $(this).data('tutor'),
                name      = $(this).data('name'),
                relatives = $(this).data('relatives'),
                comment   = $(this).data('comment');

            $.ajax({
                data: { alumno: student, tutor: tutor },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'pagos/infoPago'
            })
            .done(function(response){
                $('#nameStudent').text(name);
                let family = '';
                if (response.length > 0) {
                    response.forEach( function(element, index) {
                        family += '<div class="custom-control custom-checkbox">\
                                      <input type="checkbox" class="custom-control-input" name="familiares[]" id="check'+index+'" value="'+element.idStudent+'">\
                                      <label class="custom-control-label" for="check'+index+'">'+element.name+'(<small>'+element.group+'</small>)</label>\
                                    </div>'
                    });
                } else {
                    family = '<strong>Sin Familiares</strong>';
                }
                $('#relativesStudent').html(family);
                $('#payStudent').val(student);
                $('#payComment').val(comment);
                $('#monthToPay').html(_this.fullMonthList());
                $('#modalPayAction').modal('show');
            }); 
        });

        $('#tabla_pagos_completo tbody').on( 'click', '.addComment', function () {
            let student = $(this).data('student'),
                comment = $(this).data('comment');

            $('#id_alumno').val(student);
            $('#comment').val(comment);
            $('#modalAddComment').modal('show'); 
        });

        $('#tabla_pagos_completo tbody').on( 'click', '.addStatus', function () {
            let student = $(this).data('student'),
                comment = $(this).data('status');

            $('#status_idStudent').val(student);
            $('#status').val(comment);
            $('#modalAddStatus').modal('show'); 
        });

        $('#tabla_pagos_completo_wrapper button').removeClass('dt-button');
    },

    changeFullPayTable: function(){
        let _this = this;
        $('.pays_view').click(function(event){
            event.preventDefault();
            let grupo = $(this).data('table');
            $('.pays_view').removeClass('active');
            $('#tabla_'+grupo).addClass('active');
            _this.vars.GRUPO = grupo;
            _this.vars.PAGE  = 1;
            // sessionStorage.setItem('payTable', grupo);
            _this.vars.TABLE.destroy();
            _this.fullPayTable();
        });
    },

    setPayment: function() {
        let _this = this;
        // Enviar formulario de pago mensual a PHP
        $('#toggle_pay').on('click', function(event){
            event.preventDefault();
            let student    = $('#student_id').val(),
                month      = $('#month_to_pay').val(),
                pay_action = $('#pay_action').val(); // 0/null:Adeudo, 1:pagar, 2:becado, 3:no aplica

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
                            _this.vars.TABLE.destroy();
                            _this.fullPayTable();
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

        $('#payForm').submit(function(event) {
            event.preventDefault();

            $.ajax({
                data: $('#payForm').serialize(),
                synch: 'true',
                type: 'POST',
                url: _root_ + 'pagos/pagoMensualidad'
            })
            .done(function(response){
                if (response.success) {
                    let page = _this.vars.currentPage;
                    $('#modalPayAction').modal('hide'); 
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-blue');
                    _this.vars.TABLE.destroy();
                    _this.fullPayTable();
                } else {
                    $('#response').html(response.message);
                }
            });
        });
    },

    setComment: function(){
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
                            _this.vars.TABLE.destroy();
                            _this.fullPayTable();
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

        $('#frmSaveStatus').submit(function(event) {
            event.preventDefault();

            $.ajax({
                data: $('#frmSaveStatus').serialize(),
                synch: 'true',
                type: 'POST',
                url: _root_ + 'pagos/guardarEstado'
            })
            .done(function(response){
                if (response.success) {
                    $('#modalAddStatus').modal('hide'); 
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-blue');
                    _this.vars.TABLE.destroy();
                    _this.fullPayTable();
                } else {
                    $('#response').html(response.message);
                }
            });
        });
    },

    getRelatives: function(student, tutor){
        if (parseInt(tutor) === 0) {
            return [];
        } else {
            $.ajax({
                data: { alumno: student, tutor: tutor },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'pagos/infoPago'
            })
            .done(function(response){
                return response;
            });
        }
    },

    fullMonthList: function(){
        let months = '<option value="" hidden>Seleccione mes..</option>';
            months += '<option value="ene">Enero</option>';
            months += '<option value="feb">Febrero</option>';
            months += '<option value="mar">Marzo</option>';
            months += '<option value="abr">Abril</option>';
            months += '<option value="may">Mayo</option>';
            months += '<option value="jun">Junio</option>';
            months += '<option value="ago">Agosto</option>';
            months += '<option value="sep">Septiembre</option>';
            months += '<option value="oct">Octubre</option>';
            months += '<option value="nov">Noviembre</option>';
            months += '<option value="dic">Diciembre</option>';

        return months;
    },

    changeCourseCostsModal: function(){
        let _this = this;
        $('.change_costs').click(function(event){
            event.preventDefault();
            let course      = $(this).data('course'),
                normal_cost = $(this).data('normal'),
                promo_cost  = $(this).data('promo'),
                course_name = $(this).data('name');
            $('#course_name').text(course_name)
            $('#update_idCourse').val(course);
            $('#edit_normal_cost').val(normal_cost);
            $('#edit_promo_cost').val(promo_cost);
            $('#modalChangeCourseCost').modal('show');
        });
    },

    changeCourseCosts: function(){
        let _this = this;
        $('#frmUpdateCourseCosts').submit(function(event){
            event.preventDefault();
            $.ajax({
                data: $('#frmUpdateCourseCosts').serialize(),
                synch: 'true',
                type: 'POST',
                url: _root_ + 'pagos/actualizar_costos'
            })
            .done(function(response){
                if (response.success) {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-blue');
                    setTimeout(()=>{ location.reload(true); },800);
                    $('#modalChangeCourseCost').modal('hide');

                } else {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-red');
                }
            });
        });
    },












    definePayTable: function(){
        let _this = this;
        if (_ciclo == 'A') {
            _this.payTableA();
        } else {
            _this.payTableB();
        }

        // $.ajax({
        //     // data: { curso: 1, ciclo: 'A' },
        //     data: { curso: 1 },
        //     synch: 'true',
        //     type: 'POST',
        //     url: _root_ + 'pagos/tablaPagosFull'
        // })
        // .done(function(response){
        //     console.log('response from server');
        //     console.log(response);
        // });
    },

    payTableA: function() {
        let _this = this;

        let table = $('#tabla_pagos').DataTable({
                        "stateSave": true,
                        "lengthMenu": [[25, 50, 100], [25, 50, 100]],
                        "ajax": {
                            'url': _root_ + 'pagos/tablaPagos',
                            "type": "POST",
                            'data': {
                                'curso': _this.vars.GRUPO,
                                'ciclo': _ciclo
                            }
                        },
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        } ],
                        "order": [[ 1, 'asc' ]],
                        "columns": [
                            { "data": "count" },
                            { "data": "name" },
                            { "data": "info" },
                            { "data": "aug" },
                            { "data": "sep" },
                            { "data": "oct" },
                            { "data": "nov" },
                            { "data": "dec" },
                            { "data": "comment" },
                            { "data": "opt" }
                        ],
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
        this.vars.TABLE = table;

        table.on( 'draw.dt', function () {
            let PageInfo = $('#tabla_pagos').DataTable().page.info();
            table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
                table.cell(cell).invalidate('dom');
            } );
        } );


        $('#tabla_pagos tbody').on( 'click', '.payMonth', function () {
            let student = $(this).data('student'),
                month   = $(this).data('title'),
                code    = $(this).data('month'),
                status  = $(this).data('status');

            $('#month_name').text(month);
            $('#student_id').val(student);
            $('#month_to_pay').val(code);
            $("#pay_action option[value='" + status +"']").prop("selected", true);
            $('#modalPayMonth').modal('show'); 
        });

        $('#tabla_pagos tbody').on( 'click', '.payAction', function () {
            let student   = $(this).data('student'),
                name      = $(this).data('name'),
                relatives = $(this).data('relatives'),
                comment   = $(this).data('comment');

            $('#nameStudent').text(name);
            $('#relativesStudent').html(relatives);
            $('#payStudent').val(student);
            $('#payComment').val(comment);
            $('#monthToPay').html(_this.monthList());
            $('#modalPayAction').modal('show'); 
        });

        $('#tabla_pagos tbody').on( 'click', '.addComment', function () {
            let student = $(this).data('student'),
                comment = $(this).data('comment');

            $('#id_alumno').val(student);
            $('#comment').val(comment);
            $('#modalAddComment').modal('show'); 
        });

        $('#tabla_pagos_wrapper button').removeClass('dt-button');
    },

    payTableB: function() {
        let _this = this;

        let table = $('#tabla_pagos').DataTable({
                        "stateSave": true,
                        "lengthMenu": [[25, 50, 100], [25, 50, 100]],
                        "ajax": {
                            'url': _root_ + 'pagos/tablaPagos',
                            "type": "POST",
                            'data': {
                                'curso': _this.vars.GRUPO,
                                'ciclo': _ciclo
                            }
                        },
                        "columnDefs": [ {
                            "searchable": false,
                            "orderable": false,
                            "targets": 0
                        } ],
                        "order": [[ 1, 'asc' ]],
                        "columns": [
                            { "data": "count" },
                            { "data": "name" },
                            { "data": "info" },
                            { "data": "jan" },
                            { "data": "feb" },
                            { "data": "mar" },
                            { "data": "apr" },
                            { "data": "may" },
                            { "data": "jun" },
                            { "data": "jul" },
                            { "data": "comment" },
                            { "data": "opt" }
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
        this.vars.TABLE = table;

        table.on( 'draw.dt', function () {
            let PageInfo = $('#tabla_pagos').DataTable().page.info();
            table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
                table.cell(cell).invalidate('dom');
            } );
        } );


        $('#tabla_pagos tbody').on( 'click', '.payMonth', function () {
            let student = $(this).data('student'),
                month   = $(this).data('title'),
                code    = $(this).data('month'),
                status  = $(this).data('status');

            $('#month_name').text(month);
            $('#student_id').val(student);
            $('#month_to_pay').val(code);
            $("#pay_action option[value='" + status +"']").prop("selected", true);
            $('#modalPayMonth').modal('show'); 
        });

        $('#tabla_pagos tbody').on( 'click', '.payAction', function () {
            let student   = $(this).data('student'),
                name      = $(this).data('name'),
                relatives = $(this).data('relatives'),
                comment   = $(this).data('comment');

            $('#nameStudent').text(name);
            $('#relativesStudent').html(relatives);
            $('#payStudent').val(student);
            $('#payComment').val(comment);
            $('#monthToPay').html(_this.monthList());
            $('#modalPayAction').modal('show'); 
        });

        $('#tabla_pagos tbody').on( 'click', '.addComment', function () {
            let student = $(this).data('student'),
                comment = $(this).data('comment');

            $('#comment').val(comment);
            $('#id_alumno').val(student);
            $('#modalAddComment').modal('show'); 
        });

        $('#tabla_pagos_wrapper button').removeClass('dt-button');
    },

    monthList: function(){
        let months = '<option value="" hidden>Seleccione..</option>';
        if (_ciclo == 'B') {
            months += '<option value="ene">Enero</option>';
            months += '<option value="feb">Febrero</option>';
            months += '<option value="mar">Marzo</option>';
            months += '<option value="abr">Abril</option>';
            months += '<option value="may">Mayo</option>';
            months += '<option value="jun">Junio</option>';
            months += '<option value="jul">Julio</option>';
        } else {
            months += '<option value="ago">Agosto</option>';
            months += '<option value="sep">Septiembre</option>';
            months += '<option value="oct">Octubre</option>';
            months += '<option value="nov">Noviembre</option>';
            months += '<option value="dic">Diciembre</option>';
        }

        return months;
    },


    changePayTable: function(){
        let _this = this;
        $('.pays_view').click(function(event){
            event.preventDefault();
            let grupo = $(this).data('table');
            $('.pays_view').removeClass('active');
            $('#tabla_'+grupo).addClass('active');
            _this.vars.GRUPO = grupo;
            _this.vars.PAGE  = 1;
            // sessionStorage.setItem('payTable', grupo);
            _this.vars.TABLE.destroy();
            _this.definePayTable();
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
                            _this.vars.TABLE.destroy();
                            _this.definePayTable();
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

        $('#payForm').submit(function(event) {
            event.preventDefault();

            $.ajax({
                data: $('#payForm').serialize(),
                synch: 'true',
                type: 'POST',
                url: _root_ + 'pagos/pagarMensualidad'
            })
            .done(function(response){
                if (response.success) {
                    let page = _this.vars.currentPage;
                    $('#modalPayAction').modal('hide'); 
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-blue');
                    _this.vars.TABLE.destroy();
                    _this.definePayTable();
                } else {
                    $('#response').html(response.message);
                }
            });
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
                            _this.vars.TABLE.destroy();
                            _this.definePayTable();
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
