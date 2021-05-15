var table;
$( function() {
    table = $("#datatable").DataTable({
        "serverSide": true,
        "processing": true,
        "responsive": true,
        "order": [[1, "asc"]],
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
                "targets": [0,5], "orderable": false
            }
        ]
    }); 
})

const hapus = (id) => {
    if(!confirm('Apakah anda yakin ingin menghapus data produk?'))
        return false;    

    loader("show");
    $.ajax({
        url: `${base_url}/admin/produk/hapus`,
        type: "POST",
        data: `csrf_token=${csrf_token}&id=${id}`,
        dataType: "json",

        error: (jqxhr, txtStatus, err) => {
            loader("hide");
            show_result("danger", `Terjadi Kesalahan Ajax, ${txtStatus}`);;
        },
        success: (res) => {
            loader("hide");

            if(res.status == 1) {
                table.draw();
                show_result("success", res.message);

            } else {
                show_result("danger", res.message);
            }
        }
    });
}