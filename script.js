$( document ).ready(function() {
    const err_create = $(".init-room .err.creat-room p");
    const err_join = $(".init-room .err.no-room p");
    let init_form = $("div.init-room");
    let tictactoe_board = $("#tictac_board");
    let board_inputs = $("#tictac_board input[type='text']");

    // Board logic *** Update Board ***
    $("input.cell").click((e) => {
        update_board(e)
    });

    // init form create/join change submit button text
    $("#init-room-form input[type='text']").click(function (e) {
        init_room_inputs(e)
    })

    // submitting create/join form
    $("#init-room-form").submit((e) => {
        create_room_or_join(e);
    })

    function init_room_inputs(event) {
        let buttonText = event.target.labels[0].outerText;

        $("#init-room-form input[type='submit']").val(buttonText.slice(0, buttonText.indexOf(':')));
        $("#init-room-form input[type='submit']").prop("disabled", false);

        // check if the user clicked on the same input after entering a value, if not then reset the form and err msg's
        if ($(event.target).val() === '') {
            $("#init-room-form")[0].reset();
            err_create.text('');
            err_join.text('');
        }
    }

    function create_room_or_join(event) {
        event.preventDefault();
        let elements = event.target.length;
        let field_type = '';
        let input_value = '';

        // grab the value of the filled out field and set the field type to the corresponding php file name
        for (let i = 0; i < elements; i++) {
            if ( $(event.target[i]).val() ) {
                if ( i !== elements - 1 ) {
                    input_value = $(event.target[i]).val();

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

        // create room ajax call
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
                    // handle setting up new game room (add query params to current path, remove overhang form, show board)
                    show_board(result, true);
                }

            });

            request.fail( function (iqXHR, status) {
                alert("Request Failed:" + status);
            });
        }

        // join room ajax call
        if (input_value && field_type === 'join_room') {
            let request = $.ajax({
                method: "POST",
                url: 'api/' + field_type + '.php',
                data: {'secret_word': input_value},
                dataType: 'json'
            });

            request.done(function (result) {
                console.log('done. ' + result);
                if (result.error) {
                    // return error result (duplicate secret word)
                    err_join.text(result.error);
                } else {
                    // handle setting up new game room (add query params to current path, remove overhang form, show board)
                    show_board(result, false);
                }

            });

            request.fail(function (iqXHR, status) {
                alert("Request Failed:" + status);
            });
        }

        // code for refreshing board call (1second interval)
        // window.setInterval(function(){
        //     let request = $.ajax({
        //         method: "POST",
        //         url: 'api/' + 'board_data' + '.php',
        //         data: board_update(board_inputs, room_id, user_id),
        //         dataType: 'json'
        //     });
        // }, 1000);

    }

    function update_board(event) {
        let user_char = $('#user_char').val().toLowerCase();
        let room_id = $("#room_id").val();
        let user_id = $("#user_id").val();

        if ( $(event.target).val() === '' ){
            $(event.target).val(user_char);

            let request = $.ajax({
                method: "POST",
                url: 'api/' + 'board_data' + '.php',
                data: board_update(board_inputs, room_id, user_id),
                dataType: 'json'
            });

            request.done( function ( result ) {
                console.log('done. ' + result);
                if ( result.error ){
                    // return error result (duplicate secret word)
                    err_join.text(result.error);
                } else {
                    // handle setting up new game room (add query params to current path, remove overhang form, show board)
                    parse_board(result.table_data, board_inputs);
                    $('#user_char').val(result.char);
                    $("#room_id").val(result.id);
                    $("#user_id").val(result.user_id);
                }

            });

            request.fail( function (iqXHR, status) {
                alert("Request Failed:" + status);
            });
        }
    }

    function parse_board(obj, board){
        // strange preg replace to make array from string array '[null,null,etc...]'
        let array = obj.replace(/\[|\]/g,'').split(',')

        array.forEach( function(board_input, index){
            if (board_input === 'null'){
                $(board[index]).val('');
            }

            if (board_input === '0'){
                $(board[index]).val('o');
            }

            if (board_input === '1'){
                $(board[index]).val('x');
            }
        })
    }

    function board_update(board, room_id, user_id){
        let i = 0;
        let js_obj = {};
        for (let key in board) {
            if (board.hasOwnProperty(key) && i < 9) {
                // make object to send via ajax
                js_obj[i] = board[key].value;
                i++;
            }
        }

        return {board_data: js_obj, room_id: room_id, user_id: user_id};
    }

    function show_board(result, userOne){
        let user_id = userOne ? result.user_one_id : result.user_two_id;
        let newurl = window.location.protocol +
            "//" +
            window.location.host + window.location.pathname +
            "?room_id=" +
            result.id +
            "&user_id=" +
            user_id;
        window.history.pushState({path:newurl},'',newurl);
        parse_board(result.table_data, board_inputs);
        $('#user_char').val(result.char);
        $("#room_id").val(result.id);
        $("#user_id").val(user_id);
        init_form.hide();
        tictactoe_board.removeClass("d-invisible");
    }
});