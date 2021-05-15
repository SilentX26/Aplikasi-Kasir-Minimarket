var table;
var obj_filter = {};

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
                "targets": [0,4], "orderable": false
            }
        ]
    }); 

    $(document).on("click", "#modal-filter button[type=button]", function() {
        var form_data = $("#modal-filter form").serializeArray();
        for(x of form_data) {
            var field_name = x.name,
                field_value = x.value;
            
            if(field_name.match(/\d+\b/gm) === null) {
                var url_string = "?";
                obj_filter[field_name] = field_value;
    
                for(x in obj_filter)
                    url_string += `${x}=${ obj_filter[x] }&`;
    
                table.ajax.url(location.href + url_string);
    
            } else {
                table.columns(field_name).search(field_value);
            }
        }

        table.draw();
    });

    $(document).on("click", "#modal-filter button[type=reset]", function() {
        table.ajax.url(location.href);
        table.columns().search("").draw();

        obj_filter = {};
    });
})

const hapus = (id) => {
    if(!confirm('Apakah anda yakin ingin menghapus data akun?'))
        return false;    

    loader("show");
    $.ajax({
        url: `${base_url}/admin/akun/hapus`,
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