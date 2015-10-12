var static = require('node-static');
var file = new static.Server({
    cache: 86400,
    serverInfo: "maple-cdn-server",
    indexFile: "index.html",
    gzip: true,
    headers : {
        "Cache-Control" : "max-age=86400, public",
        'Access-Control-Allow-Origin' : "*",
        'Access-Control-Allow-Methods' : 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' : 'content-type'
    }
});
require('http').createServer(function(request, response) {
    try{
        console.log("File: ",request.url);
        if((/\/(src|dist)\//).test(request.url)){
            file.serve(request, response,function (err, result) {
                if (err) { // There was an error serving the file
                    console.error("Error serving " + request.url + " - " + err.message);
                    // Respond to the client
                    response.writeHead(err.status, err.headers);
                    response.write("NF:"+request.url);
                    response.end();
                }
            });
        } else {
            //file.serveFile("index.html",200, {}, request, response);
            response.write("NF:"+request.url);
            response.end();
        }
    } catch(e){
        //response.write("Error request.url",request.url);
        //response.end();
        response.write("NF:"+request.url);
        response.end();
    }

}).listen(process.env.PORT || 3000);