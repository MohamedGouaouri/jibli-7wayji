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