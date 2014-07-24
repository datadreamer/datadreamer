/* @pjs
preload="_canvas/lamp.jpg";
font=_canvas/DINB__.TTF;
*/

ArrayList<Node> nodes;
int maxNodes = 30;
int nodeCount = 0;
int nodeRate = 200;
long lastNode;
PGraphics pg;
ColorPanel panel;
PFont font;

// store colors to be changed intermittently
float r = 255;
float g = 0;
float b = 0;

// store three colors
color[] colors = new color[3];
color c;

// store time and repeatedly check for new color values
long lastColorCheck;
int colorCheckRate = 5000;

void setup(){
  size($('#galleryimage').width(), $('#galleryimage').height());
  background(0);
  pg = createGraphics(width, height);
  ellipseMode(CENTER);
  PImage bg = loadImage("_canvas/lamp.jpg");
  font = createFont("_canvas/DINB__.TTF", 10);
  textFont(font);
  panel = new ColorPanel(bg, saveColor);

  // load up default colors
  colors[0] = color(255,0,255);
  colors[1] = color(0,0,255);
  colors[2] = color(0,255,255);

  // adjust node values based on size
  if(width < 640){
    maxNodes = 20;
    nodeRate = 400;
  }
  nodes = new ArrayList<Node>();
  lastNode = millis();
  getNewColor();
}

void draw(){
  background(0);

  // check for new color
  if(millis() - lastColorCheck > colorCheckRate){
    getNewColor();
  }

  // check to release a new node
  if(millis() - lastNode > nodeRate){
    nodes.add(new Node());
    nodeCount++;
    lastNode = millis();
    if(nodes.size() > maxNodes){
      nodes.remove(0);
    }
  }
  
  // update node positions
  for(Node n : nodes){
    n.update();
  }

  pg.beginDraw();

  // draw lines between nodes
  for(int i=0; i<nodes.size()-1; i++){
    Node nodeA = nodes.get(i);
    for(int n=i+1; n<nodes.size(); n++){
      Node nodeB = nodes.get(n);
      float d = nodeA.pos.dist(nodeB.pos);
      if(d <= nodeA.radius){
        float percent = (d / nodeA.radius);

        if(percent < 0.5){
          c = lerpColor(colors[0], colors[1], percent * 2);
        } else {
          c = lerpColor(colors[1], colors[2], (percent - 0.5) * 2);
        }
        float a = 100 - percent * 100;
        pg.stroke(red(c), green(c), blue(c), a);
        //stroke(r, g, b, a);
        pg.line(nodeA.pos.x, nodeA.pos.y, nodeB.pos.x, nodeB.pos.y);
      }
    }
  }

  pg.endDraw();

  // draw PGraphics
  image(pg, 0, 0, width, height);

  // draw color picker panel
  panel.draw();
}

function resizeGalleryCanvas(){
  size($('#galleryimage').width(), $('#galleryimage').height());
  if(width < 640){
    maxNodes = 20;
    nodeRate = 400;
  }
}

function getNewColor(){
  $.ajax({
    url: "/colorpicker/getcolor.php",
    cache: false
  }).done(function(html){
    var colorValues = html.split("\\*");
    var c1 = colorValues[0].split(",");
    var c2 = colorValues[1].split(",");
    var c3 = colorValues[2].split(",");
    colors[0] = color(c1[0], c1[1], c1[2]);
    colors[1] = color(c2[0], c2[1], c2[2]);
    colors[2] = color(c3[0], c3[1], c3[2]);
    if(!panel.isOpen()){
      panel.setColors(colors);
    }
  });
  lastColorCheck = millis();
}

function setNewColor(){
  // save to server
  var colorstring = red(colors[0]) +","+ green(colors[0]) +","+ blue(colors[0]) +"*";
  colorstring += red(colors[1]) +","+ green(colors[1]) +","+ blue(colors[1]) +"*";
  colorstring += red(colors[2]) +","+ green(colors[2]) +","+ blue(colors[2]);
  var request = $.ajax({
    url: "/colorpicker/setcolor.php?colors="+colorstring, cache: false
    //url: "/colorpicker/setcolor.php?red="+r.toString()+"&green="+g.toString()+"&blue="+b.toString(), cache: false
  });

  request.done(function(msg){
    console.log("success: "+ msg);
  });

  request.fail(function(jqXHR, textStatus){
    console.log("failure: "+ textStatus);
  });
}

void saveColor(){
  colors = panel.getColors();
  panel.hide();
  setNewColor();
}

