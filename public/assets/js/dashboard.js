var Dashboard = {
    initialize: function(){
        console.log('Dashboard Initialize');
        this.loadCalendar();
    },


    loadCalendar: function(){
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
    
};

Dashboard.initialize();
