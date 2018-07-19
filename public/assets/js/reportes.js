var Reportes = {
    opts: {
        dataTable: null
    },

    initialize: function(){
        console.log('Repostes Initialize');
        this.Studentstable();
    },

    test: function(){
        let _this = this;

        $.ajax({
            data: { curso: 'all'},
            synch: 'true',
            type: 'POST',
            url: _root_ + 'reportes/getStudentsTable',
            success: function(data){
                console.log(data);
            }
        });
    },

    Studentstable: function() {
        let _this = this;
        let header = "<'row justify-content-between'<'col-7 col-md-8'l><'col-2 col-md-3'f><'col-2 col-md-1'B>>\
                      <'row'<'col-sm-12 px-0'tr>>\
                      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";
        let table = $('#example').DataTable({
                        "dom": header,
                        "stateSave": true,
                        "lengthMenu": [[25, 50, 100], [25, 50, 100]],
                        "ajax": {
                            'url': _root_ + 'reportes/getStudentsTable',
                            "type": "POST",
                            'data': {
                                'curso': 'all'
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
                            { "data": "surname" },
                            { "data": "studies" },
                            { "data": "school" },
                            { "data": "age" },
                            { "data": "group_name" },
                            { "data": "tutor_name" }
                        ],
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

        _this.opts.dataTable = table;

        // table.on( 'order.dt search.dt', function () {
        //    table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        //       cell.innerHTML = i + 1;
        //       table.cell(cell).invalidate('dom');
        //    } );
        // } ).draw();

        table.on( 'draw.dt', function () {
            let PageInfo = $('#example').DataTable().page.info();
            table.column(0, { page: 'current' }).nodes().each( function (cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
                table.cell(cell).invalidate('dom');
            } );
        } );

        $('#example tbody').on( 'click', '.btnSuscribeStudent', function () {
            let student = $(this).data('student'),
                name    = $(this).data('name');

            $('#suscribe_student').val(student);
            $('#suscribe_name').text(name);
            $('#modalSuscribeStudent').modal('show');
                
        });

        $('#example tbody').on( 'click', '.btnDeleteStudent', function () {
            let student = $(this).data('student'),
                name    = $(this).data('name');

            $('#delete_student').val(student);
            $('#delete_name').text(name);
            $('#modalDeleteStudent').modal('show');
                
        });

        $('#example_wrapper button').removeClass('dt-button');

        // Busqueda Por Categoria
        $('.dataTables_filter input').unbind().bind('keyup', function() {
            let colIndex = parseInt($('#select').val());
            table.column( colIndex ).search( this.value ).draw();
        });

        $('#select').change(function() {
            table.columns().search('').draw();
        });

        table.columns().search('').draw();

        // $('#example tbody').on( 'click', 'tr', function () {
        //     $(this).toggleClass('table-info');
        // } );
     
        // $('#button').click( function () {
        //     alert( table.rows('.table-info').data().length +' row(s) selected' );
        // });
    },
    
};

Reportes.initialize();
