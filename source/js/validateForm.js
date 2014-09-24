function validateForm() {
    var a=document.forms["reg"]["fname"].value;
    var b=document.forms["reg"]["lname"].value;

    if (a==null || a=="") {
        reportError("First name must be filled out");
        return false;
    }

    if (b==null || b=="") {
        reportError("Last name must be filled out");
        return false;
    }
}

function reportError(error) {
    $("#errors").html('<div class="error">' + error + '</div>');
}