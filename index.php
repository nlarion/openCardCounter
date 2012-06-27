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
	var numBalls = 100 ;
	var maxSize = 12;
	var minSize = 3;
	var maxSpeed = maxSize+5;
	var balls = new Array();
	var tempBall;
	var tempX;
	var tempY;
	var tempSpeed;
	var tempAngle;
	var tempRadius;
	var tempRadians;
	var tempvelocityx;
	var tempvelocityy;
	var playerBall;
	var controlerObj = {vy:0.0,vx:0.0}; //not sure what this is
	var GetKeyCodeVar;
	var player = {x:250,y:475}; //player obj
	var isTheMouseBeingPressed = false;	

	for (var i = 0; i < numBalls; i++) {
		tempRadius = Math.floor(Math.random()*maxSize)+minSize;
		var placeOK = false;
		while (!placeOK) {
			tempX = tempRadius*3 + (Math.floor(Math.random()*canvas.width)-tempRadius*3);
			tempY = tempRadius*3 + (Math.floor(Math.random()*canvas.height)-tempRadius*3);
			tempSpeed = 2;
			tempAngle =  Math.floor(Math.random()*360);
			tempRadians = tempAngle * Math.PI/ 180;
			tempvelocityx = Math.cos(tempRadians) * tempSpeed;
			tempvelocityy = Math.sin(tempRadians) * tempSpeed;
			tempBall = {x:tempX,y:tempY, nextX: tempX, nextY: tempY, radius:tempRadius, speed:tempSpeed, angle:tempAngle, velocityx:tempvelocityx, velocityy:tempvelocityy, mass:tempRadius, player:0};
			placeOK = canStartHere(tempBall);
		}
		balls.push(tempBall);
	}
	
	playerRadius = 50;
	playerX = canvas.width/2;
	playerY = canvas.height/2;
	playerSpeed = 0;
	playerAngle =  Math.floor(Math.random()*360);
	playerRadians = playerAngle * Math.PI/ 180;
	playervelocityx = 0;
	playervelocityy = 0;
	playerBall = {x:playerX, y:playerY, nextX: playerX, nextY: playerY, radius:playerRadius, speed:playerSpeed, angle:playerAngle, velocityx:playervelocityx, velocityy:playervelocityy, mass:playerRadius, player:1};
	balls.push(playerBall);

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

	function  drawScreen () {
		c.fillStyle = '#0000A0';
		c.fillRect(0, 0, canvas.width, canvas.height);
		//Box
		c.strokeStyle = '#000000'; 
		c.strokeRect(1,  1, canvas.width-2, canvas.height-2);
		player_controler();
		update();
		testWalls();
		collide();
		render();
		//call player stats to screen for testing only
		c.font = "12px serif";
		c.fillStyle = "#FFFFFF";
		c.fillText (player.x, 50, 50);
		c.fillText (player.y, 100, 50);
	}

	function player_controler() {
		for (var i =0; i <balls.length; i++) {
			ball = balls[i];
			if (ball.player == 1){
				//var lengthOfDistance = (ball.x - player.x);
				var widthOfDistance = (player.x - ball.x);
				//var widthOfDistance = (ball.y - player.y);
				var lengthOfDistance = (player.y - ball.y);
				playerMouseDistance = Math.sqrt((lengthOfDistance*lengthOfDistance) + (widthOfDistance*widthOfDistance));
				if (playerMouseDistance<ball.radius){
					ball.velocityy = (lengthOfDistance/120);
					ball.velocityx = (widthOfDistance/120);				
				} else if (playerMouseDistance<(ball.radius*2)){
					ball.velocityy = (lengthOfDistance/50);
					ball.velocityx = (widthOfDistance/50);
				} else{
					ball.velocityy = (lengthOfDistance/30);
					ball.velocityx = (widthOfDistance/30);
				}
				ball.nextx = (ball.x += ball.velocityx);
				ball.nexty = (ball.y += ball.velocityy);
				c.font = "12px serif"
				c.fillStyle = "#FFFFFF";
				c.fillText (ball.nextx, 50, 70);
				c.fillText (ball.nexty, 50, 90);
				c.fillText (playerMouseDistance, 50, 110);
			}
		}
	}
	
	function update() {
		for (var i =0; i <balls.length; i++) {
			ball = balls[i];
			if (ball.player == 0){
				//ball.velocityx += .1;
				ball.nextx = (ball.x += ball.velocityx);
				ball.nexty = (ball.y += ball.velocityy);
			}
		}
	}
	
	function testWalls() {
		var ball;
		var testBall;
		
		for (var i =0; i <balls.length; i++) {
			ball = balls[i];
			
			if (ball.nextx+ball.radius > canvas.width+100) {
				ball.velocityx = ball.velocityx*-1;
				ball.nextx = canvas.width+100 - ball.radius;
				
			} else if (ball.nextx-ball.radius < -100 ) {
				ball.velocityx = ball.velocityx*-1;
				ball.nextx = -100 + ball.radius;
			
			} else if (ball.nexty+ball.radius > canvas.height+100 ) {
				ball.velocityy = ball.velocityy*-1;
				ball.nexty = canvas.height+100 - ball.radius;
				
			} else if(ball.nexty-ball.radius < -100) {
				ball.velocityy = ball.velocityy*-1;
				ball.nexty = -100 + ball.radius;
			}
			
			
		}
	
	}
	
	function render() {
		var ball;
		for (var i =0; i <balls.length; i++) {
			ball = balls[i];
			if(ball.player ==0){
				ball.x = ball.nextx;
				ball.y = ball.nexty;
				c.fillStyle = "rgba(255, 255, 255, 0.5)";
				c.beginPath();
				c.arc(ball.x,ball.y,ball.radius,0,Math.PI*2,true);
				c.closePath();
				c.fill();
			}
			if(ball.player == 1){
				ball.x = ball.nextx;
				ball.y = ball.nexty;
				c.fillStyle = "rgba(0, 0, 0, 0.5)";
				c.beginPath();
				c.arc(ball.x,ball.y,ball.radius,0,Math.PI*2,true);
				c.closePath();
				c.fill();
			}
		}
		
	}
	
	function collide() {
	    var ball;
	    var testBall;
	    for (var i =0; i <balls.length; i++) {
			ball = balls[i];
			for (var j = 0 ; j < balls.length; j++) {
				testBall = balls[j];
				if ( j != i && hitTestCircle(ball,testBall)==true ) {
				    collideBalls(ball,testBall);
				}
				
		   	}
		}
  	}

	
	function hitTestCircle(ball1,ball2) {
	    var retval = false;
	    var dx = ball1.nextx - ball2.nextx;
	    var dy = ball1.nexty - ball2.nexty;
	    //var distance = (dx * dx + dy * dy);
		var distance = Math.sqrt((dx*dx) + (dy*dy));
	    if (distance <= (ball1.radius + ball2.radius)) {
	  	    retval = true;
	    }
     	return retval;
  	}


	//read page 204
	function collideBalls(ball1,ball2) {
	
 		var dx = ball1.nextx - ball2.nextx;
		var dy = ball1.nexty - ball2.nexty;

		var collisionAngle = Math.atan2(dy, dx);
 
		var speed1 = Math.sqrt(ball1.velocityx * ball1.velocityx + ball1.velocityy * ball1.velocityy);
		var speed2 = Math.sqrt(ball2.velocityx * ball2.velocityx + ball2.velocityy * ball2.velocityy);
 
		var direction1 = Math.atan2(ball1.velocityy, ball1.velocityx);
		var direction2 = Math.atan2(ball2.velocityy, ball2.velocityx);
 
		var velocityx_1 = speed1 * Math.cos(direction1 - collisionAngle);
		var velocityy_1 = speed1 * Math.sin(direction1 - collisionAngle);
		var velocityx_2 = speed2 * Math.cos(direction2 - collisionAngle);
		var velocityy_2 = speed2 * Math.sin(direction2 - collisionAngle);
		
		var final_velocityx_1 = ((ball1.mass - ball2.mass) * velocityx_1 + (ball2.mass + ball2.mass) * velocityx_2)/(ball1.mass + ball2.mass);
		var final_velocityy_1 = velocityy_1;
		//var final_velocityx_1 = velocityx_1;
		var final_velocityx_2 = ((ball1.mass + ball1.mass) * velocityx_1 + (ball2.mass - ball1.mass) * velocityx_2)/(ball1.mass + ball2.mass);
		var final_velocityy_2 = velocityy_2;
		//var final_velocityx_2 = velocityx_2;
 
		ball1.velocityx = Math.cos(collisionAngle) * final_velocityx_1 + Math.cos(collisionAngle + Math.PI/2) * final_velocityy_1;
		ball1.velocityy = Math.sin(collisionAngle) * final_velocityx_1 + Math.sin(collisionAngle + Math.PI/2) * final_velocityy_1;
		ball2.velocityx = Math.cos(collisionAngle) * final_velocityx_2 + Math.cos(collisionAngle + Math.PI/2) * final_velocityy_2;
		ball2.velocityy = Math.sin(collisionAngle) * final_velocityx_2 + Math.sin(collisionAngle + Math.PI/2) * final_velocityy_2;
 
  		ball1.nextx = (ball1.nextx += ball1.velocityx);
		ball1.nexty = (ball1.nexty += ball1.velocityy);
		ball2.nextx = (ball2.nextx += ball2.velocityx);
		ball2.nexty = (ball2.nexty += ball2.velocityy);
		if (ball1.player==1) {
			c.font = "12px serif"
			c.fillStyle = "#FFFFFF";
			c.fillText (ball1.velocityx, 50, 150);
			c.fillText (ball1.velocityy, 50, 170);
			c.fillText (ball2.velocityx, 50, 190);
			c.fillText (ball2.velocityy, 50, 210);
			c.fillText (collisionAngle,50,230);
			//alert("bla");
		}
	}

	
	function canStartHere(ball) {
		var retval = true;
		for (var i =0; i <balls.length; i++) {
			if (hitTestCircle(ball, balls[i])) {
				retval = false;
			}
		}
		return retval;
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
		c.fillText ("n-ion",canvas.width/3, canvas.height/2);
		if (introCount==150 || isTheMouseBeingPressed==true) {
			appState = STATE_PLAYING;
		} 
		//else{		return;	}
	
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
