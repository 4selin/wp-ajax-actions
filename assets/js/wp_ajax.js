jQuery(function ($) {

    $('body').on('click','.submit_ajax_form', function (e) {
        e.preventDefault();

        var submit_ajax_form = $(this);
        var ajax_form = $(this).closest('.wpaa_form');

        var result_cont;
        var result_cont_name = $(this).find("input[name='result_cont']").val();
        if (typeof result_cont_name == 'undefined') {
            result_cont = ajax_form.find('.result');
        }else{
            result_cont = $('#'+result_cont_name);
        }
        var spinner = ajax_form.find('.osp_ajax.spinner');

        spinner.addClass('is-active');

        var data;
        data = ajax_form.find(':input').serializeArray();
        data.push({name: 'action', value: 'wpaa_function'});

        console.table(data);

        // http://api.jquery.com/jquery.ajax/
        $.ajax(ajaxurl, {
            cache: false,
            data: data,
            method: 'POST',
            //processData: false,  // tell jQuery not to process the data
            //contentType: false, // tell jQuery not to set contentType
            error: function (jqXHR, textStatus, errorThrown) {
                spinner.removeClass('is-active');

                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);

                result_cont.html(textStatus + ' ' + errorThrown);
            },
            // statusCode: {
            //     404: function() {
            //         alert( "page not found" );
            //     }
            // }
            success: function (response) {
                result_cont.html(response);

                // result_cont.prepend('<br>* * * * * * * * * * * * * * * * * * * * * <br>');
                // result_cont.prepend(response);

                spinner.removeClass('is-active');

                if (ajax_form.find('input[name="loop"]').val() == '1') {
                    submit_ajax_form.click();
                }

            }
        });

    });

    $('body').on('click', '.wpaa_form .clear', function () {
        var ajax_form = $(this).closest('.wpaa_form');
        var result_cont = ajax_form.find('.result');
        result_cont.empty();
    });


});
