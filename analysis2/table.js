$( document ).ready(function() {
var table = $('#editableTable').DataTable({
    paging: false,
    searching: true,
    info: false,
    sDom: '<"top"i>rt<"bottom"lp><"clear">' // disable global search field <"bottom"flp> where f stands for filtering
});

$('#searchVorname').on('keyup', function () {
    table.columns(1).search( this.value ).draw();
} );
$('#searchNachname').on('keyup', function () {
    table.columns(2).search( this.value ).draw();
} );

$('#searchAssistent').on('keyup', function () {
    table.columns(3).search( this.value ).draw();
} );

var start = parseInt(document.getElementById("start").innerText);
var total = parseInt(document.getElementById("total").innerText);
var option = new Array(total);
var aa = 4;
for(let i=0; i<option.length;i++){
	    option[i] = [aa,'Serie' + (i+start)];
	    aa++;
}

$('#editableTable').Tabledit({
    url: 'update.php',
    editButton: false,
    deleteButton: false,
    hideIdentifier: false,
    columns: {
        identifier: [0, 'leginr'],
        editable: option
    }
});

$('#assistent').on('change', function () {
    $('#content').show();
    $('#searchAssistent').val($('#assistent option:selected').text());
    $('#searchAssistent').keyup();

});
});
