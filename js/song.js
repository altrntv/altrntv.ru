$(document).ready(function() {
    //лайки
    $(".good").bind("click", function() {
        var link = $(this);
        var id = link.data("id");
        var like = link.data("like");
        $.ajax({
            url: "../libs/song", 
            type: "POST",
            data: {id:id, like:like},
            dataType: "json", 
            success: function(result) {
                if (!result.error){
                    location.reload();
                }else{
                    alert(result.message);
                }
            }
        });
    });

    //отправка обзоров
    $(".button").bind("click", function() {
        var id = $(".button").data("id");
        var text = $(".textarea").val();
        if (text == "") { text = "NULL" }
        var score = $(".score:checked").data("score");
        $.ajax({
            url: "../libs/song", 
            method: "POST",
            data: {id:id, score:score, text:text},
            dataType: "json", 
            success: function(result) {
                if (!result.error){
                    location.reload();
                } else {
                    alert(result.message);
                }
            }
        });
    });

    //удаление обзоров
    $(".delete-score").bind("click", function() {
        var link = $(this);
        var id = link.data("id");
        var review = link.data("review");
        $.ajax({
            url: "../libs/song", 
            method: "POST",
            data: {id:id, review:review},
            dataType: "json", 
            success: function(result) {
                if (!result.error){
                    location.reload();
                } else {
                    alert(result.message);
                }
            }
        });
    });
});