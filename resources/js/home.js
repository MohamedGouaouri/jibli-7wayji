
$("html").niceScroll();
$("#nav-item-presentation").click(function () {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#presentation").offset().top
    }, 500);
})
$("#nav-item-news").click(function () {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#news").offset().top
    }, 500);
})

$("#nav-item-annonces").click(function () {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#annonces").offset().top
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


// Scroll to top button
$("#scrollTopBtn").hide();
$("#scrollTopBtn").click(() => {
    $([document.documentElement, document.body]).animate({
        scrollTop: 0
    }, 500);
})
$(window).scroll(() => {
    let scroll = $(document).scrollTop();
    let breakPoint = 100;
    if (scroll > breakPoint){
        $("#scrollTopBtn").show();
    }else{
        $("#scrollTopBtn").hide();
    }
})