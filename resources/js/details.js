
$("#apply-btn").click(() => {
    let url = "apply";
    let announcement_id = Number.parseInt($("#announcement-id").val());
    let applicationSuccessElement = $("#application-success");
    let applicationErrorElement = $("#application-error");
    $.ajax({
        type: "POST",
        url: url,
        data: {
            "id": announcement_id
        }
    }).done(data => {
        console.log(data);
        if (data.success){
            applicationSuccessElement.show();
            applicationSuccessElement.append(data.message);
            setTimeout(() => {
                $("#application-success").hide();
            }, 2000);
        }else{
            applicationErrorElement.show();
            applicationErrorElement.append(data.message);
            setTimeout(() => {
                $("#application-error").hide();
            },2000);
        }
    })
})

// client demand for a transport
$("button.demand").click((e) => {
    let btn = $(e.target);
    let currentUrl = new URLSearchParams(window.location.search);
    let announcement_id = Number.parseInt(currentUrl.get("id"));
    let transporter_id = Number.parseInt(btn.attr("data-transporter-id"));
    let url = "demand";

    $.ajax({
        type: "POST",
        url: url,
        data: {
            "announcement_id": announcement_id,
            "transporter_id": transporter_id
        }
    }).done(data => {
        console.log(data);
        if (data.success){

            $("#application-success").empty().append(data.message).show();
            setTimeout(() => {
                $("#application-success").hide();
            }, 2000);
        }else{

            $("#application-error").empty().append(data.message).show();
            setTimeout(() => {
                $("#application-error").hide();
            }, 2000);
        }
    })

});