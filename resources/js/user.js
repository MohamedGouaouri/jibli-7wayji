// =========================== Search announcement ============================

$("#searchAnnouncementsClientForm").submit((e) => {
    e.preventDefault();
    let url = "search";
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

// delete a non-confirmed announcement
$(".delete").click((e) => {
    let btn = e.target;
    let announcement_id = $(btn).attr("data-id");
    let url = "delete_announcement";
    $.ajax({
        url: url,
        type: "POST",
        data: {
            "announcement_id": announcement_id
        }
    }).done(data => {
        console.log(data);
        if (data.success){
            $("#deleted-success-alert").append(data.message);
            $("#deleted-success-alert").show();
            setTimeout(() => {
                $("#deleted-success-alert").hide();
            }, 2000);

            // delete row
            $(`#announcements-table tr[data-row-index=${$(btn).attr("data-row-index")}]`).remove();

        }else{
            $("#deleted-error-alert").append(data.message);
            $("#deleted-error-alert").show();
            setTimeout(() => {
                $("#deleted-error-alert").hide();
            }, 2000);
        }
    })
})


// ============================ ACCEPT OR REJECT Transporter application =============================
$("#accept-application-btn").click((e) => {
    let btn = e.target;
    console.log($(btn).attr("data-announcement-id"));
    let announcementId = Number.parseInt($(btn).attr("data-announcement-id"));
    let transporterId = Number.parseInt($(btn).attr("data-transporter-id"));

    $.ajax({
        type: "POST",
        url: "accept_application",
        data: {
            "announcement_id": announcementId,
            "transporter_id": transporterId
        }
    }).done((data) => {
        console.log(data);
        $(".modal-body").empty();
        if (data.success){
            let transporter = data.transaction.transporter;

            if (!transporter.certified){

                $(".modal-body")
                    .append($("<div>Vous avez accepter la transaction avec ce transporteur non certie donc vous devez vous entdre sur un prix, on recommende le prix propose par la plateforme</div>"))
                    .append($(`<div><b>Le nom du transporteur: </b> ${data.transaction.transporter.family_name} ${data.transaction.transporter.name}</div>`))
                    .append($(`<div><b>Son numero de telephone: </b> ${data.transaction.transporter.phone_number}</div>`))
                    .append($(`<div><b>L'email du transportue: </b> ${data.transaction.transporter.email}</div>`))
                ;
            }
            else {
                    $(".modal-body")
                        .append($("<div>Vous avez accepter la transaction avec ce transporteur certfie donc rix un pourcentage de X% sera retranche</div>"))
                        .append($(`<div><b>Le nom du transporteur: </b> ${data.transaction.transporter.family_name} ${data.transaction.transporter.name}</div>`))
                        .append($(`<div><b>Son numero de telephone: </b> ${data.transaction.transporter.phone_number}</div>`))
                        .append($(`<div><b>L'email du transportue: </b> ${data.transaction.transporter.email}</div>`))
                    ;

            }

            // TODO: Remove entry from the table
        }else {
            console.log("Error");
            $("#modal-logo").attr("src", "resources/assets/img/error.png")
            $(".modal-body").append($("<div>Vous avez deja accepte accepter l'application de ce transporteur</div>"))
        }
    })
})


// ============================= Refuse transporter application ==============================
$("#refuse-application-btn").click((e) => {
    let btn = e.target;
    let announcementId = Number.parseInt($(btn).attr("data-announcement-id"));
    let transporterId = Number.parseInt($(btn).attr("data-transporter-id"));
    $.ajax({
        type: "POST",
        url: "refuse_application",
        data: {
            "announcement_id": announcementId,
            "transporter_id": transporterId
        }
    }).done(data => {
        console.log(data);
        if (data.success) {
            $("#refused-success-alert").append(data.message);
        }else{
            $("#refused-error-alert").append(data.message)
        }
    })
})