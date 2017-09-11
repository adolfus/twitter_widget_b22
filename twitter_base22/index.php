<?php

?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Twitter challenge / Base22</title>
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/handlebars.runtime-v4.0.10.js"></script>
        <script src="js/templates/templates.js"></script>
        <link href="css/twttr_wid.css" rel="stylesheet">
    </head>
    <body>
    <div class="tl-widget tl-widget--edge">
        
    </div>
    <script>
        $( document ).ready(function() {
            var context = {  
                title:"Tweets",
                text:"Follow @Adolf_me",
                screen_name:"Adolf_me",
                placeholder:"Tweet to @Adolf_me"
            };
            var html = Handlebars.templates.widgetWindow(context,{});
            $('.tl-widget').html(html);
            $.get( "http://localhost:8083/slim_skele/public/user/Adolf_me/timeline", function( data ) {
                var datos = {
                    tweets : data
                };
                console.log(datos);
                var html = Handlebars.templates.tweetListItem(datos,{});
                $('.tl-tweetlist').html(html);
            });
            $('#tweet-txt').keyup(function(e){
                if(e.keyCode == 13)
                {
                    var tweet = {
                        "twitter-text": $(this).val()
                    };console.log(tweet);
                    $.ajax({
                        url: 'http://localhost:8083/slim_skele/public/tweet',
                        type: 'post',
                        dataType: 'json',
                        success: function (data) {
                            console.log(data.message);
                            $('#tweet-txt').val('');
                        },
                        data: tweet
                    });
                }
            });
        });
    </script>
    </body>
</html>