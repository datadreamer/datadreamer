<!-- HEADER CONTAINS TITLE AND MENU -->

<header>
  <div id="logo" class="noselect">
    <a href="/">datadreamer</a>
  </div>
  <button type="button" class="btn-navbar" onclick="toggleMenu()">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
  <div id="sublogo" class="noselect">
    The work of <a href="/about">Aaron Siegel</a>
  </div>
</header>

<!-- MENU IS FIXED AND DISPLAY IS TOGGLED -->

<div id="menu" class="noselect">
  <hr>
  <div id="menutitles">
    <div class="menutitle" id="tweetmenutitle">
      <div class="menutitletext">
        Tweets
      </div>
    </div>
    <div class="menutitle" id="postmenutitle">
      <div class="vr vr2"></div>
      <div class="menutitletext">
        Posts
      </div>
    </div>
    <div class="menutitle" id="tagmenutitle">
      <div class="vr vr2"></div>
      <div class="menutitletext">
        Tags
      </div>
    </div>
    <div class="menutitle" id="mainmenutitle">
      <div class="vr vr2"></div>
      <div class="menutitletext">
        Menu
      </div>
    </div>
  </div>

  <!-- tweets -->

  <div class="submenu" id="tweetmenu">
    <?php
      // list all tweets fetched when the page loaded.
      foreach ($statuses as $status){
        echo "<a href='http://twitter.com/datadreamer/status/{$status->id_str}'>";
        echo "<div class='submenuitem'>";
        echo "<div class='submenutext'>$status->text</div>";
        echo "</div></a>";
      }
    ?>

  </div>

  <!-- posts -->

  <div class="submenu" id="postmenu">
    <div class="vr"></div>
    <div class="submenuitemholder">
      <?php
        // list all blog posts as buttons to open permalinks.
        $result = mysql_query("SELECT title,link,r,g,b FROM posts ORDER BY id DESC");
        while($row = mysql_fetch_assoc($result)){
          $title = $row['title'];
          $link = $row['link'];
          $r = $row['r'];
          $g = $row['g'];
          $b = $row['b'];
          echo "<a href='http://www.datadreamer.com/blog/{$link}'>";
          echo "<div class='submenuitem'>";
          echo "<div class='submenutext' style='color:rgb({$r},{$g},{$b});'>{$title}</div>";
          echo "</div></a>";
        }
      ?>
    </div>
  </div>

  <!-- tags -->

  <div class="submenu" id="tagmenu">
    <div class="vr"></div>
    <div class="submenuitemholder">
      <?php
        // list all tags as buttons to open list of relavent posts.
        $result = mysql_query("SELECT tag, count(tag) AS num FROM tags GROUP BY tag ORDER BY tag ASC");
        while($row = mysql_fetch_assoc($result)){
          $tag = $row['tag'];
          $num = $row['num'];
          echo "<a href='/blog/tag/{$tag}'>";
          echo "<div class='submenuitem tagmenuitem'>";
          echo "<div class='submenutext tagmenutext'>{$tag} <span class='tagnum'>{$num}</span></div>";
          echo "</div></a>";
        }
      ?>
    </div>
  </div>

  <!-- main menu -->

  <div class="submenu" id="mainmenu">
    <div class="vr"></div>
    <nav class="submenuitemholder">
      <a href="/">
        <div class="submenuitem mainmenuitem">
          <div class="submenutext mainmenutext">Home</div>
        </div>
      </a>
      <a href="http://datadreamer.com/blog">
        <div class="submenuitem mainmenuitem">
          <div class="submenutext mainmenutext">Blog</div>
        </div>
      </a>
      <a href="http://datadreamer.com/about">
        <div class="submenuitem mainmenuitem">
          <div class="submenutext mainmenutext">About</div>
        </div>
      </a>
      <a href="http://datadreamer.com/contact">
        <div class="submenuitem mainmenuitem">
          <div class="submenutext mainmenutext">Contact</div>
        </div>
      </a>
    </nav>
  </div>

</div>
