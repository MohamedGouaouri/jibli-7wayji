jQuery(function ($) {

    $(".sidebar-dropdown > a").click(function() {
        $(".sidebar-submenu").slideUp(200);
        if (
            $(this)
                .parent()
                .hasClass("active")
        ) {
            $(".sidebar-dropdown").removeClass("active");
            $(this)
                .parent()
                .removeClass("active");
        } else {
            $(".sidebar-dropdown").removeClass("active");
            $(this)
                .next(".sidebar-submenu")
                .slideDown(200);
            $(this)
                .parent()
                .addClass("active");
        }
    });

    $("#close-sidebar").click(function() {
        $(".page-wrapper").removeClass("toggled");
    });
    $("#show-sidebar").click(function() {
        $(".page-wrapper").addClass("toggled");
    });

});



// ================================= Page navigation =============================
$(document).ready(function () {
    $("#get-clients").click((e) => {
        let url = 'admin_clients'
        $.get(url, function (data) {
            $("#content").html(data);
            // ban user
            $("button.ban-user").click((e) => {
                let btn = $(e.target);
                let user_id = btn.attr("data-user-id");
                let url = "ban_user";
                console.log(user_id);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "user_id": user_id
                    }
                }).done(data => {
                    if (data.success){
                        console.log($(".alert"));
                        $(".alert").empty().append(data.message).show()
                        $(`table#clients tr[data-user-id=${user_id}]`).remove();
                        setTimeout(() => {
                            $(".alert").hide();

                        }, 2000);
                        $("#get-clients").click();
                    }

                })
            })
            $("button.unban-user").click((e) => {
                let btn = $(e.target);
                let user_id = btn.attr("data-user-id");
                let url = "unban_user";
                console.log(user_id);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "user_id": user_id
                    }
                }).done(data => {
                    if (data.success){
                        $(".alert").empty().append(data.message).show()
                        $(`table#clients tr[data-user-id=${user_id}]`).remove();
                        setTimeout(() => {
                            $(".alert").hide();

                        }, 2000);
                        $("#get-clients").click();
                    }

                })
            })
        })

    })


    $("#get-transporters").click((e) => {
        let url = 'admin_transporters'
        $.get(url, function (data) {
            $("#content").html(data);
        })
    })

    // default view
    $("#get-clients").click();
});

