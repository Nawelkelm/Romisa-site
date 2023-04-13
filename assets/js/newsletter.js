function doSubscribe() {
    var subscriberEmail = document.getElementById("subscriberEmail").value;
     
    var ajax = new XMLHttpRequest();
    ajax.open("POST", "newsletter.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
     
    ajax.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("subscribe-message").innerHTML = "Gracias por suscribirse.";
        }
    };

    ajax.send("subscriberEmail=" + subscriberEmail);
    return false;
}