var Filter = {
    vars : {
        currentPage: 0
    },

    initialize: function(){
        console.log('Filter Initialize');
        // this.activeFilter();
    },

    activeFilter: function(){
        $('#filter').change(function(event) {
            if ($(this).val() !== '') {
                console.log($(this).val())
                $('#field').prop('placeholder', 'Nombre del ' + $(this).val());
            }
        });
    },

    
};

Filter.initialize();
