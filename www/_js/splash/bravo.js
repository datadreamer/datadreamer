var spotlist = [];
var deadspotlist = [];
var lastspawn = 0;
var spawndelay = 100;

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

function mouseDragged(){
  // TODO: send normalized coordinates to broadcast server
  if(millis() - lastspawn > spawndelay){
    var s = new Spot();
    s.maxalpha = 255;
    s.alpha = 255;
    spotlist.push(s);
    lastspawn = millis();
  }
}

function mouseMoved(){
  // TODO: send normalized coordinates to broadcast server
  if(millis() - lastspawn > spawndelay){
    spotlist.push(new Spot());
    lastspawn = millis();
  }
}

function mousePressed(){
  // TODO: send normalized coordinates to broadcast server
  if(millis() - lastspawn > spawndelay){
    var s = new Spot();
    s.maxalpha = 255;
    s.alpha = 255;
    spotlist.push(s);
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
  this.normx = mouseX / windowWidth;
  this.normy = mouseY / windowHeight;
  this.d = random(0.5, 3);
  this.xvec = random(-2,2);
  this.yvec = random(-2,2);
  this.damping = 0.997;
  this.maxalpha = 50;
  this.alpha = this.maxalpha;
  this.connectradius = 0.2;
}

Spot.prototype = {
  constructor: Spot,

  dist:function(s){
    var xdiff = this.normx - s.normx;
    var ydiff = this.normy - s.normy;
    return Math.sqrt(xdiff*xdiff + ydiff*ydiff);
  },

  draw:function(){
    //fill(0,150,255, this.alpha);
    //ellipse(this.x, this.y, 5 * this.d, 5 * this.d);
    this.connect();
    if(this.progress() < 1){
      this.alpha = this.maxalpha - (this.progress() * 255);
    } else {
      this.alpha = 0;
      this.dead = true;
    }
  },

  connect:function(){
    // draw a line to other spots within radius
    for(var i=0; i<spotlist.length; i++){
      var distance = this.dist(spotlist[i])
      if(distance < this.connectradius){
        stroke(255, 128 - ((distance / this.connectradius) * 128));
        line(this.x, this.y, spotlist[i].x, spotlist[i].y);
      }
    }
  },

  move:function(){
    this.xvec += random(-0.1, 0.1);
    this.yvec += random(-0.1, 0.1);
    this.xvec *= this.damping;
    this.yvec *= this.damping;
    this.x += this.xvec;
    this.y += this.yvec;
    this.normx = this.x / windowWidth;
    this.normy = this.y / windowHeight;
  },

  progress:function(){
    return (millis() - this.birth) / this.lifespan;
  }
}
