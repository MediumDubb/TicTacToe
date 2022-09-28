$( document ).ready(function() {
    let user_char = $('#user_char').val().toLowerCase();
    $("input.cell").click((e) => {
        $(e.target).val(user_char);
        $("#tictac_board").submit();
    });

    $("#init-room-form input").click( (e) => {
        let buttonText = e.target.labels[0].outerText;
        $("#init-room-form input[type='submit']").val(buttonText.slice(0, buttonText.indexOf(':')));
        $("#init-room-form input[type='submit']").prop("disabled", false);
    })

    $("#init-room-form input").submit( (e) => {
        e.target.forEach( (val) => {

        });
    })
});