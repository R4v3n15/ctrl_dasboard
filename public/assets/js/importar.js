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
    },

    importGroups: function(){
        $('#import_groups').click(function(){
            $.ajax({
                synch: 'true',
                type: 'POST',
                url: _root_ + 'alumno/importarGrupos',
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
            url: _root_ + 'alumno/getClasesList',
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
                url: _root_ + 'alumno/importarMaestros',
                success: function(data){
                    console.log(data);
                    alert('Teachers Imported');
                }
            });
        });
    },

    getStuentsList: function(page=0){
        let that = this;
        $.ajax({
            data: {page:page},
            synch: 'true',
            type: 'POST',
            url: _root_ + 'alumno/getAlumnosList',
            success: function(data){
                $('#students_list').html(data);
                that.importStudent();
                that.navigationPage();
            }
        });
    },

    navigationPage: function(lista){
        let that = this;
        $(".page-students").on('click', function(event){
            event.preventDefault();
            that.vars.currentPage = parseInt($(this).data("students"));
            that.showPaylist($(this).data("students"));
        });
    },


    importStudent: function() {
        let that = this; 
        $('.btn_import').on('click', function(){
            let alumno = $(this).attr('id');
            $.ajax({
                data: { alumno: alumno },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'alumno/importarAlumno',
                success: function(data){
                    console.log(data);
                    that.getStuentsList();
                }
            });
        }); 
    },

    getRepeatedList: function(){
        let that = this;
    },

    navigationPage: function(){
        let that = this;
        $(".page-orders").on('click', function(event){
            event.preventDefault();
            that.getDesignCalendar($(this).data("orders"));
        });
    },
    
    updateRepeated: function(){
        let that = this; 
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
                    url: _root_ + 'alumno/corregirAlumnoRepetido',
                    success: function(data){
                        $('#'+id+'message').text('[ Actualizado: Ok ]');
                    }
                });
            }
        }); 
    }
    
};

Import.initialize();
