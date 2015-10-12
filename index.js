var static = require('node-static');
var file = new static.Server({
    cache: 86400,
    serverInfo: "maple-cdn-server",
    indexFile: "index.html",
    headers : {
        "Cache-Control" : "max-age=86400, public",
        'Access-Control-Allow-Origin' : "*",
        'Access-Control-Allow-Methods' : 'GET, POST, PUT, DELETE, OPTIONS',
        'Access-Control-Allow-Headers' : 'content-type'
    }
});
require('http').createServer(function(request, response) {
    try{
        if((/\/(src|dist|build)\//).test(request.url)){
            request.addListener('end', function() {
                file.serve(request, response);
            }).resume();
        } else {
            request.addListener('end', function() {
                file.serveFile("index.html",200, {}, request, response);
            }).resume();
        }
    } catch(e){
        response.write("Error request.url",request.url);
        response.end();
    }

}).listen(process.env.PORT || 3000);