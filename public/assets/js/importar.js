var Import = {
    vars : {
        currentPage: 0
    },

    initialize: function(){
        console.log('Import Initialize');
        this.getStuentsList();
        this.updateRepeated();
        this.importTeachers();
        this.clasesList();
        this.importGroups();

        this.getRepeatedList();
    },

    importGroups: function(){
        $('#import_groups').click(function(){
            $.ajax({
                synch: 'true',
                type: 'POST',
                url: _root_ + 'importar/importarGrupos',
                success: function(data){
                    console.log(data);
                    if (data) {
                        alert('Teachers Imported');
                    }
                }
            });
        });
    },

    clasesList: function(){
        $.ajax({
            synch: 'true',
            type: 'POST',
            url: _root_ + 'importar/getClasesList',
            success: function(data){
                $('#lista_clases').html(data);
            }
        });
    },

    importTeachers: function() {
        $('#import_teachers').click(function(){
            $.ajax({
                synch: 'true',
                type: 'POST',
                url: _root_ + 'importar/importarMaestros',
                success: function(data){
                    console.log(data);
                    alert('Teachers Imported');
                }
            });
        });
    },

    getStuentsList: function(){
        let _this = this;
        $.ajax({
            data: { page:_this.vars.currentPage},
            synch: 'true',
            type: 'POST',
            url: _root_ + 'importar/importarAlumnos',
            success: function(response){
                $('#students_list').html(response);
                _this.importStudent();
                _this.navPage();
            }
        });
    },

    navPage: function(){
        let _this = this;
        $(".page-students").click(function(event){
            event.preventDefault();
            _this.vars.currentPage = parseInt($(this).data("students"));
            _this.getStuentsList();
        });
    },


    importStudent: function() {
        let that = this; 
        $('.btn_import').on('click', function(){
            let alumno = parseInt($(this).attr('id'));
            if (!isNaN(alumno)) {
                $.ajax({
                    data: { alumno: alumno },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'importar/importarAlumno',
                    success: function(response){
                        if (response.success) {
                            $('#general_snack').attr('data-content', response.message);
                            $('#general_snack').snackbar('show');
                            $('.snackbar').addClass('snackbar-blue');
                        } else {
                            $('#general_snack').attr('data-content', response.message);
                            $('#general_snack').snackbar('show');
                            $('.snackbar').addClass('snackbar-red');
                        }
                        that.getStuentsList();
                    }
                });
            }
        }); 
    },

    getRepeatedList: function(){
        let _this = this;
        $.ajax({
            synch: 'true',
            type: 'POST',
            url: _root_ + 'importar/listaRepetidos',
            success: function(response){
                $('#lista_repetidos').html(response);
                _this.updateRepeated();
                // _this.navPage();
            }
        });
    },

    navigationPage: function(){
        let that = this;
        $(".page-orders").on('click', function(event){
            event.preventDefault();
            that.getDesignCalendar($(this).data("orders"));
        });
    },
    
    updateRepeated: function(){
        let _this = this; 
        $('.btn_update').on('click', function(){
            let id = $(this).attr('id'),
                name = $('#'+id+'name').val(),
                surname = $('#'+id+'surname').val(),
                lastname = $('#'+id+'lastname').val();
            if (name !== '' && surname !== '' && lastname !== '') {
                $.ajax({
                    data: { student: id, name: name, surname: surname, lastname: lastname },
                    synch: 'true',
                    type: 'POST',
                    url: _root_ + 'importar/corregirAlumnoRepetido',
                    success: function(data){
                        $('#'+id+'message').text('[ Actualizado: Ok ]');
                    }
                });
            }
        });

        $('.btn_delete').on('click', function(){
            let id = $(this).attr('id'),
                sep = $(this).data('sep');

            $.ajax({
                data: { student: id, sep:sep },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'importar/eliminarRepetido'
            })
            .then(function(response){
                _this.getRepeatedList();
            });

        });

    }
    
};

Import.initialize();
