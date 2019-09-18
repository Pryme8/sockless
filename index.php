<!doctype html>
<html>
<head>
<meta charset="UTF-8"/>
<title>Sockless</title>
</head>
<body>
<script>
class Sockless{
	constructor(inputs, dir=''){
		this._source = new EventSource(dir+'sockless.php?channel=main')
		this._inputSources = inputs
		let self = this		
		this.source.addEventListener("ping", (e)=>{
			console.log("ping", e)
			
		})

		this.source.addEventListener("message", (e)=>{
			console.log("packet", self.decode(e.data));
			
		})				
		this.source.onerror = function (event) {
			switch (self.source.readyState) {
				case EventSource.CONNECTING:
				console.log("Sockless Connecting!")
				break;
				case EventSource.CLOSED:
				console.log("Sockless Conection Closed!")
				break;
			}
		}
	}
	
	encode(str){
		return btoa(unescape(encodeURIComponent(JSON.stringify(str))))	
	}
	
	decode(str) {
		return JSON.parse(decodeURIComponent(escape(atob(str))))
	}
	
	createPacket(inputs = null){
	if(!inputs){
	inputs = this.inputs
	}
		let packet = {
			channel:'main'
		}
		let keys = Object.keys(inputs)
		for(var i=0; i<keys.length; i++){
			let key = keys[i]
			let value =  inputs[key]
			packet[key] = value	
		}
		packet['ts'] = Date.now()
		return this.encode(packet)
	}
	
	send(packet){
		console.log("Sending:", packet);
		fetch('sockless-in.php', {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        mode: 'cors', // no-cors, *cors, same-origin
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        credentials: 'same-origin', // include, *same-origin, omit
        headers: {
            //'Content-Type': 'application/json',
            'Content-Type': 'text/event-stream',
        },
        redirect: 'follow', // manual, *follow, error
        referrer: 'no-referrer', // no-referrer, *client
        body: packet, // body data type must match "Content-Type" header
		})		
	}	
	
	get source(){
		return this._source
	}
	
	get inputs(){
		return this.inputs
	}
}

let sockless = new Sockless([])

//setTimeout(()=>{sockless.send(sockless.createPacket({user:"Test User", msg:"TestMessage/1/1212!!"}));},1000);

</script>

	<div>
		<div><input id='chat-name' placeholder='name'></input></div>
		<div><input id='message' placeholder='message'></input></div>
		<div><a id='send' onClick="sockless.send(sockless.createPacket({name:document.getElementById('chat-name').value, msg:document.getElementById('message').value}));">Send</a></div>
	</div>			
</body>
</html>