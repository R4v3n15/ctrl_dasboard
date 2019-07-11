var Cursos = {
    vars: {
        currentPage: 0
    },

	initialize: function(){
		console.log('Cursos Initialize');
        this.newCourse();
        this.newGroup();
        this.showClases();
        this.getClasses();
        this.getCourses();
        this.getGroups();
        this.getFrmNewClass();
        this.confirmDeleteClass();
		this.selectAttr();
		this.initAttributes();
	},

    showClases: function(){
        let that = this;
        $('#show_clases').click(function(event){
            event.preventDefault();
            that.getClasses();
        });
    },

    getClasses: function (page=0) {
        let that = this;
        $.ajax({
            data: { page: page },
            synch: 'true',
            type: 'POST',
            url: _root_ + 'curso/obtenerClases',
            success: function(data){
                $('#table_result').html(data);
                that.addTeacher();
                that.navigationPage();
                $('#addClase').show();
                that.deleteClase();
                that.getFrmEditClass();
            }
        });
    },

    addTeacher: function(){
        let that = this;
        $('.add-teacher').click(function(event){
            event.preventDefault();
            $('#clase_id').val($(this).data('id'));
            $('#maestro').val('');
            $("#chage_teacher_title").text('Agregar Maestro');
            $('#addTeacher').modal('show');
        });

        $('.change-teacher').click(function(event){
            event.preventDefault();
            let clase = $(this).data('id'),
                maestro = $(this).data('teacher');

            $('#clase_id').val(clase);
            $("#maestro option[value='" + maestro +"']").attr("selected", true);
            $("#chage_teacher_title").text('Cambiar Maestro');
            $('#addTeacher').modal('show');
        });

        // Agregar o Cambiar maestro de la clase
        $('#add_teacher').click(function(event){
            event.preventDefault();
            $.ajax({
                data: { clase: $('#clase_id').val(), maestro:  $('#maestro').val()},
                synch: 'true',
                type: 'POST',
                url: _root_ + 'curso/agregarMaestro',
                success: function(saved){
                    console.log(saved);
                    if (saved == 'true') {
                        let page = that.vars.currentPage;
                        $('#addTeacher').modal('hide');
                        $('#general_snack').attr('data-content', 'Maestro asignado correctamente!');
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-blue');
                        that.getClasses(page);
                    } else {
                        $('#general_snack').attr('data-content', 'Error al asignar maestro, intente de nuevo!');
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red');
                    }
                }
            });
            $('#addTeacher').modal('show');
        });
    },

    navigationPage: function(lista){
        let that = this;
        $(".page-clases").on('click', function(event){
            event.preventDefault();
            that.vars.currentPage = parseInt($(this).data("clases"));
            that.getClasses($(this).data("clases"));
        });
    },

    getFrmNewClass: function() {
        let _this = this;
        $('#addClase').on('click', function(event){
            event.preventDefault();
            $('#modalAddClasse').modal('show');
            $('#dias').select2({
                placeholder: 'Seleccione..',
                allowClear: true
            });
        });

        $('#addGroup').on('click', function(event){
            event.preventDefault();
            $('#modal_addGroup').modal('show');
        });


        $('#frmCreateClase').submit(function(event){
            event.preventDefault();

            $.ajax({
                data: $('#frmCreateClase').serialize(),
                synch: 'true',
                type: 'POST',
                url: _root_ + 'curso/nuevaClase'
            })
            .done(function(response){
                let callout = $('#alert'), classesAlpha = ["bg-success", "bg-danger", "bg-warning", "bg-info"];
                $.each(classesAlpha, function(i, v){
                   callout.removeClass(v);
                });

                if (response.success) { 
                    $('#frmCreateClase .form-control').val('');
                    callout.addClass('bg-success'); 
                }
                else { callout.addClass('bg-danger'); }

                $('#alert-content').html(response.message);

                _this.getClasses();

                callout.removeClass('d-none');
                $('#modalAddClasse').modal('hide');
            });
        });
    },

    getFrmEditClass: function() {
        let _this = this;
        $('.updateClase').on('click', function(){
            $.ajax({
                data: { clase: $(this).attr('id'),
                        horario: $(this).data('horario') },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'curso/formEditarClase',
                success: function(data){
                    $('#frmEditClase').html(data);
                    $('#modalEditClasse').modal('show');
                    $('#days').select2();
                    _this.setAttr();

                    // $('#cancel_edit').on('click', function(){
                    //     _this.getClasses();
                    // });
                }
            });
        });

        $('.restartClase').on('click', function(){
            $.ajax({
                data: { clase: $(this).attr('id'),
                        horario: $(this).data('horario') },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'curso/formReiniciarClase',
                success: function(data){
                    $('#frmRestartClase').html(data);
                    $('#modalRestartClasse').modal('show');
                    $('#dias_list').select2();
                    _this.setAttr();

                    $('#cancel_restart').on('click', function(){
                        $('#modalRestartClasse').modal('hide');
                    });
                }
            });
        });
    },

    getCourses: function () {
        let that = this;
        $('#show_courses').click(function(event){
            event.preventDefault();
            $.ajax({
                synch: 'true',
                type: 'POST',
                url: _root_ + 'curso/obtenerCursos',
                success: function(data){
                    $('#table_result').html(data);
                    that.updateCourse();
                }
            });
        });
    },

    newCourse: function(){
        let that = this;
        $('#new_course').click(function() {
            var curso = $('#new_course_name').val();
            $.ajax({
                data: { curso: curso },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'curso/nuevoCurso',
                success: function(data){
                    $('#addCourse').modal('hide');
                    if (data === '1') {
                        $('#general_snack').attr('data-content', 'Nuevo curso agregado!');
                    } else {
                        $('#general_snack').attr('data-content', 'No se agrego el curso, intente de nuevo!');
                    }
                    that.getCourses();
                    
                }
            });
        });
    },

    updateCourse: function(){
        let that = this;
        $('.btn_edit_course').click(function(){
            var curso = $(this).data('id'),
                name  = $(this).data('course');
            $('#course_name').val(name);
            $('#course_id').val(curso);
        });

        $('#btn_update_course').click(function(){
            var id    = $('#course_id').val(),
                curso = $('#course_name').val();

            $.ajax({
                data: { id: id, curso: curso },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'curso/editarCurso',
                success: function(data){
                    $('#editCourse').modal('hide');
                    if (data === '1') {
                        $('#general_snack').attr('data-content', 'Curso actualizado con éxito!');
                    } else {
                        $('#general_snack').attr('data-content', 'No se actualizó el curso!');
                    }
                    $('#general_snack').snackbar('show');
                    that.getCourses();
                }
            });
        });
    },

    deleteClase: function() {
        $('.deleteClase').on('click', function(event){
            event.preventDefault();
            let clase_id = $(this).attr('id'),
                clase_name = $(this).data('name');
            $.ajax({
                data: {clase : clase_id },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'curso/obtenerAlumnosClase',
                success: function(response){
                    if (response > 0) {
                        $('#warn_msg').html('<b>NOTA:</b> Esta clase tiene '+ response +' alumnos inscritos, todos pasaran a lista de espera si elimina la clase. ¿Desea continuar?');
                    }
                }
            });
            $('#clase_name').text(clase_name);
            $('#delete_clase_id').val(clase_id);
            $('#modalDeleteClass').modal('show');
        });

        $('.removeClase').on('click', function(event){
            event.preventDefault();
            let clase_id = $(this).data('clase'),
                clase_name = $(this).data('name');

            $('#remove_clase_name').text(clase_name);
            $('#remove_clase_id').val(clase_id);
            $('#modalRemoveClass').modal('show');
        });
    },

    confirmDeleteClass: function() {
        let that = this;

        $('#btn_delete_class').click(function(){
            let clase = $('#delete_clase_id').val();
            $.ajax({
                data: {clase : clase },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'curso/eliminarClase',
                success: function(response){
                    if (response) {
                        $('#general_snack').attr('data-content', 'Clase eliminado con éxito!');
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-blue');
                    } else {
                        $('#general_snack').attr('data-content', 'No se elimino clase!');
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red');
                    }
                    that.getClasses();
                    $('#modalDeleteClass').modal('hide');
                }
            });
        });

        $('#btn_remove_class').click(function(event){
            event.preventDefault();
            let clase = $('#remove_clase_id').val();
            $.ajax({
                data: {clase : clase },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'curso/moverClase',
                success: function(response){
                    console.log(response)
                    if (response) {
                        $('#general_snack').attr('data-content', 'Clase se movio a lista de terminados correctamente!');
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-blue');
                    } else {
                        $('#general_snack').attr('data-content', 'No se pudo mover clase, intente de nuevo!');
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-red');
                    }
                    $('#modalRemoveClass').modal('hide');
                    that.getClasses();
                }
            });
        });
    },

    newGroup: function(){
        let that = this;
        $('#new_group').click(function() {
            var grupo = $('#new_group_name').val();
            $.ajax({
                data: { grupo: grupo },
                synch: 'true',
                type: 'POST',
                datatype: 'JSON',
                url: _root_ + 'curso/nuevoGrupo',
                success: function(data){
                    if (data.success) {
                        $('#modal_addGroup').modal('hide');
                        $('#general_snack').attr('data-content', 'Nuevo grupo agregado!');
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-green');
                    } else {
                        $('#general_snack').attr('data-content', 'No se agrego el grupo, intente de nuevo!');
                        $('#general_snack').snackbar('show');
                        $('.snackbar').addClass('snackbar-green');
                    }
                    that.getGroups();
                }
            });
        });
    },

    getGroups: function () {
        let that = this;
        $('#show_groups').click(function(event){
            event.preventDefault();
            $.ajax({
                synch: 'true',
                type: 'POST',
                url: _root_ + 'curso/obtenerGrupos',
                success: function(data){
                    $('#table_result').html(data);
                    that.deleteGroup();
                    that.updateGroup();
                }
            });
        });
    },

    updateGroup: function(){
        let that = this;
        $('.btn_edit_group').click(function(){
            var group = $(this).data('id');
            var name  = $(this).data('group');
            $('#group_name').val(name);
            $('#group_id').val(group);
            $('#editGroup').modal('show');
        });

        $('#update_group').click(function(){
            var grupo = $('#group_name').val(),
                id    = $('#group_id').val();
            $.ajax({
                data: { id: id, grupo: grupo },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'curso/editarGrupo',
                success: function(data){
                    $('#editGroup').modal('hide');
                    if (data === '1') {
                        $('#general_snack').attr('data-content', 'Grupo actualizado con éxito!');
                    } else {
                        $('#general_snack').attr('data-content', 'No se actualizó el grupo!');
                    }
                    $('#general_snack').snackbar('show');
                    that.getGroups();
                }
            });
        });
    },

    deleteGroup: function() {
        let that = this;
        $('.btn_remove_group').click(function(){
            var group = $(this).data('id');
            var name  = $(this).data('group');
            console.log(name);
            $('#delete_group_id').val(group);
            $('#g_name').text(name);
            $('#deleteGroup').modal('show');
        });

        $('#delete_group').click(function(){
            var grupo = $('#delete_group_id').val();
            $.ajax({
                data: {grupo : grupo },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'curso/eliminarGrupo',
                success: function(data){
                    $('#deleteGroup').modal('hide');
                    if (data === '1') {
                        $('#general_snack').attr('data-content', 'Grupo eliminado con éxito!');
                    } else {
                        $('#general_snack').attr('data-content', 'No se elimino el grupo!');
                    }
                    $('#general_snack').snackbar('show');
                    that.getGroups();
                }
            });
        });
    },

	selectAttr: function() {
        $('#example_length').css('width', '280');
    },

    initAttributes: function(){
        if ($('#date_init').length) {
            pikadayResponsive(document.getElementById("date_init"),{
                classes : "form-control form-control-sm",
                placeholder: "Fecha de Inicio",
                properties: 'required'
            });

            $('#date_init-input').prop('autocomplete', 'off');
        }

        if ($('#date_end').length) {
            pikadayResponsive(document.getElementById("date_end"),{
                classes : "form-control form-control-sm",
                placeholder: "Fecha de Termino",
                properties: 'required'
            });

            $('#date_end-input').prop('autocomplete', 'off');
        }

        $("#timepick").timepicker({
            step:30,
            minTime:'9:00am',
            maxTime:'8:00pm'
        });
        $("#timepick2").timepicker({
            step:30,
            minTime:'9:00am',
            maxTime:'8:00pm'
        });
    },

    setAttr: function() {
        let that = this;

        if ($('#editdate_init').length) {
            pikadayResponsive(document.getElementById("editdate_init"),{
                classes : "form-control form-control-sm",
                placeholder: "Fecha de Inicio",
                properties: 'required'
            });
            $('#editdate_init-input').prop('autocomplete', 'off');
        }

        if ($('#editdate_end').length) {
            pikadayResponsive(document.getElementById("editdate_end"),{
                classes : "form-control form-control-sm",
                placeholder: "Fecha de Terminp",
                properties: 'required'
            });
            $('#editdate_end-input').prop('autocomplete', 'off');
        }

        if ($('#restartdate_init').length) {
            pikadayResponsive(document.getElementById("restartdate_init"),{
                classes : "form-control form-control-sm",
                placeholder: "Fecha de Inicio",
                properties: 'required'
            });
            $('#restartdate_init-input').prop('autocomplete', 'off');
        }

        if ($('#restartdate_end').length) {
            pikadayResponsive(document.getElementById("restartdate_end"),{
                classes : "form-control form-control-sm",
                placeholder: "Fecha de Terminp",
                properties: 'required'
            });
            $('#restartdate_end-input').prop('autocomplete', 'off');
        }

        $("#timepick3").timepicker({
            step:30,
            minTime:'9:00am',
            maxTime:'8:00pm'
        });
        $("#timepick4").timepicker({
            step:30,
            minTime:'9:00am',
            maxTime:'8:00pm'
        });
    },

};

Cursos.initialize();
