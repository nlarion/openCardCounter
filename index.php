<?
print "<html>";
?>
<head>
<script>
window.addEventListener('load', eventWindowLoaded, false);	
function eventWindowLoaded() {
	main();
}

function main() {

	//globals

	const STATE_INIT = 10;
	const STATE_LOADING = 20;
	const STATE_RESET = 30;
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
	var player = {x:250,y:475,radius:10}; //player obj
	var isTheMouseBeingPressed = false;	
	var cards = new Array();
	var GetKeyCodeVar = 0;

	//event listeners

	window.addEventListener('keydown', GetChar, false);
	window.addEventListener('keyup', resetchar, true);
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

	function drawScreen () {

		draw_table();
		player_controler();
		update();
		render();

		//call stats to screen for testing only
		c.textAlign = 'left';
		c.font = "12px serif";
		c.fillStyle = "#FFFFFF";
		var someCardVals = "here are some cards posistions ";
		for(var i = 0; i<10; i++){
			someCardVals += ' '+cards[i].posistionInDeck +' ';
		}
		var someCardVals2 = "here are some cards vals ";
		for(var i = 0; i<10; i++){
			someCardVals2 += ' '+cards[i].cardValue +' ';
		}
		c.fillText (someCardVals, 50, 50); //first var in txt is for the var you want to see
		c.fillText (someCardVals2, 50, 100); //first var in txt is for the var you want to see
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
	
		var gr = c.createLinearGradient(0, 0, 1000, 0);
		
		// Add the color stops.
		  
		gr.addColorStop(0,'#a7803d');
		gr.addColorStop(.5,'#996600');
		gr.addColorStop(1,'#e6b154');
		
		// Use the gradient for the fillStyle.
		c.strokeStyle = gr;
		for (i=0;i<100;i++){
			c.lineWidth = 1+i;
			c.beginPath();
			c.moveTo(5+i*14,5);
			c.lineTo(5+i*14,140);
			c.stroke();
		}
	}

	function player_controler() {
		switch (GetKeyCodeVar) {
			//should make move charactor method.
			case 0:		
				// do noting
				break;
 			case 39:			
				// if right
				shuffleDecks(1);
				break;
			case 'rightstop':
				// do something
				break;
 			case 37:			
				// if left
				cards.splice(0,1);
 				break;
 			case 'leftstop':	
				// do something
				break;
 			case 40:	
				//figure out what 40 is
 				break;
 			case 'downstop':	
				// do something
 				break;
 			case 38:	
				//figure out what 38 is
 				break;
  			case 'upstop':	
				// do something
 				break;

		}
	}
	
	function update() {
		
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
		for (var i = 0; i < cards.length; i++) {
			var txt = cards[i].posistionInDeck;
			c.lineWidth = 8;
			c.beginPath();
			c.fillStyle = "#ffffff";
			c.strokeStyle = '#000000'; 
			c.lineJoin='round';
			c.lineCap='butt';
			c.beginPath();
			var x = (canvas.width/12)*txt;
			var y = (canvas.height/12)*txt;
			c.moveTo(x, y);
			c.lineTo(x+(canvas.height/12), y);
			c.lineTo(x+(canvas.height/12),y+(canvas.height/10));
			c.lineTo(x,y+(canvas.height/10));
			c.lineTo(x,y);
			c.stroke();
			c.closePath();
			c.fill();
			c.font = "12px serif";
			c.fillStyle = "#000000";
			c.fillText (txt, x+10, y+10); //first var in txt is for the var you want to see
			drawSuitOnCard(x,y,cards[i].cardSuit);
		}
	}
	//create cards
	function drawSuitOnCard(x,y,suit){
		switch (suit) {
			//should make move charactor method.
			case 1:		
				// dimond
				break;
 			case 2:			
				// heart
				break;
			case 3:
				// spade
				break;
 			case 4:		
				//club	
				//c.lineWidth = 1;
				c.beginPath();
				c.fillStyle = "rgba(0, 0, 0, 1)";
				c.strokeStyle = '#000000'; 
				c.arc(x,y,10,0,Math.PI*2,true);
				c.arc(x+10,y+17,10,0,Math.PI*2,true);
				c.arc(x-10,y+17,10,0,Math.PI*2,true);
				c.stroke();
				c.closePath();
				c.fill();
	 			break;
	 	

		}
	}
	function makeDecks(cardDecks){
		//alert(cardDecks);
		var maxCards = cardDecks*52;
		if (maxCards=0){
			alert('cant be zero!');
		}else{
			var startCountForPosistion = 1;
			for (var i = 0; i < cardDecks; i++) {
				for (var k = 1; k <=13 ; k++) {
					for (var l = 1; l <=4 ; l++) {			
						//tempCard = {posistionInDeck: (i*k), posistionOnTable: 'deck', cardValue: k, cardSuit: l};
						tempCard = {posistionInDeck: startCountForPosistion, posistionOnTable: 'deck', cardValue: k, cardSuit: l};
						startCountForPosistion++;
						cards.push(tempCard);
						//alert(tempCard.posistionInDeck);
					}
				}
			}
		}
	}

	function shuffleDecks(cardDecks){
		//alert(cards.length);		
		//var maxCards = cardDecks*52;
		var arrayForPosistions = new Array();
		for (var i = 0; i < cards.length; i++) {
			arrayForPosistions.push(i);
			//alert(arrayForPosistions[i]);
		}	
		//alert(arrayForPosistions[].value);
		arrayForPosistions.sort(function() {return 0.5 - Math.random()});
		for (var i = 0; i < cards.length; i++) {
			cards[i].posistionInDeck=arrayForPosistions[i];
		}	
	}

	function GetChar(event){
		var keyCode = ('which' in event) ? event.which : event.keyCode;
		GetKeyCodeVar=keyCode;
	}
	function resetchar(event){
// something here to count and keep track of new keys being pressed.
        	var keyCode = ('which' in event) ? event.which : event.keyCode;
		if (keyCode != GetKeyCodeVar){
	        	return;
	        } else{
			switch (keyCode) {
	      	 		case 39:
	      	 	 		GetKeyCodeVar='rightstop';
	     			break;
				case 37:
					GetKeyCodeVar='leftstop';
				break;
				case 40:
					GetKeyCodeVar='downstop';
				break;
				case 38:
					GetKeyCodeVar='upstop';
				break;
			}
		}
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
			appState = STATE_LOADING;
		} 
	
	}

	function run() {
	  	switch(appState) {
			case STATE_INIT:
				initApp();
				break;
			case STATE_LOADING:
				makeDecks(3); //make initial cards
				//wait for call backs
				appState = STATE_PLAYING;
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
</head>
<body>
<div id="cool"></div>
</body>
</html>
