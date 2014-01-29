<?php
$version = "0.1"; // this lets us know which clients are connected & with what version on the back end.
?>

<!DOCTYPE html>
<html> 
<head>
	<title>TubeLab</title>
	<link id="favicon" rel="shortcut icon" href="images/player_logo.png" />
<!--    TODO: Checkout SASS or some other CSS preprocessor-->
    <link rel="stylesheet" href="css/reset_clearfix_borderbox.css" />
    <link rel="stylesheet" href="css/base.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.min.css" />
	<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/socket.io/0.9.16/socket.io.min.js"></script>

    <script>

        //TODO: make this connect via a real domain. This is easier for development right now
        var socket = io.connect('http://localhost:8888');
        socket.on('connect', function () {
            socket.emit('authenticate', {
                version: "<?php echo $version; ?>"
            });
        });
        socket.on('searchResult', function (data) {
            console.log(data);
            if (data.results){
                for (i in data.results){
                    $(".result .listing").append("<tr><td>"+data.results[i].name+"</td><td>"+data.results[i].artist+"</td><td>"+data.results[i].url+"</td></tr>");
                }
            } else {
                var $resultHeader = $(".result .header");
                $resultHeader.find('.query').text(data.query);
                $resultHeader.find('.search-id').text(data.searchID);
            }
        });

        socket.on('error', function (data) {
            alert(data.error);
        });

        $(function(){
            $(document).on("submit", "#searchform", function(){
                var query = $(this).find("input[name=query]").val();
                socket.emit('search', {
                    query: query
                });
                return false;
            });
        });

	</script>
</head>


<body>

    <header class="cf">
        <form id="searchform">
            <input type="text" name="query" placeholder="Search" />
        </form>
        <span class="separator"></span>
        <i class="fa fa-clock-o"></i>
        <ul class="history">
            <li><h3>Search</h3>Party Rock</li>
            <li><h3>Search</h3>LMFAO</li>
            <li><h3>Search</h3>Shots</li>
        </ul>
    </header>

    <div class="content">
        <div class="left-panel">
            <h2>Playlists</h2>
            <ul>
                <li><i class="fa fa-music"></i> This is a list item</li>
                <li><i class="fa fa-music"></i> This is a list item</li>
                <li><i class="fa fa-music"></i> This is a list item</li>
                <li><i class="fa fa-music"></i> This is a list item</li>
                <li><i class="fa fa-music"></i> This is a list item</li>
            </ul>
        </div>
        <div class="result">
            <div class="header">
                <h2>Search</h2>
                <span class="query"></span>
                <span class="search-id"></span>
            </div>
            <table class="listing" style="width: 100%"></table>
        </div>
    </div>

    <footer>
        Hello world
    </footer>

</body>


</html>
