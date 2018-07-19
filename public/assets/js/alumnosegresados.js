var Egresados = {
    opts: {
        dataTable: null
    },

    initialize: function(){
        console.log('Alumnos Egresados Initialize');
        this.tables();
    },

    test: function(){
        let _this = this;

        $.ajax({
            data: { curso: 'all'},
            synch: 'true',
            type: 'POST',
            url: _root_ + 'alumnos/getAlumnos',
            success: function(data){
                console.log(data);
            }
        });
    },

    tables: function() {
        let _this = this;
        let table = $('#example').DataTable({
                        "stateSave": true,
                        "lengthMenu": [[25, 50, 100], [25, 50, 100]],
                        "ajax": {
                            'url': _root_ + 'alumnos/getAlumnos',
                            "type": "POST",
                            'data': {
                                'curso': 'all'
                            }
                        },
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
                        "language": {
                            "lengthMenu": "Ver _MENU_ filas por página",
                            "search": "Buscar:",
                            "zeroRecords": "No se encontró resultados",
                            "info": "_PAGE_ de _PAGES_ páginas",
                            "infoEmpty": "No records available",
                            "infoFiltered": "(filtrado de _MAX_ resultados)"
                        }
                    });

        _this.opts.dataTable = table;

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

        // $('#example tbody').on( 'click', 'tr', function () {
        //     $(this).toggleClass('table-info');
        // } );
     
        // $('#button').click( function () {
        //     alert( table.rows('.table-info').data().length +' row(s) selected' );
        // });
    },
    
};

Egresados.initialize();
