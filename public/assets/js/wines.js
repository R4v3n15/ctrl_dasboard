var Perfil = {
    vars: {
        'currentPage': 0
    },

    initialize: function(){
        console.log('Profile Initialize');
        // this.getWinesList();
        this.loadTable();
        this.filterTable()
        this.openModal();

    },

    getWinesList: function(){
        $.ajax({
            synch: 'false',
            type: 'POST',
            url: _root_ + 'wines/wineList',
            success: function(data){
                $('#wines_table').html(data);
            }
        });
    },

    loadTable: function(){
        let _this = this;
        let tipo = $('#vino_tipo').val();
        _this.getWinesTable(tipo);
    },

    filterTable: function() {
        let _this = this;
        $('#vino_tipo').change(function(event) {
            event.preventDefault();
            _this.getWinesTable($(this).val());
        });
    },

    getWinesTable: function(tipo, page=0){
        let _this = this;
        $.ajax({
            synch: 'false',
            type: 'POST',
            data: {tipo: tipo, page: page},
            url: _root_ + 'wines/wineTypes',
            success: function(data){
                $('#wines_table').html(data);
                _this.updateWinePrice();
                _this.navigationPage();
            }
        });
    },

    navigationPage: function(){
        let that = this;
        $(".page-clases").on('click', function(event){
            event.preventDefault();
            let tipo = $('#vino_tipo').val();
            that.vars.currentPage = parseInt($(this).data("clases"));
            that.getWinesTable(tipo, that.vars.currentPage);
        });
    },

    updateWinePrice: function(){
        let _this = this;
        $('.btn_update').click(function(event) {
            event.preventDefault();
            let vino = $(this).data('vino'),
                precio = $('#precio_'+vino).val(),
                costo  = $('#costo_'+vino).val();

            if (vino !== '' && precio !== '' && costo !== '') {
                $.ajax({
                    synch: 'false',
                    type: 'POST',
                    data: { vino: vino, 
                            precio: precio, 
                            costo: costo},
                    url: _root_ + 'wines/updateWine',
                })
                .done(function(data){
                    let tipo = $('#vino_tipo').val();
                    console.log(data.message);
                    _this.getWinesTable(tipo, _this.vars.currentPage);
                });
            }

        });
    },

    openModal: function () {
        let _this = this;
        $('#add_wine').click(function(event) {
            event.preventDefault();
            $('#ModalWine').modal('show');
        });

        $('#save_wine').click(function(event) {
            event.preventDefault();
            $.ajax({
                synch: 'false',
                type: 'POST',
                data: {nombre: $('#nombre').val(), 
                        precio: $('#precio').val(), 
                        costo: $('#costo').val(), 
                        tipo: $('#tipo').val(), 
                        pais:$('#pais').val()},
                url: _root_ + 'wines/saveWine',
            })
            .done(function(data){
                let tipo = $('#vino_tipo').val();
                _this.getWinesTable(tipo, _this.vars.currentPage);
                $('#ModalWine').modal('hide');
            });
        });
    },



    
};

Perfil.initialize();
