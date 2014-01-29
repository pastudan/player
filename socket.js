var io = require('socket.io').listen(8888),
request = require('request'),
uuid = require('node-uuid'),
config = {
    lastFMKey: "f2d74fdc9a039ef72c627f37eeb351d7"
};

io.sockets.on('connection', function (socket) {

    console.log("CLIENT CONNECTED: " + socket.id);

    socket.on('search', function (data) {
        var searchID = uuid.v4();

        if (data.query){

            //search last.fm to get the most relevant results
            var searchURL = 'http://ws.audioscrobbler.com/2.0/?method=track.search&track=' + data.query +
                '&api_key=' + config.lastFMKey +
                '&format=json';
            request(searchURL, function (error, response, body) {
                if (!error && response.statusCode == 200) {
                    body = JSON.parse(body);
                    console.log(body.results, body.trackmatches);
                    if (typeof body.results == "undefined" || typeof body.results.trackmatches == "undefined"){
                        socket.emit('searchResult', {
                            searchID: searchID,
                            errorCode: -1,
                            error: "There was a problem while accessing the last.fm api"
                        });
                    } else {
                        socket.emit('searchResult', {
                            searchID: searchID,
                            results: body.results.trackmatches.track
                        });
                        //TODO: check local headphones API to see if we have the track / album. If not, mark it as wanted
                    }
                }
            });

            socket.emit('searchResult', {
                searchID: searchID,
                query: data.query
            });
        } else {
            socket.emit('error', {
                errorCode: -1,
                error: "Please provide a search query"
            });
        }

    });


    socket.on('disconnect', function () {
        console.log("CLIENT DISCONNECTED: " + socket.id);
    })


});
