$(document).ready(function(){
    /* выпадающее меню в шапке*/
    $("#btn-exit").click(function () {
        $(this).toggleClass("btn-exit-active");
        $(".more-menu").toggleClass("more-menu-active");
        $("#show-notifications").html('');
        $("#notificationsCheck").removeClass("btn-exit-active");
        $("#notificationsCheck").one("click", handler1);
    });
    /* поиск */
    $("#search").keyup(function(){
        var searchText = $(this).val();
        if (searchText != '') {
            $.ajax({
                url: '/libs/search',
                method: 'post',
                data: {query:searchText},
                success: function(response) {
                    $("#show-list").html(response);
                }
            });
        }
        else {
            $("#show-list").html('');
        }
    });
    $("#search").click(function(){
        var searchText = $(this).val();
        if (searchText != '') {
            $.ajax({
                url: '/libs/search',
                method: 'post',
                data: {query:searchText},
                success: function(response) {
                    $("#show-list").html(response);
                }
            });
        }
        else {
            $("#show-list").html('');
        }
    });
    $(document).click(function() {
        $("#show-list").html('');
    });
    /* уведомления */
    function handler1() {
        $("#btn-exit").removeClass("btn-exit-active");
        $(".more-menu").removeClass("more-menu-active");
        $.ajax({
            url: '/libs/notifications',
            method: 'post',
            data: { do: 1},
            success: function(response) {
                $("#show-notifications").html(response);
                
                $(".notification__item").hover(function(){ 
                    $(this).removeClass("unread");
                    //alert($(this).data("id"));
                    var id = $(this).data("id");
                    $.ajax({
                        url: '/libs/notifications',
                        method: 'post',
                        data: {do: 2, id: id},
                    });
                });
            }
        });
        $(this).addClass("btn-exit-active");
        $( ".badge" ).hide();
        $(this).one("click", handler2);
    }

    function handler2() {
        $("#show-notifications").html('');
        $(this).removeClass("btn-exit-active");
        $(this).one("click", handler1);
    }
    $("#notificationsCheck").one("click", handler1);
  
  	/* смена темы оформления*/
    $("#switch").click(function(){
        var link = document.getElementById("theme");
        let lightTheme = location.protocol + "//" + location.hostname + "/css/light.css";
        let darkTheme = location.protocol + "//" + location.hostname + "/css/dark.css";
        
        var currTheme = link.getAttribute("href");
        var theme = "";

        if (currTheme == lightTheme) {
            currTheme = darkTheme;
            theme = "dark";
        } else {    
            currTheme = lightTheme;
            theme = "light";
        }

        link.setAttribute("href", currTheme);

        $.ajax({
            url: '/libs/settings',
            method: 'get',
            data: {theme: theme},
        });
        
    });
});