/**
* Serveur de websocket écrit en NodeJS pour l'api du Strawpoll
*
* Ce serveur de websocket connecté à Beanstalkd permet la
* propagation à tous les clients des nouveaux résultats d'un sondage 
* aussitôt qu'un nouveau vote est enregistré pour ce sondage.
*
* @authors Adam Attafi, Guillaume Peres et Sébastien Verneyre
*/

const http = require("http");
const socketio = require("socket.io");
const fivebeans = require("fivebeans");
const LISTEN_PORT = 5678;

// Imprime un message sur la sortie standard
const log = function(text) {
	const date = new Date();
	const out = "["+date.getDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear()+" "+date.getHours()+":"+date.getMinutes()+":"+date.getSeconds()+"] "+text;
	console.log(out);
};

// Imprime un message sur la sortie d'erreur
const warn = function(text) {
	const date = new Date();
	const out = "["+date.getDate()+"-"+(date.getMonth()+1)+"-"+date.getFullYear()+" "+date.getHours()+":"+date.getMinutes()+":"+date.getSeconds()+"] "+text;
	console.error(out);
};

// Initialisation du serveur http
const app = http.createServer().listen(LISTEN_PORT);
log("Http server launched");

// Initialisation de socket.io
const io = socketio.listen(app);
log("Socket io launched");

io.on("connection", function(socket) {
	socket.on("join_channel", function(channel) {
		socket.join(channel);
	});
	socket.on("leave_channel", function(channel) {
		socket.leave(channel);
	});
});

// Beanstalkd
const beanstalkd = new fivebeans.client("127.0.0.1", 11300);
beanstalkd.connect();

beanstalkd.on("connect", function() {
	log("Connected to Beanstalkd");
	beanstalkd.watch("strawpoll", function() {
		beanstalkd.reserve(processAndReserveNextJob);
	});
});
beanstalkd.on("close", function() {
	log("Stopping beanstalkd client...");
});
beanstalkd.on("error", function() {
	warn("Error on beanstalkd client");
	process.kill(process.pid, "SIGHUP");
});

// Réalisation d'un job beanstalkd et réservation d'un nouveau job
const processAndReserveNextJob = function(error, jobid, payload) {
	if (typeof(jobid) !== "undefined" || typeof(payload) !== "undefined") {
		log("Processing job id " + jobid);
		const job = JSON.parse(payload.toString());
		if (typeof(job.channel) !== "undefined" && typeof(job.results) !== "undefined") {
			io.in(job.channel).emit("results", job.results);
		}
		beanstalkd.destroy(jobid, null);
	}
	beanstalkd.reserve(processAndReserveNextJob);
};

// Gestion des signaux Unix
process.on("SIGTERM", function() {
	log("Stopping now...");
	io.close();
	app.close();
	process.exit(0);
});
process.on("SIGINT", function() {
	log("Stopping now...");
	io.close();
	app.close();
	process.exit(0);
});
process.on("SIGQUIT", function() {
	log("Stopping now...");
	io.close();
	app.close();
	process.exit(0);
});
process.on("SIGHUP", function() {
	log("Restarting now...");
	io.close();
	app.close();
	process.exit(0);
});
process.on("SIGUSR2", function() {
	log("Restarting now...");
	io.close();
	app.close();
	process.exit(0);
});
