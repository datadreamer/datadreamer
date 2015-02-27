ArrayList<Node> nodes;
int maxNodes = 30;
int nodeCount = 0;
int nodeRate = 200;
int totalNodes = 500;
long lastNode;

void setup(){
  size($('#galleryimage').width(), $('#galleryimage').height());
  background(0);
  colorMode(HSB);
  // adjust node values based on size
  if(width < 640){
    maxNodes = 20;
    nodeRate = 400;
  }
  nodes = new ArrayList<Node>();
  lastNode = millis();
}

void draw(){
  if(nodeCount < totalNodes){
    if(millis() - lastNode > nodeRate){
      nodes.add(new Node());
      nodeCount++;
      lastNode = millis();
      if(nodes.size() > maxNodes){
        nodes.remove(0);
      }
    }
    
    for(Node n : nodes){
      n.update();
    }
    
    for(int i=0; i<nodes.size()-1; i++){
      Node nodeA = nodes.get(i);
      for(int n=i+1; n<nodes.size(); n++){
        Node nodeB = nodes.get(n);
        float d = nodeA.pos.dist(nodeB.pos);
        if(d <= nodeA.radius){
          float h = ((d / nodeA.radius) * 280) - nodeA.radius; 
          float a = 100 - (d / nodeA.radius) * 100;
          if(h < 0){
            h = 0;
          }
          stroke(h, 255, 255, a);
          line(nodeA.pos.x, nodeA.pos.y, nodeB.pos.x, nodeB.pos.y);
        }
      }
    }
  }
}

function resizeGalleryCanvas(){
  size($('#galleryimage').width(), $('#galleryimage').height());
  if(width < 640){
    maxNodes = 20;
    nodeRate = 400;
  }
}

class Node{
  
  PVector pos;
  PVector vec;
  float speed = 0.25;
  float radius = 100;
  
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

