const refresh_keranjang = () => {
    $.ajax({
        url: `${base_url}/transaksi/ajax`,
        type: "POST",
        data: `csrf_token=${csrf_token}&action=refresh-keranjang`,
        dataType: "html",
    
        error: (jqxhr, txtStatus, err) => {
            loader("hide");
            show_result("danger", `Terjadi Kesalahan Ajax, ${txtStatus}`);
        },
        success: (res) => {
            if( $("body").hasClass("modal-open") ) {
                $("#keranjang-belanja #modal-keranjang-belanja").modal("hide");
                $("#keranjang-belanja #modal-keranjang-belanja").on("hidden.bs.modal", function() {
                    $("#keranjang-belanja").html(res);
                    $("#keranjang-belanja #modal-keranjang-belanja").modal("show");
                });

            } else {
                $("#keranjang-belanja").html(res);
            }
        }
    });
}

const tambah_produk = (id_produk, produk) => {
    $("#modal-tambah-produk form [name=id_produk]").val(id_produk);
    $("#modal-tambah-produk form [id=produk]").val(produk);
    $("#modal-tambah-produk").modal("show");
}

$( function() {
    $(document).on("click", "#modal-keranjang-belanja form [data-dismiss=modal]", function() {
        loader("show");
        $.ajax({
            url: `${base_url}/transaksi/ajax`,
            type: "POST",
            data: `csrf_token=${csrf_token}&action=reset-keranjang`,
            dataType: "json",
    
            error: (jqxhr, txtStatus, err) => {
                loader("hide");
                show_result("danger", `Terjadi Kesalahan Ajax, ${txtStatus}`);
            },
            success: (res) => {
                loader("hide");
                loader("onhide", () => {
                    refresh_keranjang();
                });
                show_result("success", res.message);
            }
        });
    });

    $(document).on("click", "#modal-tambah-produk form button[type=submit]", function(e) {
        e.preventDefault();
        var form_data = $("#modal-tambah-produk form").serialize();

        loader("show");
        $.ajax({
            url: `${base_url}/transaksi/ajax`,
            type: "POST",
            data: form_data,
            dataType: "json",
    
            error: (jqxhr, txtStatus, err) => {
                loader("hide");
                show_result("danger", `Terjadi Kesalahan Ajax, ${txtStatus}`);
            },
            success: (res) => {
                loader("hide");
                loader("onhide", () => {
                    if(res.status == 1) {
                        refresh_keranjang();
                        show_result("success", res.message);

                    } else {
                        show_result("danger", res.message);
                    }
                });
            }
        });
    });

    $(document).on("click", "#modal-keranjang-belanja [id=edit-jumlah]", function() {
        var jumlah_pesanan = $(this).parent().find("#jumlah-pesanan").html(),
            jumlah_pesanan = parseInt(jumlah_pesanan),
            operasi = $(this).attr("data-operasi"),
            id_produk = $(this).parent().find("input[name=id_produk]").val();
        
        if(operasi == 'kurang') {
            var hasil = (jumlah_pesanan >= 1) ? jumlah_pesanan - 1 : 0;
        } else {
            var hasil = jumlah_pesanan + 1;
        }

        $(this).parent().find("#jumlah-pesanan").html(`<span class="spinner-border text-primary" role="status" style="height: 1.5rem; width: 1.5rem;"></span>`);
        $.ajax({
            url: `${base_url}/transaksi/ajax`,
            type: "POST",
            data: `csrf_token=${csrf_token}&action=edit-jumlah&operasi=${operasi}&id=${id_produk}`,
            dataType: "json",
    
            error: (jqxhr, txtStatus, err) => {
                $("#modal-keranjang-belanja #jumlah-pesanan").html(jumlah_pesanan);
                show_result("danger", `Terjadi Kesalahan Ajax, ${txtStatus}`);;
            },
            success: (res) => {
                if(res.status == 1) {
                    refresh_keranjang();

                } else {
                    $("#modal-keranjang-belanja #jumlah-pesanan").html(jumlah_pesanan);
                    show_result("danger", res.message);
                }
            }
        });
    });

    var table = $("#datatable").DataTable({
        "serverSide": true,
        "processing": true,
        "responsive": true,
        "order": [[1, "asc"]],
        "ajax": {
            "url": `${base_url}/transaksi/ajax`,
            "method": "POST",
            "data": (d) => {
                d.csrf_token = csrf_token;
                d.action = "render-produk";
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
                "targets": [0,3], "orderable": false
            }
        ]
    });
});