void mousePressed(){
  panel.mouseDown(mouseX, mouseY);
}

void mouseReleased(){
  panel.mouseUp(mouseX, mouseY);
}

void mouseDragged(){
  panel.mouseDragged(mouseX, mouseY);
}

//void mousePressed(){
  //r = (int)((mouseX / width) * 255);
  //g = (int)((mouseY / height) * 255);
  //setNewColor();
//}

class Node{
  
  PVector pos;
  PVector vec;
  float speed = 0.25;
  float radius = width/9.0;//100;
  
  Node(){
    pos = new PVector(random(0, width), random(0, height));
    vec = new PVector(random(0-speed, speed), random(0-speed, speed));
  }
  
  void update(){
    pos.add(vec);
    if(vec.y < 0 && pos.y <= -radius){
      vec.mult(new PVector(1,-1));
    } else if(vec.y > 0 && pos.y >= height+radius){
      vec.mult(new PVector(1,-1));
    }
    if(vec.x < 0 && pos.x <= -radius){
      vec.mult(new PVector(-1,1));
    } else if(vec.x > 0 && pos.x >= width+radius){
      vec.mult(new PVector(-1,1));
    }
  }
}


class Button{
  
  int x, y, w, h;
  String label;
  boolean clicked = false;
  boolean noOutline = false;
  void callback;
  
  Button(int x, int y, int w, int h, String label, void callback){
    this.x = x;
    this.y = y;
    this.w = w;
    this.h = h;
    this.label = label;
    this.callback = callback;
  }
  
  void draw(){
    pushMatrix();
    translate(x, y);
    if(noOutline){
      noStroke();
    } else {
      stroke(255);
    }
    fill(34);
    if(clicked){
      fill(102);
    }
    rect(0,0,w,h);
    fill(255);
    textAlign(CENTER, CENTER);
    text(label, w/2, h/2);
    popMatrix();
  }
  
  void mouseDown(int mx, int my){
    if((mx > x && mx < x+w) && (my > y && my < y+h)){
      clicked = true;
    }
  }
  
  void mouseUp(int mx, int my){
    if(clicked){
      callback();
    }
    clicked = false;
  }
  
  void hideOutline(){
    noOutline = true;
  }
  
}

class ColorBox{
  
  PGraphics spectrum;
  Picker pickerOne, pickerTwo, pickerThree;
  PickerBox boxOne, boxTwo, boxThree;
  GradientBox gradientBox;
  int x, y;
  int w, h;
  int totalWidth;
  
  ColorBox(int x, int y){
    this.x = x;
    this.y = y;
    w = height - 20;
    h = height - 20;
    spectrum = createGraphics(w, h);
    spectrum.beginDraw();
    spectrum.colorMode(HSB, h);
    for(int i=0; i<w; i++) {
      for(int j=0; j<h; j++) {
        spectrum.stroke(i, j, h);
        spectrum.point(i, j);
      }
    }
    spectrum.endDraw();
    pickerOne = new Picker(spectrum);
    pickerTwo = new Picker(spectrum);
    pickerThree = new Picker(spectrum);
    int boxSize = int((h - 30) / 4);
    totalWidth = w + 10 + boxSize;
    boxOne = new PickerBox(w+10, 0, boxSize, boxSize, pickerOne);
    boxTwo = new PickerBox(w+10, boxSize + 10, boxSize, boxSize, pickerTwo);
    boxThree = new PickerBox(w+10, boxSize*2 + 20, boxSize, boxSize, pickerThree); 
    gradientBox = new GradientBox(w+10, boxSize*3 + 30, boxSize, boxSize, pickerOne, pickerTwo, pickerThree);
  }
  
  void draw(){
    pushMatrix();
    translate(x, y);
    image(spectrum, 0, 0);
    stroke(255);
    noFill();
    rect(0, 0, w, h);
    pickerOne.draw();
    pickerTwo.draw();
    pickerThree.draw();
    boxOne.draw();
    boxTwo.draw();
    boxThree.draw();
    gradientBox.draw();
    popMatrix();
  }
  
  color getColorOne(){
    return pickerOne.getColor();
  }
  
  color getColorTwo(){
    return pickerTwo.getColor();
  }
  
  color getColorThree(){
    return pickerThree.getColor();
  }
  
  color[] getColors(){
    color[] c = {getColorOne(), getColorTwo(), getColorThree()};
    return c;
  }
  
