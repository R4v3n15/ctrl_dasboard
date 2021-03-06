var Becados = {
    initialize: function(){
        console.log('Becados Initialize');
        this.handleModals();
        this.handleScholarForms();
    },

    handleModals: function(){
        $('.remove_scholar').click(function(ev){
            ev.preventDefault();
            let scholar = $(this).data('scholar'),
                name    = $(this).data('name');
            $('#scholar_idStudent').val(scholar);
            $('#scholar_name').text(name);
            $('#remove_scholar_modal').modal('show');
        });
    },

    handleScholarForms: function(){
        $('#frmDeleteScholar').submit(function(event){
            event.preventDefault();
            $.ajax({
                synch: 'true',
                type: 'POST',
                data: $('#frmDeleteScholar').serialize(),
                url: _root_ + 'becas/quitar_beca'
            })
            .done(function(response){
                if (response.success) {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-green');
                    $('#remove_scholar_modal').modal('hide');
                    setTimeout(()=>{
                        location.reload(true);
                    }, 1000);
                } else {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-red');
                }
            })
            .fail(function(errno){
                $('#general_snack').attr('data-content', 'ERROR:500, Error desconocido, reporte el incidente!');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-red');
            });
        });
    },

    handleLoginUser: function() {
        let _this = this;
        $('#loginForm').submit(function(event){
            event.preventDefault();
            $.ajax({
                synch: 'true',
                type: 'POST',
                data: $('#frmDeleteScholar').serialize(),
                url: _root_ + 'becas/quitar_beca'
            })
            .done(function(response){
                if (response.success) {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-green');
                } else {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-red');
                }
            })
            .fail(function(errno){
                $('#general_snack').attr('data-content', 'ERROR:500, Error desconocido, reporte el incidente!');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-red');
            });
        });
    }
};

Becados.initialize();
