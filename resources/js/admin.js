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

    // clients subpage

    $("#get-clients").click((e) => {
        let url = 'admin_clients'
        $.get(url, function (data) {
            $("#content").html(data);
            // ban user
            // let clientsTable = $("#clients-table")
            // clientsTable.DataTable()
            $("table").DataTable();
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
                        // $(`table#clients tr[data-user-id=${user_id}]`).remove();
                        setTimeout(() => {
                            $(".alert").hide();

                        }, 2000);
                        $("#get-clients").click();
                    }

                })
            })
        })

    })


    // transporters page
    $("#get-transporters").click((e) => {
        let url = 'admin_transporters'
        $.get(url, function (data) {
            $("#content").html(data);
            $("table").DataTable();
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
                        // $(`table#clients tr[data-user-id=${transporter_id}]`).remove();
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
            $("table").DataTable();
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
                        // $(`table#clients tr[data-user-id=${user_id}]`).remove();
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
            $("table").DataTable();
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
            }) // end validate ad
            $("button.delete").click((e) => {
                let btn = $(e.target);
                let url = "admin_delete_announcement";
                let announcement_id = btn.attr("data-announcement-id");
                $.ajax({
                    type: "POST",
                    url: url,
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

    // get signals
    $("#get-signals").click(() => {
        let url = "signals";
        $.get(url, (data) => {
            // set html content
            $("#content").html(data);
        })
    })

    // get pricing view
    $("#get-pricing").click((e) => {
        let url = "admin_pricing";
        $.get(url, (data) => {
            $("#content").html(data);
            // process
            $("table").DataTable();
            $("#pricing-form").submit((e) => {
                let url = "update_pricing"
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "start_point": $("#price_start_point option:selected").val(),
                        "end_point": $("#price_end_point option:selected").val(),
                        "price": Number.parseFloat($("#price").val())
                    }
                }).done((data) =>{
                    console.log(data);
                    $(".alert").empty().append(data.message).show()
                    setTimeout(() => {
                        $(".alert").hide();

                    }, 2000);
                    $("#get-pricing").click();

                } )
            })
        })
    })

    // get analytics view
    $("#get-analytics").click((e) => {
        let url = "admin_analytics";
        $.get(url, (content) => {
            $("#content").html(content);
            $.ajax({
                type: "GET",
                url: "admin_analytics_api"
            }).done(server_data => {
                console.log(server_data)
                const user_types_data = {
                    labels: ['Users', 'Transporters', 'Banned users'],
                    datasets: [{
                        label: '# Users',
                        data: [server_data["users"]["nb_users"], server_data["users"]["nb_transporters"], server_data["users"]["nb_banned"]],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                        ],
                        borderWidth: 1
                    }]
                };
                const ctx1 = document.getElementById('myChart1').getContext('2d');
                new Chart(ctx1, {
                    type: 'bar',
                    data: user_types_data,
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                const announcements_data = {
                    labels: ['Validated ads', 'Archived ads', 'Non validated ads'],
                    datasets: [{
                        label: 'announcements',
                        data: [server_data["announcements"]["nb_validated"], server_data["announcements"]["nb_archived"], server_data["announcements"]["nb_non_validated"]],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                        ],
                        borderWidth: 1
                    }]
                };

                const ctx2 = document.getElementById('myChart2').getContext('2d');
                new Chart(ctx2, {
                    type: "doughnut",
                    data: announcements_data
                });
            })

        })
    })

    // news view
    $("#get-news").click((e) => {
        let url = "admin_news";
        $.get(url, (content) => {
            $("#content").html(content);
            // delete news
            $("button.delete").click((e) => {
                let btn = $(e.target);
                let url = "admin_delete_news";
                let id = Number.parseInt(btn.attr("data-news-id"));
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        "id": id
                    }
                }).done(data => {
                    console.log(data);
                    if (data.success){
                        $(".alert-success").empty().append(data.message).show();
                        setTimeout(() => {
                            $(".alert-success").hide();

                        }, 2000);
                        $("#get-news").click();
                    }
                    else{
                        $(".alert-danger").empty().append(data.message).show();
                        setTimeout(() => {
                            $(".alert-success").hide();

                        }, 2000);
                    }
                })
            })

            // add news
            $("#add-news-form").submit((e) => {
                e.preventDefault();
                $.ajax({
                    url: "admin_add_news",
                    type: "POST",
                    data: {
                        "title": $("#news-title").val(),
                        "synopsis": $("#news-synopsis").val(),
                        "content": $("#news-content").val()
                    }
                }).done(data => {
                    console.log(data);
                    if (data.success){
                        $(".alert").empty().append(data.message).show()
                        setTimeout(() => {
                            $(".alert").hide();

                        }, 2000);

                    }
                });
                $("get-news").click();

            })

        });
    })
    // default view
    $("#get-clients").click();
});

