$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    if (IS_MOBILE) {
        $(".dropdown-submenu a.submenu").on("click", function (e) {
            $(this).next("ul").toggle();
            e.stopPropagation();
            e.preventDefault();
        });

        /* $("body").on("click", function () {
            if ($(".sub-menu").css("display") != "none") {
                $(".sub-menu").css("display", "none");
            }
        }); */
    }

    $("a").on("click", function () {
        if (!CONNECT && $(this).attr("href") != "#") {
            return false;
        }
    });

    $("#app-navbar-collapse")
        .find(".active")
        .parent()
        .parent()
        .parent()
        .addClass("active");
    $("#app-navbar-collapse")
        .find(".active")
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .addClass("active");
    $('[data-toggle="tooltip"]').tooltip();

    _onfocus();
    _tc();
});

function _onfocus() {
    /* this focus on last character if input isn't empty. https://stackoverflow.com/a/53196878/6885956 */
    let el = $(".autofocus");
    tmp = el.val();
    el.val("").val(tmp).blur().focus();
}

function _tc() {
    setTimeout(function () {
        _tc();
    }, 1000 * 30);

    $.ajax({
        url: ADMIN_URL + "/test/connection",
        cache: false,
    })
        .done(function (data) {
            $("#status-server")
                .removeClass("bg-danger")
                .addClass("bg-success")
                .text("Terhubung");
            CONNECT = true;
        })
        .fail(function () {
            $("#status-server")
                .removeClass("bg-success")
                .addClass("bg-danger")
                .text("Terputus");
            CONNECT = false;
        });
}

function _get_slug(txt) {
    return txt
        .trim()
        .replace(/\s+/g, " ")
        .replace(/\W/g, " ")
        .replace(/\s+/g, "-")
        .replace(/\-$/, "")
        .toLowerCase();
}

function _get_status_text(status = "inactive", par = []) {
    /* check custom parameter */
    if (par.length == 0) {
        par = {
            active: "Aktif",
            inactive: "Nonaktif",
        };
    }

    /* generate text */
    if (status == "active") {
        return (
            '<span class="tag bg-success text-center text-white" style="padding:2px 5px;">' +
            par[status] +
            "</span>"
        );
    }

    return (
        '<span class="tag bg-danger text-center text-white" style="padding:2px 5px;">' +
        par[status] +
        "</span>"
    );
}

function _delete(URL = "", text = null) {
    if (!CONNECT) {
        return false;
    }

    var formDel =
        '<form id="form-delete" class="form-delete" action="' +
        URL +
        '" method="post">';
    formDel += '<input type="hidden" name="_method" value="DELETE">';
    formDel += '<input type="hidden" name="_token" value="' + TOKEN + '">';
    /* formDel += '<label style="font-weight:normal;"><input type="checkbox" name="hard_delete"> Delete permanently</label>'; */
    formDel += "</form>";

    swal({
        title: "Hapus data" + (text ? " " + text : "") + "?",
        html: formDel,
        type: "warning",
        showCancelButton: true,
        cancelButtonText: "Tidak",
        confirmButtonColor: "#d33",
        confirmButtonText: "Ya, hapus!",
    }).then(function (isConfirm) {
        if (isConfirm) {
            $("#form-delete").submit();
        }
    });
}

function _remove(id) {
    $(id).remove();
}

function _open_window(url, w = 700, h = 260) {
    window.open(
        url,
        "_blank",
        "toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, width=" +
            w +
            ", height=" +
            h
    );
}
