$(document).ready(function (e) {
    loadComments();
    $("#challengeComment").submit(function (event) {
        event.preventDefault();
        var data = {
            challenge_id: $("#challenge_id").val(),
            comment: $("#comment").val()
        }
        $("#comment").val("");
        $.ajax({
            type: "POST",
            url: "/design/challenge/addComment",
            data: data,
            success: function (response) {
                response = JSON.parse(response);
                if (response.success) {
                    loadComments();
                } else {
                    var messages = "";
                    for (var i in response.messages) {
                        for (var j in response.messages[i]) {
                            messages += response.messages[i][j] + "\n";
                        }
                    }
                    alert(messages);
                }

            }
        });

        return false;
    });
});
function showComment(comment) {
    if ($("#comments ul").length == 0) {
        $("#comments").html("");
        $("#comments").append(
            document.createElement("ul")
        )
    }
    var date = $(document.createElement("time"))
        .attr("datetime", comment.dateAdded.date)
        .text(comment.dateAdded.date);
    var user = $(document.createElement("a"))
        .attr("href", "/profile/" + comment.user.id)
        .text((comment.user.displayName == "") ? "Unknown" : comment.user.displayName);

    $("#comments ul").prepend(
        $(document.createElement("li"))
            .append($(document.createElement("p")).text(comment.comment))
            .append(date)
            .append(user)
    )
}
function loadComments() {
    $.ajax({
        type: "POST",
        url: "/design/challenge/getComments",
        data: {challenge_id: $("#challenge_id").val()},
        success: function (response) {
            response = JSON.parse(response);
            if (response.success) {
                for (var i in response.comments) {
                    showComment(response.comments[i]);
                }
            } else {
                $("#comments").html(response.message);
            }
        }
    });
}