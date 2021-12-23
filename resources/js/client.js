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