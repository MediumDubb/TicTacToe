$( document ).ready(function() {
    const err_create = $(".init-room .err.creat-room p");
    const err_join = $(".init-room .err.no-room p");

    $("input.cell").click((e) => {
        let user_char = $('#user_char').val().toLowerCase();

        $(e.target).val(user_char);
        $("#tictac_board").submit( (e) => {
            e.preventDefault();
            let $board_inputs = $("#tictac_board input[type='text']");
        });
    });

    $("#init-room-form input[type='text']").click( function(e) {
        let buttonText = e.target.labels[0].outerText;

        $("#init-room-form input[type='submit']").val(buttonText.slice(0, buttonText.indexOf(':')));
        $("#init-room-form input[type='submit']").prop("disabled", false);

        // check if the user clicked on the same input after entering a value, if not then reset the form and err msg's
        if ( $(this).val() === '' ) {
            $("#init-room-form")[0].reset();
            err_create.text('');
            err_join.text('');
        }
    })

    $("#init-room-form").submit( (e) => {
        e.preventDefault();
        let elements = e.target.length;
        let field_type = '';
        let input_value = '';

        // grab the value of the filled out field and set the field type to the corresponding php file name
        for (let i = 0; i < elements; i++) {
            if ( $(e.target[i]).val() ) {
                if ( i !== elements - 1 ) {
                    input_value = $(e.target[i]).val();

                    if ( i === 0){
                        // first input box is for create_room
                        field_type = 'create_room'
                    }

                    if ( i === 1){
                        //second input box is for join_room
                        field_type = 'join_room'
                    }

                }
            }
        }

        if (input_value && field_type === 'create_room') {
            let request = $.ajax({
                method: "POST",
                url: 'api/' + field_type + '.php',
                data: {'secret_word': input_value},
                dataType: 'json'
            });

            request.done( function ( result ) {
                console.log('done. ' + result);
                if ( result.error ){
                    // return error result (duplicate secret word)
                    err_create.text(result.error);
                } else {
                    // handle setting up new game room (add query params to current path, remove overhang form)
                    let newurl = window.location.protocol +
                        "//" +
                        window.location.host + window.location.pathname +
                        "?room=" +
                        result.id +
                        "&user_id=" +
                        result.user_one_id;
                    window.history.pushState({path:newurl},'',newurl);
                    $('#user_char').val(result.char);
                    $("div.init-room").hide();
                    $("#tictac_board").removeClass("d-invisible");
                }

            });

            request.fail( function (iqXHR, status) {
                alert("Request Failed:" + status);
            });
        }

        if (input_value && field_type === 'join_room') {
            let request = $.ajax({
                method: "POST",
                url: 'api/' + field_type + '.php',
                data: {'secret_word': input_value},
                dataType: 'json'
            });

            request.done( function ( result ) {
                console.log('done. ' + result);
                if ( result.error ){
                    // return error result (duplicate secret word)
                    err_join.text(result.error);
                } else {
                    // handle setting up new game room (add query params to current path, remove overhang form)
                    let newurl = window.location.protocol +
                        "//" +
                        window.location.host + window.location.pathname +
                        "?room=" +
                        result.id +
                        "&user_id=" +
                        result.user_two_id;
                    window.history.pushState({path:newurl},'',newurl);
                    $('#user_char').val(result.char);
                    $("div.init-room").hide();
                    $("#tictac_board").removeClass("d-invisible");
                }

            });

            request.fail( function (iqXHR, status) {
                alert("Request Failed:" + status);
            });
        }
    })
});