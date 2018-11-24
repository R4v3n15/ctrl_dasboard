var Eliminados = {
    vars:{
        dataTable: null
    },

    initialize: function(){
        console.log('Alumnos Eliminados Initialize');
        // this.getStudentsTable();
        this.getDeletedStudents();
        this.triggerActions();
    },


    getStudentsTable: function(){
        $.ajax({
            synch: 'false',
            type: 'POST',
            url: _root_ + 'alumnos/tablaAlumnosEliminados'
        })
        .then(function(response){
            console.log(response);
        });
    },


    getDeletedStudents: function() {
        let table = $('#example').DataTable({
                        "stateSave": true,
                        "ajax": _root_ + 'alumnos/tablaAlumnosEliminados',
                        "columns": [
                            { "data": "count" },
                            { "data": "avatar" },
                            { "data": "name" },
                            { "data": "age" },
                            { "data": "genre" },
                            { "data": "group" },
                            { "data": "tutor" },
                            { "data": "edit" }
                        ],
                        dom: 'Bfrtip',
                        buttons: [
                            'pdf'
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

        this.vars.dataTable = table;

        // $('#example tbody').on( 'click', 'tr', function () {
        //     $(this).toggleClass('table-info');
        // });

        $('#example tbody').on( 'click', '.reactive_student', function () {
            let idStudent =  $(this).data('student'),
                nameStudent = $(this).data('name'),
                groupStudent = $(this).data('group');

                // Validate group to know if student will return in a group o in Stand by list
                $.ajax({
                    synch: 'false',
                    type: 'POST',
                    data: { idClass : groupStudent },
                    url: _root_ + 'alumnos/validarClase'
                })
                .then(function(response){
                    console.log(response);
                    $('#returnStudentTo').text(response.listStudents);
                    $('#idClassToReturn').val(response.classId);
                })
                .fail(function(err){
                    console.log(err);
                });

                $('#idStudentToActivate').val(idStudent);
                $('#nameStudentToActivate').text(nameStudent);
                $('#modalReactiveStudent').modal('show');
                console.log(idStudent, nameStudent);
        });
     
        $('#button').click( function () {
            alert( table.rows('.table-info').data().length +' row(s) selected' );
        });
    },

    triggerActions: function(){
        let _this = this;

        $('#reactiveStudent').click(function(event){
            let idStudent = $('#idStudentToActivate').val()
                idClass   = $('#idClassToReturn').val();

            if (idStudent !== undefined && idStudent !== null && idStudent !== '') {
                $.ajax({
                    synch: 'false',
                    type: 'POST',
                    data: { idStudent : idStudent, idClass: idStudent },
                    url: _root_ + 'alumnos/restaurarAlumno'
                })
                .then(function(response){
                    if (response.success) {
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-blue');
                        _this.vars.dataTable.destroy();
                        _this.getDeletedStudents();
                        $('#modalReactiveStudent').modal('hide');
                    } else {
                        $('#general_snack').attr('data-content', response.message);
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red');
                    }
                })
                .fail(function(err){
                    console.log(err);
                    $('#general_snack').attr('data-content', "ERROR:500, Eror desconocido, reporte el problema (code:L122)");
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-red');
                });
            }
        });
    }
    
};

Eliminados.initialize();
