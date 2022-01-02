
$("#apply-btn").click(() => {
    let url = "apply";
    console.log($("#announcement-id").val());
    $.ajax({
        type: "POST",
        url: url,
        data: {
            "id": $("#announcement-id").val()
        }
    }).done(data => {
        console.log(data);
        if (data.success){
            $("#application-success").show();
            $("#application-success").append(data.message);
            setTimeout(() => {
                $("#application-success").hide();
            }, 2000);
        }else{
            $("#application-error").show();
            $("#application-error").append(data.message);
            setTimeout(() => {
                $("#application-error").hide();
            },2000);
        }
    })
})