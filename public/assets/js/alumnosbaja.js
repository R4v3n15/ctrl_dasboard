var Baja = {
    opts: {
        dataTable: null
    },

    initialize: function(){
        console.log('Alumnos Baja Initialize');
        // this.getStudentsTable();
        this.tables();
        this.suscribeStudent();
        this.deleteStudent();
    },


    getStudentsTable: function(){
        $.ajax({
            synch: 'false',
            type: 'POST',
            url: _root_ + 'alumnos/tablaAlumnosBaja'
        })
        .then(function(response){
            console.log(response);
        });
    },


    tables: function() {
        let _this = this;
        let table = $('#example').DataTable({
                        "stateSave": true,
                        "lengthMenu": [[20, 50, 100], [20, 50, 100]],
                        "ajax": _root_ + 'alumnos/tablaAlumnosBaja',
                        "columns": [
                            { "data": "count" },
                            { "data": "avatar" },
                            { "data": "name" },
                            { "data": "age" },
                            { "data": "group" },
                            { "data": "tutor" },
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

    suscribeStudent: function(){
        let _this = this;
        $('#suscribeStudent').on('click', function(event){
            event.preventDefault();

            let student = $('#suscribe_student').val();

            if (student !== '' && student !== undefined) {
                $.ajax({
                    data: { student: student },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'alumnos/altaAlumno',
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
                        $('#modalSuscribeStudent').modal('hide');
                        _this.opts.dataTable.ajax.reload();
                    }
                });
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
                        $('#modalDeleteStudent').modal('hide');
                        _this.opts.dataTable.ajax.reload();
                    }
                });
            }
        });
    }
    
};

Baja.initialize();
