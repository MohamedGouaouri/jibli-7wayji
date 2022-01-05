
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
        xhr: function () {
            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 2) {
                    if (xhr.status === 200) {
                        xhr.responseType = "blob";
                    } else {
                        xhr.responseType = "text";
                    }
                }
            };
            return xhr;
        },

    }).done(function (data) {
        if (!data.error){
            //Convert the Byte Data to BLOB object.
            let blob = new Blob([data.blob], { type: "application/octetstream" });
            //Check the Browser type and download the File.
            let isIE = !!document.documentMode;
            let link;
            if (isIE) {
                window.navigator.msSaveBlob(blob, "fileName");
            } else {
                let url = window.URL || window.webkitURL;
                link = url.createObjectURL(blob);
                let a = $("<a />");
                a.attr("download", "fileName");
                a.attr("href", link);
                $("body").append(a);
                a[0].click();
                $("body").remove(a);
            }

            certSuccessElement.append("Votre demande de certification a ete envoye, on vous envoi la liste des documents a ...");
            certSuccessElement.show();
        }{
            // An error
            certErrorElement.append("Une erreur s'est produit lors de la demande, ca peut etre du que vous avez deja effectuer une demande");
            certErrorElement.show();
        }
    });
})
