
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