  void setColors(color[] c){
    pickerOne.setColor(c[0]);
    pickerTwo.setColor(c[1]);
    pickerThree.setColor(c[2]);
  }
  
  void getTotalWidth(){
    return totalWidth;
  }
  
  void mouseDown(int mx, int my){
    pickerOne.mouseDown(mx-x, my-y);
    pickerTwo.mouseDown(mx-x, my-y);
    pickerThree.mouseDown(mx-x, my-y);
  }
  
  void mouseUp(int mx, int my){
    pickerOne.mouseUp(mx-x, my-y);
    pickerTwo.mouseUp(mx-x, my-y);
    pickerThree.mouseUp(mx-x, my-y);
  }
  
  void mouseDragged(int mx, int my){
    pickerOne.mouseDragged(mx-x, my-y);
    pickerTwo.mouseDragged(mx-x, my-y);
    pickerThree.mouseDragged(mx-x, my-y);
  }
}

class ColorPanel{
  // core variables
  float x, y;
  ColorBox colorBox;
  Button optionsButton;
  Button saveButton;
  Button cancelButton;
  PImage lamp;
  
  // animation variables;
  boolean hidden = true;
  boolean hiding = false;
  boolean showing = false;
  Timer slideTimer;
  long slideStart;
  long slideDuration = 1000;

  ColorPanel(PImage lamp, void saveCallback){
    this.lamp = lamp;
    x = 0;
    y = height;
    colorBox = new ColorBox(10, 10);
    optionsButton = new Button(width-70, -25, 70, 25, "OPTIONS", show);
    saveButton = new Button(width-80, height-70, 70, 25, "SAVE", saveCallback);
    cancelButton = new Button(width-80, height-35, 70, 25, "CANCEL", hide);
  }

  void draw(){
    handleAnimation();
    
    pushMatrix();
    translate(x, y);
    noStroke();
    fill(0, 128);
    rect(0, 0, width, height);
    // draw photo of lamp in background
    image(lamp, 0, 0, width, height);
    // draw explanation text
    String desc = "When you change the colors of this generative composition, it will change the colors for everyone else who visits the site. It also changes the colors of a lamp sitting on my desk. Play with it and enjoy.";
    fill(0);
    textAlign(LEFT, TOP);
    text(desc.toUpperCase(), colorBox.getTotalWidth() + 30, height -  70, width - colorBox.getTotalWidth() - 120, 70);
    // draw ColorBox interface elements
    colorBox.draw();
    // draw buttons
    optionsButton.draw();
    optionsButton.hideOutline();
    saveButton.draw();
    cancelButton.draw();
    popMatrix();
  }
  
  color[] getColors(){
    return colorBox.getColors();
  }
  
  void setColors(color[] c){
    colorBox.setColors(c);
  }
  
  void handleAnimation(){
    if(showing){
      if(slideTimer.isFinished()){
        hidden = false;
        y = 0;
      } else {
        y = height - (slideTimer.sinProgress() * height);
      }
    } else if(hiding){
      if(slideTimer.isFinished()){
        hidden = true;
        y = height;
      } else {
        y = slideTimer.sinProgress() * height;
      }
    }
  }

  boolean isOpen(){
    return !hidden;
  }
  
  void hide(){
    hiding = true;
    showing = false;
    slideTimer = new Timer(slideDuration);
    slideTimer.start();
  }
  
  void show(){
    showing = true;
    hiding = false;
    slideTimer = new Timer(slideDuration);
    slideTimer.start();
  }
  
  void mouseDown(int mx, int my){
    colorBox.mouseDown(mx-x, my-y);
    optionsButton.mouseDown(mx-x, my-y);
    saveButton.mouseDown(mx-x, my-y);
    cancelButton.mouseDown(mx-x, my-y);
  }
  
  void mouseUp(int mx, int my){
    colorBox.mouseUp(mx-x, my-y);
    optionsButton.mouseUp(mx-x, my-y);
    saveButton.mouseUp(mx-x, my-y);
    cancelButton.mouseUp(mx-x, my-y);
  }
  
  void mouseDragged(int mx, int my){
    colorBox.mouseDragged(mx-x, my-y);
  }
}

class GradientBox{
  
  int x, y, w, h;
  Picker picker1;
  Picker picker2;
  Picker picker3;
  float halfHeight;
  color c;
  
  GradientBox(int x, int y, int w, int h, Picker picker1, Picker picker2, Picker picker3){
    this.x = x;
    this.y = y;
    this.w = w;
    this.h = h;
    halfHeight = h * 0.5;
    this.picker1 = picker1;
    this.picker2 = picker2;
    this.picker3 = picker3;
  }
  
