var http = require('http')

var server = http.createServer(function(req, res) {
	  res.end('Coucou depuis Docker')
});

server.listen(3000)
