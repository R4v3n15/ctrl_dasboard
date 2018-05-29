var Maestros = {
    vars: {
        currentPage: 0
    },

    initialize: function(){
        console.log('Maestros Initialize');
        this.getTeachersTable();
    },

    getTeachersTable: function(page=0){
        let that = this;
        $.ajax({
            data: { page: page },
            synch: 'true',
            type: 'POST',
            url: _root_ + 'maestro/tablaMaestros',
            success: function(teachers){
                $('#tabla_maestros').html(teachers);
                that.navigationPage();
                that.newTeacher();
                that.editTeacher();
                that.deleteTeacher();
            }
        });
    },

    navigationPage: function(lista){
        let that = this;
        $(".page-teachers").on('click', function(event){
            event.preventDefault();
            that.vars.currentPage = parseInt($(this).data("teachers"));
            that.getTeachersTable($(this).data("teachers"));
        });
    },

    newTeacher: function(){
        let that = this;
        $('#newTeacher').on('click', function(){
            $('#modalAddTeacher').modal('show');

            $("#avatar").fileinput({
                showCaption: true,
                browseClass: "btn btn-info btn-md",
                fileType: "image"
            });
        });

        $("#avatar_file").fileinput({
                showCaption: true,
                browseClass: "btn btn-info btn-md",
                fileType: "image"
            });
    },

    editTeacher: function(){
        let that = this;
        $('.editTeacher').on('click', function(){
            var teacher = $(this).data('teacher');
            $.ajax({
                data: { maestro: teacher },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'maestro/maestro',
                success: function(data){
                    var res = JSON.parse(data);
                    $('#user_id').val(res.user_id);
                    $('#name').val(res.name);
                    $('#lastname').val(res.lastname);
                    $('#user_name').val(res.user_name);
                    $('#user_email').val(res.user_email);
                    $('#user_password').val(res.user_access_code);
                    
                    $('#modalEditTeacher').modal('show');
                }
            });
        });
    },

    deleteTeacher: function(){
        let that = this;
        $('.btn_delete_teacher').click(function(event) {
            event.preventDefault();
            let teacher      = $(this).data('teacher'),
                teacher_name = $(this).data('name');

            $('#delete_teacher_id').val(teacher);
            $('#teacher_name').text(teacher_name);
            $('#modalDeleteTeacher').modal('show');
        });

        $('#confirm_delete_teacher').click(function(event) {
            event.preventDefault();
            let teacher = $('#delete_teacher_id').val();

            $.ajax({
                data: { maestro: teacher },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'maestro/eliminarMaestro',
                success: function(deleted){
                    if (deleted == 'true') {
                        let page = that.vars.currentPage;
                        $('#general_snack').attr('data-content', 'Maestro Eliminado correctamente!');
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-blue');
                        that.getTeachersTable(page);
                    } else {
                        $('#general_snack').attr('data-content', 'Error al eliminar maestro, intente de nuevo!');
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red');
                    }
                    $('#modalDeleteTeacher').modal('hide');
                }
            });
        });
    }
};

Maestros.initialize();
