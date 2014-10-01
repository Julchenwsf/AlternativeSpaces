function addComment(where, parent) {
    /*	This functions gets called from both the "Add a comment" button
     on the bottom of the page, and the add a reply link.
     It shows the comment submition form */

    var $el;
    if($('.waveButton').length) return false;
    // If there already is a comment submition form
    // shown on the page, return and exit

    if(!where)
        $el = $('#commentArea');
    else
        $el = $(where).closest('.commentBox');

    if(!parent) parent=0;

    // If we are adding a comment, but there are hidden comments by the slider:
    $('.waveComment').show('slow');

    // Move the slider to the end point and show all comments
    var comment = '<div class="commentBox addComment">\
    \
    <div class="comment">\
    <div class="commentAvatar">\
    <img src="img/design/defaultProfileIcon.png" width="30" height="30" />\
    </div>\
    \
    <div class="commentText">\
    \
    <textarea class="textArea" rows="2" cols="70" name="" />\
    <div><input type="button" class="waveButton" value="Add comment" onclick="addSubmit(this,'+parent+')" /> or <a href="" onclick="cancelAdd(this);return false">cancel</a></div>\
    \
    </div>\
    </div>\
    \
    </div>';

    $el.append(comment);
    // Append the form
}

function cancelAdd(el) {
    $(el).closest('.commentBox').remove();
}

function addSubmit(el, parent) {
    /* Executed when clicking the submit button */
    var cText = $(el).closest('.commentText');
    var text = cText.find('textarea').val();
    var wC = $(el).closest('.commentBox');

    if(text.length < 4) {
        alert("Your comment is too short!");
        return false;
    }

    $.ajax({
        type: "POST",
        url: "backend/forms/commentform.php",
        data: "comment=" + encodeURIComponent(text) + "&parent=" + parent + "&thread=" + $("#commentArea").attr("data-thread-id"),
        /* Sending both the text and the parent of the comment */
        success: function(msg){
            wC.replaceWith(msg);
        }
    });
}