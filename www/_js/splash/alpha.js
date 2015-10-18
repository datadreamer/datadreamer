var spotlist = [];
var deadspotlist = [];

function setup(){
  var canvas = createCanvas(windowWidth,windowHeight);
  canvas.parent("splash");
  frameRate(60);
  noStroke();
  background(0,30,50);
}

function draw(){
  background(0,30,50,20);
  for(var i=0; i<spotlist.length; i++){
    spotlist[i].move();
    spotlist[i].draw();
    if(spotlist[i].dead){
      deadspotlist.push(spotlist[i]);
    }
  }
  for(var n=0; n<deadspotlist.length; n++){
    var index = spotlist.indexOf(deadspotlist[n]);
    if (index > -1) {
      spotlist.splice(index, 1);
    }
  }
  deadspotlist = [];
}

function getMouseX(){
  return mouseX / windowWidth;
}

function getMouseY(){
  return mouseY / windowHeight;
}

function mouseMoved(){
  // TODO: send normalized coordinates to broadcast server
  spotlist.push(new Spot());
}

function touchMoved(){
  // TODO: send normalized coordinates to broadcast server
  spotlist.push(new Spot());
}

function windowResized(){
  resizeCanvas(windowWidth, windowHeight);
  background(0,30,50);
}


/* CLASSES */

function Spot(){
  this.birth = millis();
  this.lifespan = random(2000, 5000);
  this.dead = false;
  this.x = mouseX;
  this.y = mouseY;
  this.xvec = random(-2,2);
  this.yvec = random(-2,2);
  this.damping = 0.997;
  this.alpha = 1;
}

Spot.prototype = {
  constructor: Spot,

  draw:function(){
    fill(0,150,255,1 * this.alpha);
    ellipse(this.x, this.y, 100, 100);
    fill(0,150,255,5 * this.alpha);
    ellipse(this.x, this.y, 50, 50);
    fill(255,255,255,10 * this.alpha);
    ellipse(this.x, this.y, 20, 20);
    fill(255,255,255,20 * this.alpha);
    ellipse(this.x, this.y, 5, 5);
    if(this.progress() < 1){
      this.alpha = 1 - this.progress();
    } else {
      this.alpha = 0;
      this.dead = true;
    }
  },

  move:function(){
    this.xvec += random(-0.1, 0.1);
    this.yvec += random(-0.1, 0.1);
    this.xvec *= this.damping;
    this.yvec *= this.damping;
    this.x += this.xvec;
    this.y += this.yvec;
  },

  progress:function(){
    return (millis() - this.birth) / this.lifespan;
  }
}
