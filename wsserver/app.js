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

// Initialisation du serveur http
const app = http.createServer().listen(LISTEN_PORT);
console.log("Http server launched");

// Initialisation de socket.io
const io = socketio.listen(app);
console.log("Socket io launched");

io.on("connection", function(socket) {
	socket.on("join_channel", function(channel) {
		socket.join(channel);
	});
	socket.on("leave_channel", function(channel) {
		socket.leave(channel);
	});
});

// Initialisation de la connexion avec Beanstalkd
const beanstalkd = new fivebeans.client("127.0.0.1", 11300);
beanstalkd.connect();
beanstalkd.on("connect", function() {
	console.log("Connected to Beanstalkd");
	beanstalkd.watch("strawpoll", function() {
		beanstalkd.reserve(processAndReserveNextJob);
	});
});

// Réalisation d'un job beanstalkd et réservation d'un nouveau job
const processAndReserveNextJob = function(error, jobid, payload) {
	if (typeof(jobid) !== "undefined" || typeof(payload) !== "undefined") {
		console.log("Processing job id " + jobid);
		const job = JSON.parse(payload.toString());
		if (typeof(job.channel) !== "undefined" && typeof(job.results) !== "undefined") {
			io.in(job.channel).emit("results", job.results);
		}
		beanstalkd.destroy(jobid, null);
	}
	beanstalkd.reserve(processAndReserveNextJob);
};