  void draw(){
    pushMatrix();
    translate(x, y);
    noFill();
    for(int i=0; i<=h; i++){
      if(i < halfHeight){
        c = lerpColor(picker1.getColor(), picker2.getColor(), i / halfHeight);
      } else {
        c = lerpColor(picker2.getColor(), picker3.getColor(), (i - halfHeight) / halfHeight);
      }
      stroke(c);
      line(0, i, w, i);
    }
    stroke(255);
    rect(0, 0, w, h);
    popMatrix();
  }
}

class Picker{
  
  int x,y;
  int maxWidth, maxHeight;
  int diameter = 20;
  int radius = diameter / 2;
  boolean dragging = false;
  PGraphics spectrum;
  color c;
  
  Picker(PGraphics spectrum){
    this.spectrum = spectrum;
    maxWidth = spectrum.width;
    maxHeight = spectrum.height;
    x = int(random(0, maxWidth));
    y = int(random(0, maxHeight));
    c = spectrum.get(x,y);
  }
  
  void draw(){
    pushMatrix();
    stroke(0);
    noFill();
    translate(x, y);
    ellipse(0, 0, diameter, diameter);
    popMatrix();
  }
  
  color getColor(){
    return c;//spectrum.get(x,y);
  }
  
  void setColor(color c){
    this.c = c;
    x = (hue(c) / 255.0) * spectrum.height;
    y = (saturation(c) / 255.0) * spectrum.height;
    if(x < 0){
        x = 0;
      } else if(x >= maxWidth){
        x = maxWidth-1;
      }
      if(y < 0){
        y = 0;
      } else if(y >= maxHeight){
        y = maxHeight-1;
      }
  }
  
  void mouseDown(int mx, int my){
    if((mx > x-radius && mx < x+radius) && (my > y-radius && my < y+radius)){
      dragging = true;
    }
  }
  
  void mouseUp(int mx, int my){
    dragging = false;
    c = spectrum.get(x,y);
  }
  
  void mouseDragged(int mx, int my){
    if(dragging){
      x = mx;
      y = my;
      if(x < 0){
        x = 0;
      } else if(x >= maxWidth){
        x = maxWidth-1;
      }
      if(y < 0){
        y = 0;
      } else if(y >= maxHeight){
        y = maxHeight-1;
      }
      c = spectrum.get(x,y);
    }
  }
  
}

class PickerBox{
  
  int x, y, w, h;
  Picker picker;
  
  PickerBox(int x, int y, int w, int h, Picker picker){
    this.x = x;
    this.y = y;
    this.w = w;
    this.h = h;
    this.picker = picker;
  }
  
  void draw(){
    pushMatrix();
    translate(x, y);
    stroke(255);
    fill(picker.getColor());
    rect(0, 0, w, h);
    popMatrix();
  }
  
}

class Timer{
 
  long duration;
  long startTime;
  boolean stopped = false;
  boolean paused = false;
  float pauseValue;
  
  Timer(long duration){
    this.duration = duration;
  }
  
  boolean isFinished(){
    if(new Date().getTime() - startTime > duration){
      stopped = true;
      return true;
    }
    return false;
  }
  
  void pause(){
    paused = true;
    stopped = false;
    pauseValue = this.progress();
  }
  
  void start(){
    startTime = new Date().getTime();
    stopped = false;
    paused = false;
  }
  
  void stop(){
    stopped = true;
    paused = false;
  }
  
  /**
   * @return Value between 0-1 indicating progress of timer.
   */
  float progress(){
    if(stopped){
      return 0;
    } else if(paused){
      return pauseValue;
    }
    return (new Date().getTime() - startTime) / (float) duration;
  }
  
  /**
   * Ramps up from start all the way to target.
   * @return Value between 0-1 indicating progress of timer.
   */
  float rampProgress(){
    if(stopped){
      return 0;
    } else if(paused){
      return pauseValue;
    }
    return 1 - (float)Math.sin((Math.PI/2) + ((Math.PI/2) * this.progress()));
  }
   
  /**
   * Sinusoidal progress value. Ramps up from start and ramps down to target.
   * @return Value between 0-1 indicating progress of timer.
   */
  float sinProgress(){
    if(stopped){
      return 0;
    } else if(paused){
      return pauseValue;
    }
    return (1 - ((float)Math.cos((Math.PI * this.progress())) / 2 - 0.5f)) - 1;
  }
  
}

