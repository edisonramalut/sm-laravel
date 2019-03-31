function onDelete(e) {
    var id = $(e).data('id');
    $('#deleteForm').prop('action', '/employee/'+id);
    $('.deleteModal').modal('show');
}

function onEdit(e) {
    var id = $(e).data('id');
    var token = $('#token').val();
    $.ajax({
        type: "GET",
        headers: { 'X-XSRF-TOKEN' : token },
        url: '/employee/'+id+"/edit",
        success: function(data){
            $('.editModal').html(data);
            $('.editModal').modal('show');
        }
    });
}