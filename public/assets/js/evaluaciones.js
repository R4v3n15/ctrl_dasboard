var Evaluaciones = {
    initialize: function(){
        console.log('Evaluations Initialize');
        this.setEvaluationPoints()
    },

    setEvaluationPoints: function(){
        let _this = this;
            

        if ($('#evaluation_date').length) {
            new Pikaday(
                    {
                        field: document.getElementById('evaluation_date'),
                        defaultDate: moment().toDate(),
                        setDefaultDate: true,
                        format: 'YYYY-MM-DD'
                    });
        }
        // Evaluate Achievement
        $('.read_achiev').click(function(){
            let value = $(this).data('val');
            $('#reading').val(value);
            $('.read_achiev').find('span').removeClass('checked');
            $(this).find('span').addClass('checked');
        });

        $('.write_achiev').click(function(){
            let value = $(this).data('val');
            $('#writing').val(value);
            $('.write_achiev').children('span').removeClass('checked');
            $(this).children('span').addClass('checked');
        });

        $('.speak_achiev').click(function(){
            let value = $(this).data('val');
            $('#speaking').val(value);
            $('.speak_achiev').children('span').removeClass('checked');
            $(this).children('span').addClass('checked');
        });

        $('.listen_achiev').click(function(){
            let value = $(this).data('val');
            $('#listening').val(value);
            $('.listen_achiev').children('span').removeClass('checked');
            $(this).children('span').addClass('checked');
        });

        // Evaluate Effort
        $('.read_effort').click(function(){
            let value = $(this).data('val');
            $('#read').val(value);
            $('.read_effort').children('span').removeClass('checked')
            $(this).children('span').addClass('checked');
        });

        $('.write_effort').click(function(){
            let value = $(this).data('val');
            $('#write').val(value);
            $('.write_effort').children('span').removeClass('checked')
            $(this).children('span').addClass('checked');
        });

        $('.speak_effort').click(function(){
            let value = $(this).data('val');
            $('#speak').val(value);
            $('.speak_effort').children('span').removeClass('checked')
            $(this).children('span').addClass('checked');
        });

        $('.listen_effort').click(function(){
            let value = $(this).data('val');
            $('#listen').val(value);
            $('.listen_effort').children('span').removeClass('checked')
            $(this).children('span').addClass('checked');
        });

        $('.active_effort').click(function(){
            let value = $(this).data('val');
            $('#attitude').val(value);
            $('.active_effort').children('span').removeClass('checked')
            $(this).children('span').addClass('checked');
        });

        $('.team_effort').click(function(){
            let value = $(this).data('val');
            $('#team').val(value);
            $('.team_effort').children('span').removeClass('checked')
            $(this).children('span').addClass('checked');
        });

        $('.homew_effort').click(function(){
            let value = $(this).data('val');
            $('#time').val(value);
            $('.homew_effort').children('span').removeClass('checked')
            $(this).children('span').addClass('checked');
        });
    },

    newEvaluation: function() {
        $('#new_evaluation').on('click', function(){
            $.ajax({
                synch: 'true',
                type: 'POST',
                url: _root_ + 'evaluaciones/nuevaEvaluacion',
                success: function(data){
                    $('#new_evaluation').hide();
                    $('#evaluation_template').html(data);


                    $('.btn_volver').on('click', function(){
                        // that.getClasses();
                    });
                }
            });
        });
    },
};

Evaluaciones.initialize();
