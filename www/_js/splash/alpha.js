function setup(){
  var canvas = createCanvas(windowWidth,windowHeight);
  canvas.parent("splash");
  noStroke();
  background(0,30,50);
}

function draw(){
  fill(0,150,255,1);
  ellipse(mouseX, mouseY, 100, 100);
  fill(0,150,255,5);
  ellipse(mouseX, mouseY, 50, 50);
  fill(50,200,255,10);
  ellipse(mouseX, mouseY, 20, 20);
  fill(255,255,255,20);
  ellipse(mouseX, mouseY, 5, 5);
}

function getMouseX(){
  return mouseX / windowWidth;
}

function getMouseY(){
  return mouseY / windowHeight;
}

function mouseMoved(){
  //console.log(getMouseX() +", "+ getMouseY());
  // TODO: send normalized coordinates to broadcast server
}

function touchMoved(){
  //console.log(getMouseX() +", "+ getMouseY());
  // TODO: send normalized coordinates to broadcast server
}

function windowResized(){
  resizeCanvas(windowWidth, windowHeight);
  background(0,30,50);
}
