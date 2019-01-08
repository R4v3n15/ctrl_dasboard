var LoginUser = {
    initialize: function(){
        console.log('Login Process Initialize');
        this.handleLoginUser()
    },

    handleLoginUser: function() {
        let _this = this;
        $('#loginForm').submit(function(event){
            event.preventDefault();
            $.ajax({
                synch: 'true',
                type: 'POST',
                data: $('#loginForm').serialize(),
                url: _root_ + 'login/login'
            })
            .done(function(response){
                if (response.success) {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-green');
                    _this.loadAllDataBefore();
                } else {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-red');
                }
            })
            .fail(function(errno){
                console.log(errno.message)
                $('#general_snack').attr('data-content', 'ERROR:500, Error desconocido, reporte el incidente!');
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass('snackbar-red');
                console.log(errno);
            });
        });
    },

    loadAllDataBefore: function(){
        $('.login-form').addClass('d-none');
        $('.loading').removeClass('d-none');
        setTimeout(function(){
            window.location.replace(_root_ + 'alumnos');
        }, 3000);
    }
};

LoginUser.initialize();
