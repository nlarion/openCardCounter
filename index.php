<?
print "<html>";
?>
<body>
<div id="cool"></div>
<script>
window.addEventListener('load', eventWindowLoaded, false);	

function eventWindowLoaded() {

	main();
	
}

function main() {

	//globals

	const STATE_INIT 	= 10;
	const STATE_LOADING = 20;
	const STATE_RESET	= 30;
	const STATE_PLAYING = 40;
	var canvas, c;
	var timer = 0;
	var cheight = document.documentElement.clientHeight;
	var cwidth = document.documentElement.clientWidth;
	document.getElementById("cool").innerHTML="<canvas style='position:absolute;left:0px;top:0px;' width='"+ cwidth +"' height='" + cheight + "' id='cid' ></canvas>";
	canvas = document.getElementById('cid');
	c = canvas.getContext('2d');
	var pointImage = new Image();
	var GetKeyCodeVar;
	var player = {x:250,y:475,radius:20}; //player obj
	var isTheMouseBeingPressed = false;	
	
	//event listeners

	canvas.addEventListener("mousedown",MouseClicked, false);
	function MouseClicked(event){
		isTheMouseBeingPressed = true;
	}
	canvas.addEventListener("mouseup",MouseUnClicked, false);	
	function MouseUnClicked(event){
		isTheMouseBeingPressed = false;
	}
	canvas.addEventListener("mousemove",MouseMove, false);	

	function MouseMove(event) {
		if ( event.layerX ||  event.layerX == 0) { // Firefox
   			mouseX = event.layerX ;
			mouseY = event.layerY;
  		} else if (event.offsetX || event.offsetX == 0) { // Opera
    			mouseX = event.offsetX;
			mouseY = event.offsetY;
		}
		player.x = mouseX;
		player.y = mouseY;

	}
	window.addEventListener("resize",changeCanvasSize,false);
	function changeCanvasSize() {
		canvas.width = document.documentElement.clientWidth;
		canvas.height = document.documentElement.clientHeight;
	}

	//functions

	function  drawScreen () {

		draw_table();
		player_controler();
		update();
		render();
		//call stats to screen for testing only
		//c.font = "12px serif";
		//c.fillStyle = "#FFFFFF";
		//c.fillText (player.x, 50, 50); //first var in txt is for the var you want to see
		//c.fillText (player.y, 100, 50); //first var in txt is for the var you want to see
	}

	function draw_table() {
		//draw the static screen
		c.lineWidth = 1;
		c.beginPath();
		c.fillStyle = '#0000A0';
		c.fillRect(0, 0, canvas.width, canvas.height);
		c.strokeStyle = '#000000'; 
		c.strokeRect(1,  1, canvas.width-2, canvas.height-2);
		c.closePath();
	
		var gr = c.createLinearGradient(0, 0, 100, 0);
		
		// Add the color stops.
		  
		gr.addColorStop(0,'rgb(255,0,0)');
		gr.addColorStop(.5,'rgb(0,255,0)');
		gr.addColorStop(1,'rgb(255,0,0)');
		
		// Use the gradient for the fillStyle.
		c.strokeStyle = gr;
		for (i=0;i<10;i++){
			c.lineWidth = 1+i;
			c.beginPath();
			c.moveTo(5+i*14,5);
			c.lineTo(5+i*14,140);
			c.stroke();
		}
	}

	function player_controler() {
		//add things here for player controls
	}
	
	function update() {
		//update cards
	}
	
	function render() {
		//draw player
		c.lineWidth = 1;
		c.beginPath();
		c.fillStyle = "rgba(0, 0, 0, 0.5)";
		c.strokeStyle = '#000000'; 
		c.arc(player.x,player.y,player.radius,0,Math.PI*2,true);
		c.stroke();
		c.closePath();
		c.fill();	
	}

	function GetChar(event){
		var keyCode = ('which' in event) ? event.which : event.keyCode;
		GetKeyCodeVar=keyCode;
	}

	function runtheapp() {	
		setInterval(run,33);
	}

	function initApp() {
		introCount++;
		fadeIn = introCount + 30;
		awesome = fadeIn.toString(16);
		c.fillStyle = '#0001'+awesome;
		c.fillRect(0, 0, canvas.width, canvas.height);
		//Box
		c.strokeStyle = '#000000'; 
		c.strokeRect(1,  1, canvas.width-2, canvas.height-2);
		c.font = " "+canvas.width/10+"px serif"
		c.fillStyle = "#"+introCount+"";
		c.textAlign = 'center';
		c.fillText ("openCardCounter",canvas.width/2, canvas.height/2);
		if (introCount==150 || isTheMouseBeingPressed==true) {
			appState = STATE_PLAYING;
		} 
	
	}

	function run() {
	  	switch(appState) {
			case STATE_INIT:
				initApp();
				break;
			case STATE_LOADING:
				//wait for call backs
				break;
			case STATE_RESET:
				resetApp();
				break;
			case STATE_PLAYING:
				drawScreen();
				break;		
				 	
		}
	}

	var appState = STATE_INIT;
	var introCount = 0;
	pointImage.src = "point.png";
	pointImage.addEventListener('load', runtheapp(), false);
}
</script>

</body>
</html>
