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

const tambah_produk = (id) => {
    loader("show");
    $.ajax({
        url: `${base_url}/transaksi/ajax`,
        type: "POST",
        data: `csrf_token=${csrf_token}&action=tambah-produk&id=${id}`,
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
}

$( function() {
    $(document).on("click", "#modal-keranjang-belanja #kurang-jumlah", function() {
        var jumlah_pesanan = $("#modal-keranjang-belanja #jumlah-pesanan").html();
        jumlah_pesanan = parseInt(jumlah_pesanan);

        var hasil = (jumlah_pesanan >= 1) ? jumlah_pesanan - 1 : 0;
        $("#modal-keranjang-belanja #jumlah-pesanan").html(hasil);
    });

    $(document).on("click", "#modal-keranjang-belanja #tambah-jumlah", function() {
        var jumlah_pesanan = $("#modal-keranjang-belanja #jumlah-pesanan").html();
        jumlah_pesanan = parseInt(jumlah_pesanan);

        var hasil = jumlah_pesanan + 1;
        $("#modal-keranjang-belanja #jumlah-pesanan").html(hasil);
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