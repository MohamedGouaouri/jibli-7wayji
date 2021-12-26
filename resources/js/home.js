
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



// Search form
$("#searchAnnouncementsUserForm").submit((e) => {
    let card = "<div class=\"col-md-3 mb-4\">\n" +
        "            <div class=\"card\">\n" +
        "              <img\n" +
        "                      src=\"https://images.unsplash.com/photo-1477862096227-3a1bb3b08330?ixlib=rb-1.2.1&auto=format&fit=crop&w=700&q=60\"\n" +
        "                      alt=\"\" class=\"card-img-top\">\n" +
        "              <div class=\"card-body\">\n" +
        "                <h5 class=\"card-title\">Sunset</h5>\n" +
        "                <p class=\"card-text\">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ut eum similique repellat a\n" +
        "                  laborum, rerum voluptates ipsam eos quo tempore iusto dolore modi dolorum in pariatur. Incidunt\n" +
        "                  repellendus praesentium quae!</p>\n" +
        "                <a href=\"\" class=\"btn btn-outline-success btn-sm\">Read More</a>\n" +
        "              </div>\n" +
        "            </div>\n" +
        "          </div>";

    e.preventDefault();
    let form = $(this);
    let url = "index.php";
    $("#loading").show();
    $.ajax({
        type: "POST",
        url: url,
        data: {
            "start_point": $("#start_point option:selected").val(),
            "end_point": $("#end_point option:selected").val()
        }
    }).then((data) => {
        $("#loading").hide();
        console.log(data);
        if (data.success){

            // console.log(data.announcements[0]);
            // $("#row1").append(
            //     $("<div class='col-md-3 mb-4'>0 Results found</div>")
            // );
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
                                        .append($('<a href="" class="btn btn-outline-success btn-sm m-5">Read More</a>'))
                                    )
                            )

                    );
                }
            }else {
                $("#result-not-found").append(
                    $("<div class='m-5'>0 Results found</div>")
                );
            }

        }
        else{

        }
    });
    console.log(url);
});

// ================================ How it works button =======================
$("#how-it-works a").click((e) => {
    console.log("hello")
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#presentation").offset().top
    }, 500);
})