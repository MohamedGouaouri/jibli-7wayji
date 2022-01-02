
// Get certified button
$("#cert-btn").click(() => {
    let url = "certification";
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
        success: function (data) {
            //Convert the Byte Data to BLOB object.
            let blob = new Blob([data], { type: "application/octetstream" });

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
        }
    });
})
