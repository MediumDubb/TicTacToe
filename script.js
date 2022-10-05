$( document ).ready(function() {
    let user_char = $('#user_char').val().toLowerCase();
    $("input.cell").click((e) => {
        $(e.target).val(user_char);
        $("#tictac_board").submit();
    });

    $("#init-room-form input[type='text']").click( function(e) {
        let buttonText = e.target.labels[0].outerText;

        $("#init-room-form input[type='submit']").val(buttonText.slice(0, buttonText.indexOf(':')));
        $("#init-room-form input[type='submit']").prop("disabled", false);

        // check if the user clicked on the same input, if not then reset the form
        if ($("#init-room-form input[type='text']")[0] !== this) {
            $("#init-room-form")[0].reset();
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

        if (input_value && field_type !== '') {
            let request = $.ajax({
                method: "POST",
                url: 'api/' + field_type + '.php',
                data: {'secret_word': input_value},
                dataType: 'json'
            });

            request.done( function ( result ) {
                console.log('done. ' + result);
                if ( result.error ){
                    $(".init-room .err p").innerText(result.error);
                }
            });

            request.fail( function (iqXHR, status) {
                alert("Request Failed:" + status);
            });
        }
    })
});