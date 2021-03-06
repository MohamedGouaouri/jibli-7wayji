
$("html").niceScroll();

$("#nav-item-annonces").click(function () {
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#annonces").offset().top
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
            $("#row1").empty();
            if (data.announcements){
                for (let i = 0; i <= data.announcements.length; i++){
                    $("#row1").append(
                        $("<div class='col-md-3 mb-4'></div>")
                            .append(
                                $("<div class='card'></div>")
                                    .append($('<img src="https://images.unsplash.com/photo-1477862096227-3a1bb3b08330?ixlib=rb-1.2.1&auto=format&fit=crop&w=700&q=60" alt="" class="card-img-top>'))
                                    .append($('<div class="card-body"></div>')
                                        .append($(`<h5 class="card-title">TITLE</h5>`))
                                        .append($(`<div><b>Start point</b> ${data.announcements[i].start_point.name} </div>`))
                                        .append($(`<div><b>End point</b> ${data.announcements[i].end_point.name} </div>`))
                                        .append($(`<div><b>Type</b> ${data.announcements[i].type} </div>`))
                                        .append($(`<div><b>Weight</b> ${data.announcements[i].weight} </div>`))
                                        .append($(`<div><b>Volume</b> ${data.announcements[i].volume} </div>`))
                                        .append($(`<a href="details?id=${data.announcements[i].id}" class="btn btn-outline-success btn-sm m-5">Read More</a>`))
                                    )
                            )

                    );
                }
            }else {
                $("#result-not-found").empty();
                $("#result-not-found").append(
                    $("<div class='m-5'>0 Results found</div>")
                );
            }
        }
        else{

        }
    });
});

// ================================ How it works button =======================
$("#how-it-works a").click((e) => {
    console.log("hello")
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#presentation").offset().top
    }, 500);
})


// ====================== ADD NEW ANNOUNCEMENT ==========================

const createTransporterCard = (name, familyName, email, phoneNumber) => {
    return $("<div class='col-md-3 mb-4'></div>")
        .append(
            $("<div class='card'></div>")
                .append($('<div class="card-body"></div>')
                    .append($(`<h5 class="card-title">TITLE</h5>`))
                    .append($(`<div><b class="mr-2">Name: </b> ${name} </div>`))
                    .append($(`<div><b class="mr-2">Family name: </b> ${familyName} </div>`))
                    .append($(`<div><b class="mr-2">Email: </b> ${email} </div>`))
                    .append($(`<div><b class="mr-2">Phone number</b> ${phoneNumber} </div>`))
                    .append($(`<a href="#" class="btn btn-outline-primary btn-sm m-5">Demander</a>`))
                )
        )
}

$("#newAnnouncementForm").submit((e) => {
    e.preventDefault();
    let url = "new_announcement";
    let fd = new FormData();
    let files = $("#image")[0].files;
    if (files.length > 0){
        fd.append('announcement_image', files[0]);
    }
    fd.append("start_point", $("#add_start_point option:selected").val());
    fd.append("end_point", $("#add_end_point option:selected").val());
    fd.append("type", $("#type option:selected").text());
    fd.append("weight", Number.parseFloat($("#weight option:selected").val()));
    fd.append("volume", Number.parseFloat($("#volume").val()));
    fd.append("way", Number.parseInt($("#way option:selected").val()));
    fd.append("message", $("#message").val());

    $.ajax({
        type: "POST",
        url: url,
        data: fd,
        processData: false,
        contentType: false,
        success: (data) => {
            console.log(data);
            $("#added-success-alert").show();
            setTimeout(() => {
                $("#added-success-alert").hide();
            }, 2000)
        }
    }).then((data) => {

        // console.log(data);
        // if (data.added === true){
        //     console.log(data);
        //
        //     // let transporters = data.transporters;
        //     // for (let i = 0; i < transporters.length; i++) {
        //     //     let transporter = transporters[i];
        //     //     console.log(transporter);
        //     //     $("#transporters-list").append(createTransporterCard(transporter.name, transporter.family_name, transporter.email, transporter.phone_number));
        //     //     // $("#transporters-list").append("hello");
        //     // }
        // }
    });

});