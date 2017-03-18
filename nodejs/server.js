var CONFIG = {
	host: 		'chat.quanly.org',
	port: 		'3333',
	user : 		'root',
  	password: 	'',
	database: 	'project_chat',
    domainPost: 	'http://chat.quanly.org',
}
var app = require('http').createServer(handler),
	io = require('socket.io').listen(app),
	mysql = require('mysql'),
    requestify = require('requestify');

var conn =  mysql.createConnection({
  	host 	: CONFIG.host,
  	user 	: CONFIG.user,
  	password: CONFIG.password,
	database: CONFIG.database,
  });
app.listen(CONFIG.port);

function handler(req, res){
	res.writeHead(200, {'Content-Type': 'text/html'});
	res.end();
}

io.sockets.on('connection', function (socket){
    socket.on("ajaxGetMessagesConversation", function (data) {
        //Check Messages Facebook Request
        requestify.get(CONFIG.domainPost+'/ajaxGetMessagesConversation?datac='+data.datac+'&dataid='+data.dataid+'&mid='+data.mid).then(function(response){
            var rows = response.getBody();
            socket.emit("ajaxGetMessagesConversation", rows);
            socket.broadcast.emit("ajaxGetMessagesConversation", rows);
        });
    });
});