$( document ).ready(function() {
    let user_char = $('#user_char').val().toLowerCase();
    $("input.cell").click((e) => {
        $(e.target).val(user_char);
        $("#tictac_board").submit();
    });
});