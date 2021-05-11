const random_number = (range) => {
    return Math.round( Math.random() * range );
}

const currency = (num) => {
    return Math.round(num).toLocaleString().replace(",", ".");
}

const loader = (action, param = null) => {
    switch(action) {
        case 'show':
            if( $(".preloader").attr("data-preloader") !== undefined )
                $(".preloader").removeAttr("data-preloader");
            $(".preloader").show();
            break;
        case 'hide':
            $(".preloader").fadeOut("slow");
            break;
        case 'onhide':
            var interval = setInterval(() => {
                if( $(".preloader").attr("style") == "display: none;" ) {
                    param();
                    clearInterval(interval);
                }
            }, 100);
            break;
            
        default:
            return;
    }
}
loader("hide");

const show_result = (status, message) => {
    loader("onhide", () => {
        $.notify({
            icon: (status == "success") ? "fas fa-check-circle" : "fas fa-times-circle",
            title: (status == "success") ? "Berhasil:" : "Gagal:",
            message: message
        }, {    
            type: status,
            mouse_over: "pause",
            newest_on_top: true,
            delay: 3500,
            animate: {
                enter: "animate__animated animate__" + ((status == "success") ? "fadeInDown" : "shakeX"),
                exit: "animate__animated animate__fadeOutUp"
            },
            template: '<div data-notify="container" class="col-11 col-md-4 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title"><b>{1}</b></span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                    '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>',
            onShow: () => {
                var length_notify = $("[data-notify=container]").length;
                if(length_notify > 4) {
                    for(i=0; i < (length_notify - 4); i++)    
                        $(`[data-notify=container]:eq(${i})`).fadeOut();
                }
            }
        });
    });
}

$( function() {
    $("body").tooltip({
        selector: '[data-toggle="tooltip"]',
        container: 'body'
    });

    $("[type=file][data-image-preview!=false]").on("change", function() {
        var form_name = $(this).attr("name");
        var reader = new FileReader();
        
        reader.onload = function(e) {
            $(`[id=image-preview-${form_name}]`).attr("src", e.target.result);
        }

        reader.readAsDataURL($(this)[0]['files'][0]);
    });
});