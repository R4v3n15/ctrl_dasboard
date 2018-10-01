var Croquis = {
    initialize: function(){
        console.log('Croquis Initialize');
        this.updateStudentLocation();
    },

    updateStudentLocation: function(){
        $('#saveLocation').click(function(event) {
            let latitud   = $('#lat').val(),
                longitud  = $('#lng').val(),
                user_type = $('#user_type').val(),
                user   = $('#user').val(),
                street    = $('#street').val(),
                numero    = $('#number').val(),
                between   = $('#between').val(),
                colony    = $('#colony').val();

            if (latitud === '' || latitud === undefined || longitud === '' || longitud === undefined) {
                console.log(latitud, longitud)
                return;
            }

            if (user === '' || user === undefined || user == null) {
                return;
            }

            if (street === '' && numero === '' && between === '' && colony === '') {
                return;
            }

            // console.log(latitud, longitud, user_type, user, street, numero, between, colony);

            $.ajax({
                data: {
                    user_type: user_type,
                    user: user,
                    street: street,
                    numero: numero,
                    between: between,
                    colony: colony,
                    latitud: latitud,
                    longitud: longitud
                },
                synch: 'true',
                type: 'POST',
                url: _root_ + 'mapa/actualizarUbicacion'
            })
            .done(function(response){
                // console.log(response);
                $('#general_snack').attr('data-content', response.message);
                $('#general_snack').snackbar('show');
                $('.snackbar').addClass(response.success ? 'snackbar-green' : 'snackbar-red');
            });
        });
    },

    
};

Croquis.initialize();
