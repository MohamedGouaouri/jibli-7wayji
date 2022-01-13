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
            $("a.validate").click((e) => {
                // Handle transporter validation event
                let btn = $(e.target);
                let url = "validate_transporter";
                let transporter_id = btn.attr("data-transporter-id");
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "transporter_id": transporter_id
                    }
                }).done(data => {
                    console.log(data)
                    if (data.success){
                        $(".alert").empty().append(data.message).show()
                        setTimeout(() => {
                            $(".alert").hide();

                        }, 2000);
                        $("#get-transporters").click()
                    }
                })
            }) // End validation handling

            $(".ban-transporter").click((e) => {
                let btn = $(e.target);
                let transporter_id = btn.attr("data-transporter-id");
                let url = "ban_user";
                console.log(transporter_id);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "user_id": transporter_id
                    }
                }).done(data => {
                    if (data.success){
                        $(".alert").empty().append(data.message).show()
                        $(`table#clients tr[data-user-id=${transporter_id}]`).remove();
                        setTimeout(() => {
                            $(".alert").hide();

                        }, 2000);
                        $("#get-transporters").click();
                    }

                })
            })

        })
    })

    // Fetch banned users content
    $("#get-banned").click((e) => {
        let url = "banned_users";
        $.get(url, function (data) {
            $("#content").html(data);
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
                        $("#get-banned").click();
                    }
                })
            })
        });
    });


    // Fetch announcements
    $("#get-announcements").click((e) => {
        let url = "admin_announcements";
        $.get(url, function (data) {
            $("#content").html(data);
            $("button.validate").click((e) => {
                let btn = $(e.target);
                let url = "validate_announcement";
                let announcement_id = btn.attr("data-announcement-id");
                $.ajax({
                    type: "POST",
                    url:url,
                    data: {
                        "announcement_id": announcement_id
                    }
                }).done(data => {
                    if (data.success){
                        $(".alert").empty().append(data.message).show()
                        setTimeout(() => {
                            $(".alert").hide();

                        }, 2000);
                        $("#get-announcements").click();
                    }
                })
            })
        })
    });

    // default view
    $("#get-clients").click();
});

