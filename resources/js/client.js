$("#nav-item-new-announcement").click(function () {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#new-announcement").offset().top
    }, 500);
})
$("#nav-item-news").click(function () {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#news").offset().top
    }, 500);
})

$("#nav-item-announcements").click(function () {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#announcements").offset().top
    }, 500);
})
$("#nav-item-stats").click(function () {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#stats").offset().top
    }, 500);
})

$("#nav-item-contact").click(function () {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#contact").offset().top
    }, 500);
})

$("html").niceScroll();


// ====================== ADD NEW ANNOUNCEMENT ==========================
$("#newAnnouncementForm").submit((e) => {
    e.preventDefault();
    let url = "new_announcement";
    $.ajax({
        type: "POST",
        url: url,
        data: {
            "start_point": $("#start_point option:selected").val(),
            "end_point": $("#end_point option:selected").val(),
            "type": Number.parseInt($("#type option:selected").val()),
            "weight": Number.parseFloat($("#weight option:selected").val()),
            "volume": Number.parseFloat($("#volume").val()),
            "way": Number.parseInt($("#way option:selected").val()),
            "message": $("#message").val()
        }
    }).then((data) => {
        console.log(data);
        if (data.added === true){
            console.log(data);
            $("#added-success-alert").show();
            setTimeout(() => {
                $("#added-success-alert").hide();
            }, 2000)
        }
    });

});

// =========================== Search announcement ============================

$("#searchAnnouncementsClientForm").submit((e) => {
    e.preventDefault();
    let url = "client";
    $("#loading").show();
    $.ajax({
        type: "POST",
        url: url,
        data: {
            "start_point": $("#client_start_point option:selected").val(),
            "end_point": $("#client_end_point option:selected").val()
        }
    }).then((data) => {
        $("#loading").hide();
        if (data.success){
            $("#result-not-found").hide();
            $("#row1").empty();
            if (data.announcements.length > 0){
                for (let i = 0; i <= data.announcements.length; i++){
                    $("#row1").append(
                        $("<div class='col-md-3 mb-4'></div>")
                            .append(
                                $("<div class='card'></div>")
                                    .append($('<img src="https://images.unsplash.com/photo-1477862096227-3a1bb3b08330?ixlib=rb-1.2.1&auto=format&fit=crop&w=700&q=60" alt="" class="card-img-top>'))
                                    .append($('<div class="card-body"></div>')
                                        .append($(`<h5 class="card-title">TITLE</h5>`))
                                        .append($(`<div><b>Start point</b> ${data.announcements[i].start_point} </div>`))
                                        .append($(`<div><b>End point</b> ${data.announcements[i].end_point} </div>`))
                                        .append($(`<div><b>Type</b> ${data.announcements[i].type} </div>`))
                                        .append($(`<div><b>Weight</b> ${data.announcements[i].weight} </div>`))
                                        .append($(`<div><b>Volume</b> ${data.announcements[i].volume} </div>`))
                                        .append($(`<a href=details?id=${data.announcements[i].id} class="btn btn-outline-success btn-sm m-5">Read More</a>`))
                                    )
                            )
                    );
                }
            }else {
                $("#result-not-found").show();
            }
        }
        else{
        }
    });
    console.log(url);
});