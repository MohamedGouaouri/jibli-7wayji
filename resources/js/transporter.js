
// Get certified button
$("#cert-btn").click(() => {
    let url = "certification";
    let certSuccessElement = $("#cert-success-alert");
    let certErrorElement = $("#cert-error-alert");
    certSuccessElement.hide();
    certErrorElement.hide();

    $.ajax({
        url: url,
        cache: false,
    }).done(function (data) {

        if (!data.error){
            // //Convert the Byte Data to BLOB object.
            // let blob = new Blob([data.blob], { type: "application/octetstream" });
            // //Check the Browser type and download the File.
            // let isIE = !!document.documentMode;
            // let link;
            // if (isIE) {
            //     window.navigator.msSaveBlob(blob, "fileName");
            // } else {
            //     let url = window.URL || window.webkitURL;
            //     link = url.createObjectURL(blob);
            //     let a = $("<a />");
            //     a.attr("download", "fileName");
            //     a.attr("href", link);
            //     $("body").append(a);
            //     a[0].click();
            //     $("body").remove(a);
            // }

            certSuccessElement.append(data.message);
            certSuccessElement.show();
        }else{
            // An error
            certErrorElement.append(data.message);
            certErrorElement.show();
        }
    });
})


// ============================= Finish delivery ============================
$("#running button").click((e) => {
    let btn = $(e.target);
    let url = "finish";
    console.log(Number.parseInt(btn.attr("data-transporter-id")));
    $.ajax({
        url: url,
        type: "POST",
        data: {
            "transporter_id": Number.parseInt(btn.attr("data-transporter-id")),
            "announcement_id": Number.parseInt(btn.attr("data-announcement-id"))
        }
    }).done(data => {
        $("#finish-success-alert").show();
        $("#finish-success-alert").append("On vous remercie")
        $(`#running tr[data-row-index=${btn.attr("data-row-index")}]`).remove();
    })
})

$("#trajectory button#add-wilaya").click((e) => {
    let btn = $(e.target);
    let url = "update_add";
    $.ajax({
        url: url,
        type: "POST",
        data: {
            "wilaya_id": Number.parseInt(btn.attr("data-wilaya-id"))
        }
    }).done(data => {
        console.log(data);
        if (data.success){
            btn.prop("disabled", true);
            btn.next().prop("disabled", false);
        }
    })

})
$("#trajectory button[id='delete-wilaya']").click((e) => {
    let btn = $(e.target);
    let url = "update_delete";
    $.ajax({
        url: url,
        type: "POST",
        data: {
            "wilaya_id": Number.parseInt(btn.attr("data-wilaya-id"))
        }
    }).done(data => {
        console.log(data);
        if (data.success){
            btn.prop("disabled", true);
            btn.prev().prop("disabled", false);
        }
    })
})


$("#accept-demand-btn").click((e) => {
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
                    .append($("<div>Vous avez accepter la transaction avec ce transporteur certfie donc rix un pourcentage de 20% sera retranche</div>"))
                    .append($(`<div><b>Le nom du transporteur: </b> ${data.transaction.transporter.family_name} ${data.transaction.transporter.name}</div>`))
                    .append($(`<div><b>Son numero de telephone: </b> ${data.transaction.transporter.phone_number}</div>`))
                    .append($(`<div><b>L'email du transportue: </b> ${data.transaction.transporter.email}</div>`))
                ;

            }

            // TODO: Remove entry from the table
        }else {
            console.log("Error");
            $("#modal-logo").attr("src", "resources/assets/img/error.png")
            $(".modal-body").append($("<div>Vous avez deja accepte accepter la demande</div>"))
        }
    })
});
$("#refuse-demand-btn").click((e) => {

})

// Update profile
$("#enable-update-profile").click((e) => {
    $(".updated").attr("readonly", false);
    $("#submit-update-profile").show();
})
$("#cancel-update-profile").click((e) => {
    $(".updated").attr("readonly", true);
    $("#submit-update-profile").hide();
    $("#cancel-update-profile").hide();
})
$("#submit-update-profile").click((e) => {

    let url = "update_profile";
    $.ajax({
        type: "POST",
        url: url,
        data: {
            "email": $("#email-field").val(),
            "phone_number": $("#phone-field").val(),
            "address": $("#address-field").val()
        }
    }).done(data => {
        console.log(data);
        if (data.success){
            $(".alert-success").empty().append(data.message).show();
            setTimeout(() => {
                $(".alert-success").hide();
            }, 2000);
            // location.reload();
        }else{
            $(".alert-danger").empty().append(data.message).show();
            setTimeout(() => {
                $(".alert-danger").hide();
            }, 2000);
        }
        $(".updated").attr("readonly", true);
        $("#submit-update-profile").hide();
        $("#cancel-update-profile").hide();
    })

})


// transporter make signals
$("#signal-form").submit(e => {
    e.preventDefault();
    let url = new URLSearchParams(window.location.search);
    //
    let client_id = Number.parseInt(url.get("client_id"));
    let message = $("#message").val();
    $.ajax({
        type: "POST",
        url: "transporter_signals",
        data: {
            "client_id": client_id,
            "message": message
        }
    }).done(data => {
        console.log(data);
        if (data.success){
            $(".alert-success").empty().append(data.message).show();
            setTimeout(() => {
                $(".alert-success").hide();
            }, 2000);
        }else{
            $(".alert-danger").empty().append(data.message).show();
            setTimeout(() => {
                $(".alert-danger").hide();
            }, 2000);
        }
    })
})