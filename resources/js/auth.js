
$(document).ready(() => {
    let clientCheck = $("#client-check");
    let transporterCheck = $("#transporter-check");
    if (clientCheck.is(":checked")){
        $("#show-wilayas").hide();
    }else{
        $("#show-wilayas").show();
    }
    clientCheck.click(() => {
        console.log("client");
        $("#show-wilayas").hide();
    });
    transporterCheck.click(() => {
        console.log("transporter");
        $("#show-wilayas").show();
    });
})
