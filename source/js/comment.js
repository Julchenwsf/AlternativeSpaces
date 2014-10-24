function addComment(where, parent) {
    var $el = $('.commentButton');
    if($el.length) cancelAdd($el);

    if(!where) $el = $('#commentArea');
    else $el = $(where).closest('.commentBox');

    if(!parent) parent=0;

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
    <div><input type="button" class="submitButton commentButton" value="Send" onclick="addSubmit(this,'+parent+')" /> or <a href="" onclick="cancelAdd(this);return false">cancel</a></div>\
    \
    </div> </div></div>';

    $el.append(comment);
    $(window).scrollTop($('.commentButton').offset().top);
}

function cancelAdd(el) {
    $(el).closest('.commentBox').remove();
}

function addSubmit(el, parent) {
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