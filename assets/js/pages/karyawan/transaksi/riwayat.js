$( function() {
    var table = $("#datatable").DataTable({
        "serverSide": true,
        "processing": true,
        "responsive": true,
        "order": [[0, "desc"]],
        "ajax": {
            "url": location.href,
            "method": "POST",
            "data": (d) => {
                d.csrf_token = csrf_token;
            },
            "error": (jqxhr, txtStatus, err) => {
                show_result("danger", `Terjadi Kesalahan Ajax, ${txtStatus}`);
            }
        },
        language: {
            searchPlaceholder: "Ketik pencarian disini.."
        },
        "columnDefs": [
            {
                "targets": [1,2,4], "orderable": false
            }
        ]
    }); 
})