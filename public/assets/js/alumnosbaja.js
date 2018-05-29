var Baja = {
    initialize: function(){
        console.log('Alumnos Baja Initialize');
        // this.getStudentsTable();
        this.tables();
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
        let table = $('#example').DataTable({
                        "stateSave": true,
                        "ajax": _root_ + 'alumnos/tablaAlumnosBaja',
                        "columns": [
                            { "data": "count" },
                            { "data": "avatar" },
                            { "data": "name" },
                            { "data": "age" },
                            { "data": "genre" },
                            { "data": "group" },
                            { "data": "tutor" }
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

        $('#example tbody').on( 'click', 'tr', function () {
            $(this).toggleClass('table-info');
        } );
     
        $('#button').click( function () {
            alert( table.rows('.table-info').data().length +' row(s) selected' );
        });
    },
    
};

Baja.initialize();
