var spotlist = [];
var deadspotlist = [];
var lastspawn = 0;
var spawndelay = 50;

function setup(){
  var canvas = createCanvas(windowWidth,windowHeight);
  canvas.parent("splash");
  frameRate(60);
  noStroke();
  background(0,30,50);
}

function draw(){
  background(0,30,50);
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
  if(millis() - lastspawn > spawndelay){
    spotlist.push(new Spot());
    lastspawn = millis();
  }
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
  this.d = random(0.5, 3);
  this.xvec = random(-2,2);
  this.yvec = random(-2,2);
  this.damping = 0.997;
  this.alpha = 1;
}

Spot.prototype = {
  constructor: Spot,

  draw:function(){
    fill(0,150,255,1 * this.alpha);
    ellipse(this.x, this.y, 100 * this.d, 100 * this.d);
    fill(0,150,255,5 * this.alpha);
    ellipse(this.x, this.y, 50 * this.d, 50 * this.d);
    fill(255,255,255,10 * this.alpha);
    ellipse(this.x, this.y, 20 * this.d, 20 * this.d);
    fill(255,255,255,20 * this.alpha);
    ellipse(this.x, this.y, 5 * this.d, 5 * this.d);
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
