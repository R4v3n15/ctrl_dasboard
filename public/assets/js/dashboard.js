var Dashboard = {
    initialize: function(){
        "use strict";
        let path = window.location.pathname.split('/'), that = this;
        if (path[1] === 'dashboard') {
            switch (path[2]) {
                case 'admin': // admin bd view
                    this.manageDatabase();
                    this.backupDatabase();
                    this.importDatabase();
                    break;
                default: // index
                    this.loadCalendar();
                    break;
            }
        }
    },


    loadCalendar: function(){
        console.log('Dashboard Initialize');

        $('#calendar').fullCalendar({
        	themeSystem: 'bootstrap4',
        	firstDay: 1,
        	locale: 'es',
        	events: _root_ + 'dashboard/eventos',
        	eventLimit: true,
        	views:{
        		month:{
        			eventLimit: 5
        		},
        		agenda:{
        			eventLimit:3,
        			hiddenDays: [0],
		        	slotLabelInterval: '00:30',
		        	slotLabelFormat: 'h:mm a',
		        	minTime: '09:00:00',
		        	maxTime: '21:00:00',
        		}
        	},
        	eventLimitClick: 'popover'
        });
    },

    manageDatabase: function(){
        console.log('Management View');

        $('#createTable').click(function(event) {
            event.preventDefault();

            $.ajax({
                synch: 'true',
                type: 'POST',
                url: _root_ + 'dashboard/nuevaTabla'
            })
            .then(function(response){
                if (response.success) {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-blue');
                } else {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-red');
                }
            });//End Ajax
        });

        $('#cleanDatabase').click(function(event) {
            event.preventDefault();

            $.ajax({
                synch: 'true',
                type: 'POST',
                url: _root_ + 'dashboard/limpiarDB'
            })
            .then(function(response){
                if (response.success) {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-blue');
                } else {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-red');
                }
            });//End Ajax
        });

        $('#feedDatabase').click(function(event) {
            event.preventDefault();

            $.ajax({
                synch: 'true',
                type: 'POST',
                url: _root_ + 'dashboard/cargarDB'
            })
            .then(function(response){
                if (response.success) {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-blue');
                } else {
                    $('#general_snack').attr('data-content', response.message);
                    $('#general_snack').snackbar('show');
                    $('.snackbar').addClass('snackbar-red');
                }
            });//End Ajax
        });
    },

    backupDatabase: function(){
        $('#createBackup').click(function(event) {
            event.preventDefault();

            $.ajax({
                synch: 'true',
                url: _root_ + 'dashboard/backDatabase',
                type: 'POST'
            })
            .done(function(response) {
                console.log(response);
            })
            .fail(function() {
                console.log("error");
            });

            // $.ajax({
            //     synch: 'true',
            //     url: _root_ + 'dashboard/backupDatabase',
            //     type: 'POST'
            // })
            // .done(function(response) {
            //     let clases = ["bg-info", "bg-success", "bg-warning", "bg-danger"];
            //     $.each(clases, function(i, v){
            //        $('#alert').removeClass(v);
            //     });

            //     if (response.success) {
            //         $('#alert').addClass("bg-success");
            //     } else {
            //         $('#alert').addClass("bg-danger");
            //     }

            //     $('#alert').addClass("show");
            //     $('#alertcontent').text(response.message);
            // })
            // .fail(function() {
            //     console.log("error");
            // });
            
        });
    },

    importDatabase: function(){
        $('#importDatabase').click(function(event) {
            event.preventDefault();

            $.ajax({
                synch: 'true',
                url: _root_ + 'dashboard/importDatabase',
                type: 'POST'
            })
            .done(function(response) {
                let clases = ["bg-info", "bg-success", "bg-warning", "bg-danger"];
                $.each(clases, function(i, v){
                   $('#alert').removeClass(v);
                });

                if (response.success) {
                    $('#alert').addClass("bg-success");
                } else {
                    $('#alert').addClass("bg-danger");
                }

                $('#alert').addClass("show");
                $('#alertcontent').text(response.message);
            })
            .fail(function() {
                console.log("error");
            });
        });
    }
    
    
};

Dashboard.initialize();
