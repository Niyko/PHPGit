<html>
    <head>
        <title></title>
        <style>
            @font-face {
                font-family: 'console';
                src: url('assets/console.ttf')  format('truetype');
            }
            
            body{
                background-color: #000000;
                font-family: console;
                color: #ffffff;
                margin: 0 0 0 0;
            }
            
            .console-input {
                color: #ffffff;
                background: none;
                font-family: console;
                font-size: 15px;
                border: none;
            }
            
            .console-msg p{
                font-size: 15px;
                margin: 6px;
            }
            
            .console-loading{
                color: #ffffff;
                display: none;
                font-size: 15px;
                margin: 6px;
                margin-left: 13px;
            }
            
            .console-active{
                background-color: rgba(255,255,255,0.3);
                padding-left: 5px;
            }
            
            .white{
                color: #ffffff;
            }
            
            .red{
                color: #ff0000;
            }
            
            .green {
                color: #00ff00;
            }
            
            .yellow {
                color: #ffff00;
            }
            
            .blue {
                color: #0000ff;
            }
            
            .logo{
                width: 190px;
                position: fixed;
                right: 40px;
                bottom: 40px;
                opacity: 0.3;
            }
            
            .light{
                opacity: 0.5;
            }
            
            .small{
                font-size: 11px;
            }
            
            .tab{
                padding-left: 30px;
            }
        </style>
    </head>
    <body onload="on_console_load();" onclick="$('#console-input').focusWithoutScrolling();">
        <div id="console-msg" class="console-msg">
            <pre>
        ____  __  ______  _______ __ 
       / __ \/ / / / __ \/ ____(_) /_
      / /_/ / /_/ / /_/ / / __/ / __/
     / ____/ __  / ____/ /_/ / / /_  
    /_/   /_/ /_/_/    \____/_/\__/  
            </pre>
        </div>
        <div class="console-active">
            <p class="console-loading" id="console-loading">&gt; Loading  |</p>
            <span>&gt; <input type="text" onkeydown="return on_console_input(event)" class="console-input" id="console-input"></span>
        </div>
        <img src="assets/phpgit.png" class="logo">
    </body>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
    /*
    git init [fld]
    git repo [repo address]
    git commit [msg]
    git email [email]
    git key [key]
    git push
    
    
    */
    
        var console_type_state = true;
        var console_state = "nothing";
        var command_head = "git";
        var console_loading_prase = "Loading";
        var cache_trace = 0;
        
        function on_console_load(){
            $('#console-input').focusWithoutScrolling();
            setInterval('animate_console_loading()', 100);
            int_console();
        }
        
        function int_console(){
            add_console_msg("white small light","WELCOME TO ://PHPGIT");
            add_console_msg("white small light","Released under MIT http://github.com/Niyko/PhpGit");
            add_console_msg("white small light","Type <span class='blue'>git help</span> for for info");
            add_console_msg("white small light","_____________________________________________________________");
            /*
            if(getCookie("githubrepo")!="" && getCookie("githubauth")!=""){
                add_console_msg("white","Intilsed github repo link is "+getCookie("githubrepo"));
                add_console_msg("white","Intilsed github auth key is "+hide_auth_key(getCookie("githubauth")));
                console_state = "get-push";
                add_console_msg("white","Enter the folder to pushed : ");
            }
            */
            $("#console-input").width(($("body").width()-50)+"px");
        }
        
        function on_console_input(e){
            if(console_type_state){
                if(e.keyCode == 38){
                    get_cache_back();
                }
                if(e.keyCode == 40){
                    get_cache_front();
                }
                else if(e.keyCode == 13){
                    add_console_msg("white light user-inputed",$("#console-input").val());
                    console_process();
                    $("#console-input").val('');
                    cache_trace = ($('.user-inputed').length-1);
                    return false;
                }
                else {
                    return true;
                }
            }
        }
        
        function console_process(){
            var console_msg_splited = $("#console-input").val().split(" ");
            if(console_msg_splited[1]=="clear"){
                $("#console-msg").html("");
            }
            if(console_msg_splited[1]=="help"){
                add_console_msg("white", "All git commands are listed below");
                add_console_msg("white", "");
                add_console_msg("white", "<span class='tab'></span>git init [DIR PATH]");
                add_console_msg("white", "<span class='tab'></span>git repo [GITHUB REPO .git PATH]");
                add_console_msg("white", "<span class='tab'></span>git email [GITHUB EMAIL]");
                add_console_msg("white", "<span class='tab'></span>git key [GITHUB AUTH KEY]");
                add_console_msg("white", "<span class='tab'></span>git commit [COMMIT MESSAGE]");
                add_console_msg("white", "<span class='tab'></span>git push");
                add_console_msg("white", "<span class='tab'></span>");
                add_console_msg("white", "<span class='tab'></span>git clear");
                add_console_msg("white", "<span class='tab'></span>git view");
                add_console_msg("white", "<span class='tab'></span>git default");
                add_console_msg("white", "<span class='tab'></span>git pass [PHPGIT PASSWORD]");
                add_console_msg("white", "<span class='tab'></span>");
            }
            else if(console_msg_splited[1]=="push"){
                if(getCookie("githubrepo")=="") add_console_msg("red","Repository address is not set");
                else if(getCookie("githubfld")=="") add_console_msg("red","Folder to push is not set");
                else if(getCookie("githubkey")=="") add_console_msg("red","Github auth key is not set");
                else if(getCookie("githubcommit")=="") add_console_msg("red","Commit message is not set");
                else if(getCookie("githubemail")=="") add_console_msg("red","Github email is not set");
                else {
                    show_console_loading("Pushing");
                    $.get( "api.php?push=true", function(data) {
                        if(data=="nopass"){
                            add_console_msg("red", "PHPGit password is not given. Set it with <span class='blue'>"+command_head+" pass [YOUR PHPGIT PASSWORD]</span>");
                            hide_console_loading();
                            console_type_state = true;
                        }
                        if(data=="wrongpass"){
                            add_console_msg("red", "PHPGit password is not matching, check your password and try again");
                            hide_console_loading();
                            console_type_state = true;
                        }
                        else {
                            add_console_msg("white", data);
                            hide_console_loading();
                            console_type_state = true;
                        }
                    });
                }
            }
            else if(console_msg_splited[1]=="view"){
                add_console_msg("white","Folder to push "+((getCookie("githubfld")=="")?"<span class='red'>not set</span>":getCookie("githubfld")));
                add_console_msg("white","Repository address "+((getCookie("githubrepo")=="")?"<span class='red'>not set</span>":getCookie("githubrepo")));
                add_console_msg("white","Github auth key "+((getCookie("githubkey")=="")?"<span class='red'>not set</span>":getCookie("githubkey")));
                add_console_msg("white","Github email "+((getCookie("githubemail")=="")?"<span class='red'>not set</span>":getCookie("githubemail")));
                add_console_msg("white","Commit message "+((getCookie("githubcommit")=="")?"<span class='red'>not set</span>":getCookie("githubcommit")));
            }
            else if(console_msg_splited[1]=="default"){
                setCookie("githubrepo", "default");
                setCookie("githubkey", "default");
                setCookie("githubcommit", "default");
                setCookie("githubemail", "default");
                add_console_msg("white","Repository address is set to default");
                add_console_msg("white","Github auth key is set to default");
                add_console_msg("white","Github email is set to default");
                add_console_msg("white","Commit message is set to default");
            }
            else if(console_msg_splited[1]=="init"){
                console_type_state = false;
                show_console_loading("Initializing");
                setCookie("githubfld", $("#console-input").val().split(command_head+" init ")[1]);
                $.get( "api.php?init=true&dir="+getCookie("githubfld"), function(data) {
                    if(data=="nopass"){
                            add_console_msg("red", "PHPGit password is not given. Set it with <span class='blue'>"+command_head+" pass [YOUR PHPGIT PASSWORD]</span>");
                            hide_console_loading();
                            console_type_state = true;
                    }
                    else if(data=="wrongpass"){
                        add_console_msg("red", "PHPGit password is not matching, check your password and try again");
                        hide_console_loading();
                        console_type_state = true;
                    }
                    else if(data=="true") add_console_msg("white","Initialized "+getCookie("githubfld")+" as Git repository");
                    else {
                        add_console_msg("red","Folder "+getCookie("githubfld")+" not found");
                        setCookie("githubfld", "");
                    }
                    hide_console_loading();
                    console_type_state = true;
                });
            }
            else if(console_msg_splited[1]=="commit"){
                if(getCookie("githubfld")=="") add_console_msg("red","No repository initialized to push");
                else {
                    console_type_state = false;
                    show_console_loading("Commiting");
                    setCookie("githubcommit", $("#console-input").val().split(command_head+" commit ")[1]);
                    $.get( "api.php?commit=true&dir="+getCookie("githubfld"), function(data) {
                        if(data=="nopass"){
                            add_console_msg("red", "PHPGit password is not given. Set it with <span class='blue'>"+command_head+" pass [YOUR PHPGIT PASSWORD]</span>");
                            hide_console_loading();
                            console_type_state = true;
                        }
                        else if(data=="wrongpass"){
                            add_console_msg("red", "PHPGit password is not matching, check your password and try again");
                            hide_console_loading();
                            console_type_state = true;
                        }
                        else {
                            add_console_msg("white",data);
                            hide_console_loading();
                            console_type_state = true;
                        }
                    });
                }
            }
            else if(console_msg_splited[1]=="repo"){
                setCookie("githubrepo", $("#console-input").val().split(command_head+" repo ")[1]);
                add_console_msg("green",getCookie("githubrepo")+" marked as push repository");
            }
            else if(console_msg_splited[1]=="key"){
                setCookie("githubkey", $("#console-input").val().split(command_head+" key ")[1]);
                add_console_msg("green",getCookie("githubkey")+" marked as auth key");
            }
            else if(console_msg_splited[1]=="pass"){
                setCookie("githubpass", $("#console-input").val().split(command_head+" pass ")[1]);
                add_console_msg("green",getCookie("githubpass")+" marked as password");
            }
            else if(console_msg_splited[1]=="email"){
                setCookie("githubemail", $("#console-input").val().split(command_head+" email ")[1]);
                add_console_msg("green",getCookie("githubemail")+" marked as github email");
            }
            else add_console_msg("red","Command not found");
        }
        
        function add_console_msg(color, msg){
            $("#console-msg").html($("#console-msg").html()+'<p class="'+color+'">&gt; '+msg+'</p>');
            $("html, body").animate({ scrollTop: $(document).height() }, 1000);
        }
        
        function show_console_loading(prase){
            console_loading_prase = prase;
            $("#console-loading").show();
        }
        
        function hide_console_loading(){
            $("#console-loading").hide();
        }
        
        function animate_console_loading(){
            if($("#console-loading").html().split("  ")[1]=="|") $("#console-loading").html(console_loading_prase+"  /");
            else if($("#console-loading").html().split("  ")[1]=="/") $("#console-loading").html(console_loading_prase+"  --");
            else if($("#console-loading").html().split("  ")[1]=="--") $("#console-loading").html(console_loading_prase+"  \\");
            else if($("#console-loading").html().split("  ")[1]=="\\") $("#console-loading").html(console_loading_prase+"  |");
        }
        
        function hide_auth_key(key){
            return "*****************"+key.slice(0, -3);
        }
        
        function get_cache_back(){
            $("#console-input").val($('.user-inputed').eq(cache_trace).html().replace("&gt; ", ""));
            if(cache_trace>0)cache_trace--;
        }
        
        function get_cache_front(){
            $("#console-input").val($('.user-inputed').eq(cache_trace).html().replace("&gt; ", ""));
            if(cache_trace<($('.user-inputed').length-1))cache_trace++;
        }
        
        function setCookie(cname, cvalue) {
            var exdays = 30;
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }
        
        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
            c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
            }
            }
            return "";
        }
        
        $.fn.focusWithoutScrolling = function(){
            var x = window.scrollX, y = window.scrollY;
            this.focus();
            window.scrollTo(x, y);
            return this; 
        };
    </script>
</html>