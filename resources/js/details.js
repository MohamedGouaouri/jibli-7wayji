
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