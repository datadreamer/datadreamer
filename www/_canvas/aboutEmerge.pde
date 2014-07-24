/* @pjs preload="/_canvas/eyes_blue.jpg,/_canvas/weirdline.png,/_canvas/weirdline2.png,/_canvas/weirdline3.png"; */

PImage eyes;
PImage[] textures = new PImage[3];
Animation animation;

void setup(){
  size($('#galleryimage').width(), $('#galleryimage').height());
  frameRate(30);
  noStroke();
  imageMode(CENTER);
  background(0);
  
  eyes = loadImage("/_canvas/eyes_blue.jpg");
  // resize image to fit div
  eyes.resize(width, height);
  textures[0] = loadImage("/_canvas/weirdline.png");
  textures[1] = loadImage("/_canvas/weirdline2.png");
  textures[2] = loadImage("/_canvas/weirdline3.png");
  
  animation = new SoftAnimation(textures);
  animation.setImage(eyes);
  animation.start();
}

void draw(){
  animation.draw();
  animation.update();
}

public interface Animation{
  
  public void draw();
  public void update();
  public void reset();
  public void start();
  public void stop();
  public void setImage(PImage image);
  public int particleCount();
  
}
class Particle{
 
  int id;
  PVector locs[] = new PVector[10];
  PVector startLoc;
  PVector vec = new PVector();
  PVector perlin = new PVector();
  float radius;
  float damping = 0.99f;
  float mass = 1;
  int currentColor = 0;
  int pastColor = 0;
  int colorHistoryLength = 10;
  int colors[] = new int[colorHistoryLength];
  int red[] = new int[colorHistoryLength];
  int green[] = new int[colorHistoryLength];
  int blue[] = new int[colorHistoryLength];
  float alpha;
  int age = 0;
  int lifespan;
  float rotation = 0;
  float rotationSpeed = 0;
  PImage texture;
  float size = 1;
  boolean ISDEAD = false;
  
  Particle(int id, float x, float y){
    this.id = id;
    startLoc = new PVector(x, y);
    for(int i=0; i<locs.length; i++){
      locs[i] = startLoc;
    }
    lifespan = (int)random(300,600);
  }
  
  void applyForce(float xForce, float yForce){
    vec.add(xForce, yForce, 0);
  }
  
  void applyForce(PVector force){
    vec.add(force);
  }
  
  void destroy(){
    ISDEAD = true;
  }
  
  PVector getLoc(){
    return locs[0];
  }
  
  float getX(){
    return locs[0].x;
  }
  
  float getY(){
    return locs[0].y;
  }
  
  void move(){
    for(int i=locs.length-1; i>0; i--){
      locs[i].set(locs[i-1]);
    }
    locs[0].add(vec);
    vec.mult(damping);
    rotation += rotationSpeed;
    age++;
    if(age == lifespan){
      ISDEAD = true;
    }
  }
  
  void setColor(int newColor){
    pastColor = currentColor;
    currentColor = newColor;
    for(int i=colors.length-1; i>0; i--){
      colors[i] = colors[i-1];
    }
    colors[0] = currentColor;
  }
  
}
public class SoftAnimation implements Animation {
  
  private PImage img;
  private PImage[] textures;
  private ArrayList<Particle> particles;
  private ArrayList<Particle> deadParticles;
  private int particlesPerFrame = 1;
  private int particleIndex;
  private float particleMinSize = 20;
  private float particleMaxSize = 400;
  private float particleMinAlpha = 5;
  private float particleMaxAlpha = 20;
  private int particleMinDuration = 200;
  private int particleMaxDuration = 600;
  private float particleMinForce = -2;
  private float particleMaxForce = 2;
  private float animationDuration = 30000;
  private float sketchDuration = 120000;
  private long startTime;
  private boolean stopped = false;
  
  public SoftAnimation(PImage[] textures){
    this.textures = textures;
    particles = new ArrayList<Particle>();
    deadParticles = new ArrayList<Particle>();
  }
  
  public void draw(){
    for(Particle p : particles){
      if(img != null){
        float r = red(img.get((int)p.getX(), (int)p.getY()));
        float g = green(img.get((int)p.getX(), (int)p.getY()));
        float b = blue(img.get((int)p.getX(), (int)p.getY()));
        float a = 0;
        if(p.age < p.lifespan*0.5){
          a = (float)(p.age / (p.lifespan*0.5)) * p.alpha;
        } else {
          a = (float)(((p.lifespan*0.5) - (p.age - (p.lifespan*0.5))) / (p.lifespan*0.5)) * p.alpha;
        }
        
        // draw the texture
        pushMatrix();
        translate(p.getX(), p.getY());
        rotate(p.rotation);
        tint(r, g, b, a);
        image(p.texture, 0, 0, p.size, 2);
        popMatrix();
      }
    }
  }
  
  public int particleCount(){
    return particles.size();
  }
  
  public void reset(){
    particles.clear();
    deadParticles.clear();
  }
  
  public void setImage(PImage img){
    this.img = img;
  }
  
  public void start(){
    particleIndex = 0;
    startTime = millis();
  }
  
  public void stop(){
    reset();
    stopped = true;
  }
  
  public void update(){
    if(!stopped){
      for(int i=0; i<particlesPerFrame; i++){
        float xpos = random(width);
        float ypos = random(height);
        float a = random(particleMinAlpha, particleMaxAlpha);
        float xv = random(particleMinForce, particleMaxForce);
        float yv = random(particleMinForce, particleMaxForce);
        int lifespan = (int)random(particleMinDuration, particleMaxDuration);
        float percentage = 1 - ((millis() - startTime) / animationDuration);
        float size = random(particleMinSize, particleMaxSize);
        if(millis() - startTime < animationDuration){
          size *= percentage;
        } else {
          size = particleMinSize;
        }
        PImage texture = textures[(int)(random(1) * textures.length)];
        Particle p = new Particle(particleIndex, xpos, ypos);
        p.setColor(img.get((int)p.getX(), (int)p.getY()));
        p.alpha = a;
        p.applyForce(xv, yv);
        p.lifespan = lifespan;
        p.size = size;
        p.texture = texture;
        p.rotationSpeed = random(-0.01, 0.01);
        particles.add(p);
        particleIndex++;
      }
      
      // process particle movement
      for(Particle p : particles){
        p.move();
        if(p.ISDEAD){
          deadParticles.add(p);
        }
      }
      
      // remove dead particles
      for(Particle p : deadParticles){
        particles.remove(p);
      }
      deadParticles.clear();
      
      // check if it's time to kill the sketch
      if(millis() - startTime >= sketchDuration){
        this.stop();
      }
    }
  }
  
}

