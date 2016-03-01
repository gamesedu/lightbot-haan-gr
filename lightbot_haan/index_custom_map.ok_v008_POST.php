<?php
/*
20150712-POST /REQUEST
light bot - custom map load with get
?map_data={"direction":0,"position":{"x":0,"y":0},"map":[[{"h":1,"t":"l"}................,]],"medals":{"gold":3,"silver":4,"bronze":5}}

*/
if(!isSet($map_file))$map_file="maps.txt"; ;
//$map_file="maps_easy.txt";
if(isSet($_GET["map"])){$map_file=$_GET["map"];}

?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>LightBot v0.9 ΕΛΛΗΝΙΚΑ</title>
    <link rel="shortcut icon" href="img/favicon.ico" />
    <link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Lato:400,900' rel='stylesheet' type='text/css' />
    <link type="text/css" href="css/smoothness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
    <link type="text/css" href="css/jplayer.css" rel="stylesheet"  />
    <link type="text/css" href="css/lightbot.css" rel="stylesheet" />
    <script type="text/javascript" src="js/jquery-1.7.min.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
    <script type="text/javascript" src="js/jquery.jplayer.min.js"></script>
    <script type="text/javascript" src="js/jquery.history.js"></script>
    <script type="text/javascript" src="js/lightbot.model.game.js"></script>
    <script type="text/javascript" src="js/lightbot.model.directions.js"></script>
    <script type="text/javascript" src="js/lightbot.model.bot.js"></script>
    <script type="text/javascript" src="js/lightbot.model.bot.instructions.js"></script>
		<!-- <script type="text/javascript" src="js/lightbot.model.map.js"></script> -->
<!-- ##########################START of js/lightbot.model.map.js ############################ -->		
		<script type="text/javascript">
/*jsl:option explicit*/
/*jsl:import lightbot.model.game.js*/

(function() {
  var map = {};

  var maps = null; // storage for all the maps loaded from the server
    var defaultMapData = [{
      "direction": 0,
		"position": {"x": 4, "y": 5},
      "map": [
[{"h":3, "t":"b"},{"h":1, "t":"b"},{"h":1, "t":"b"},{"h":1, "t":"b"},{"h":1, "t":"b"}],
[{"h":1, "t":"b"},{"h":1, "t":"b"},{"h":1, "t":"b"},{"h":1, "t":"b"},{"h":1, "t":"b"}],
[{"h":1, "t":"l"},{"h":1, "t":"l"},{"h":1, "t":"l"},{"h":1, "t":"l"},{"h":1, "t":"l"}],
[{"h":1, "t":"l"},{"h":1, "t":"b"},{"h":1, "t":"b"},{"h":1, "t":"b"},{"h":1, "t":"l"}],
[{"h":1, "t":"l"},{"h":1, "t":"l"},{"h":1, "t":"l"},{"h":1, "t":"l"},{"h":1, "t":"l"}],
[{"h":1, "t":"b"},{"h":1, "t":"b"},{"h":1, "t":"b"},{"h":1, "t":"b"},{"h":1, "t":"b"}]
      ],
      "medals": {
        "gold": 3,
        "silver": 4,
        "bronze": 5
      }
    }];
<?php
//added by jon 20150711 (gets 1 map from GET)
if($_REQUEST["map_data"]!=null) {
echo '
defaultMapData = ['.$_REQUEST["map_data"].'

];
';

}
 ?>
  var levelSize = {'x': 0, 'y': 0}; // the level size
  var mapRef = null; // the actual map values
  var medals = null; // medals for the level
  var levelNumber = null; // what level is the user currently playing

  map.loadMaps = function() {
    <?php echo "$.getJSON('maps/".$map_file."', function(data){"; ?>
	 <?php //echo "$.getJSON('maps/maps.txt', function(data){"; ?>
      maps = defaultMapData;
    });
  };

  map.loadMap = function(x) {
    if (!maps) {
      console.error('Map list is empty');
    } else {
      // set the level number
      levelNumber = x;

      // set the bot starting direction
      lightBot.bot.init(maps[x].direction, maps[x].position);

      // set the level medals
      medals = maps[x].medals;

      // map files are defined user-friendly so we have to adapt to that
      levelSize.x = maps[x].map[0].length; // we suppose map is a rectangle
      levelSize.y = maps[x].map.length;

      mapRef = new Array(levelSize.x);
      for (i = 0; i < levelSize.x; i++) {
        mapRef[i] = new Array(levelSize.y);
      }

      var botInMap = false;
      var nbrLights = 0;

      for (var i = 0; i < maps[x].map.length; i++){
        for (var j = 0; j < maps[x].map[i].length; j++) {
          switch (maps[x].map[i][j].t) {
            case 'b':
              mapRef[j][maps[x].map.length - i - 1] = new lightBot.Box(maps[x].map[i][j].h, j, maps[x].map.length - i - 1);
              break;
            case 'l':
              mapRef[j][maps[x].map.length - i - 1] = new lightBot.LightBox(maps[x].map[i][j].h, j, maps[x].map.length - i - 1);
              nbrLights++;
              break;
            default:
              // output error and fall back to box element
              console.error('Map contains unsupported element: ' + maps[x].map[i][j].t);
              mapRef[j][maps.map.length - i - 1] = new lightBot.Box(maps[x].map[i][j].h, j, maps[x].map.length - i - 1);
              break;
          }
        }
      }

      if (nbrLights === 0) {
        console.error('No light defined in map');
      }
    }
  };

  map.reset = function() {
    lightBot.bot.reset();
    for (var i = 0; i < levelSize.x; i++) {
      for (var j = 0; j < levelSize.y; j++) {
        mapRef[i][j].reset();
      }
    }
  };

  /* getters and setters */
  map.ready = function() {
    return levelNumber !== null;
  };

  map.getLevelSize = function() {
    return levelSize;
  };

  map.getMapRef = function() {
    return mapRef;
  };

  map.getMedals = function() {
    return medals;
  };

  map.getLevelNumber = function() {
    return levelNumber;
  };

  map.getNbrOfLevels = function() {
    return maps.length;
  };

  map.complete = function() {
    levelNumber = null; // by setting levelNumber to null, the map is marked as completed
  };

  lightBot.map = map;
})();
</script>
<!-- ##########################END ############################ -->
		<!--<script type="text/javascript" src="lightbot.model.easy_map.js.php"></script>-->
    <script type="text/javascript" src="js/lightbot.model.map.state.js"></script>
    <script type="text/javascript" src="js/lightbot.model.box.js"></script>
    <script type="text/javascript" src="js/lightbot.model.lightbox.js"></script>
    <script type="text/javascript" src="js/lightbot.model.medals.js"></script>
    <script type="text/javascript" src="js/lightbot.model.achievements.js"></script>
    <script type="text/javascript" src="js/lightbot.view.canvas.js"></script>
    <script type="text/javascript" src="js/lightbot.view.canvas.ui.js"></script>
    <script type="text/javascript" src="js/lightbot.view.canvas.ui.media.js"></script>
    <script type="text/javascript" src="js/lightbot.view.canvas.ui.buttons.js"></script>
    <script type="text/javascript" src="js/lightbot.view.canvas.ui.dialogs.js"></script>
    <script type="text/javascript" src="js/lightbot.view.canvas.ui.editor.js"></script>
    <script type="text/javascript" src="js/lightbot.view.canvas.ui.history.js"></script>
    <script type="text/javascript" src="js/lightbot.view.canvas.game.js"></script>
    <script type="text/javascript" src="js/lightbot.view.canvas.map.js"></script>
    <script type="text/javascript" src="js/lightbot.view.canvas.box.js"></script>
    <script type="text/javascript" src="js/lightbot.view.canvas.bot.animations.js"></script>
    <script type="text/javascript" src="js/lightbot.view.canvas.bot.js"></script>
    <script type="text/javascript" src="js/lightbot.view.canvas.projection.js"></script>
    <script type="text/javascript" src="js/lightbot.view.canvas.medals.js"></script>
    <script type="text/javascript" src="js/lightbot.view.canvas.achievements.js"></script>
  </head>
  <body>
    <div id="audioContainer">
      <div id="audioPlayer"></div>
    </div>

    <a href="http://github.com/haan" target="_blank"><img style="position: absolute; top: 0; right: 0; border: 0;" src="../img/github.png" alt="Fork me on GitHub"></a>

    <div id="lightbot">

      <div id="credits">Developed by <a href="http://www.haan.lu" target="_blank">Laurent Haan</a>. Interface by <a href="http://zenobiahoman.daportfolio.com/">Zenobia Homan</a>. Sprite by <a href="http://www.pixeljoint.com/forum/member_profile.asp?PF=2146">surt</a>. Music by <a href="http://hektikmusic.newgrounds.com/" target="_blank">hektikmusic</a>. Original concept by <a href="http://coolio-niato.newgrounds.com/" target="_blank">coolio niato</a>.</div>

      <div id="welcomeScreen" class="ui-screen">
        <button class="helpButton">Help</button>
        <button class="achievementsButton">Βραβεία</button>
        <button class="levelSelectButton ui-state-highlight">Αρχή / Start Game</button>
        <button class="audioToggleButton">Ήχος</button>
      </div>

      <div id="achievementsScreen" class="ui-screen ui-helper-hidden">
        <h1>Βραβεία</h1>
        <ul id="achievementsList">
        </ul>
        <button class="mainMenuButton">Αρχικό Μενου / Main Menu</button>
      </div>

      <div id="helpScreen" class="ui-screen ui-helper-hidden">
        <div id="helpScreenAccordionContainer">
          <div id="helpScreenAccordion">
            <h3><a href="#goal">Σκοπός Παιχνιδιού / Goal of the game</a></h3>
            <div>
              <p>
              In order to complete the game, you have to tell the robot how to light up all the light tiles in a given level. However, your only way of interacting with the robot is by assembling instructions into a program that the robot can execute.
              </p>
            </div>
            <h3><a href="#howto">How to play the game</a></h3>
            <div>
              <p>
              You can create a program by dragging instructions from the instruction list and dropping them into the program frame. The instructions will automatically be added to the bottom of the highlighted block.
              </p>
              <p>Execute your program by clicking the Run button. If you are not satisfied with your current program, you can interrupt the execution at any moment by clicking the Stop button. This will reset the robot to his initial position.
              </p>
            </div>
            <h3><a href="#objects">Game objects</a></h3>
            <div>
              <p>
              A level is made up from gray tiles which have a certain height. Special <em>light</em> tiles are scattered throughout the level. These light tiles can either be blue, which means that they are unlit, or they can be yellow, which means that they are lit. If at any given moment, all the light tiles in a level are lit, you have completed that level.
              </p>
            </div>
            <h3><a href="#walk_forward">Instruction: walk forward</a></h3>
            <div>
              <p>
              When walking forward, the robot will will advance one square in the direction it is currently facing. This movement will only be performed if the space it would be heading into is of the same height as the square it is moving out from. In any other case the movement will not be performed.
              </p>
            </div>
            <h3><a href="#turn_right">Instruction: Turn 90&deg; to the right</a></h3>
            <div>
              <p>
              When turning 90&deg; to the right, the robot will stay in place and turn 90&deg; (a quarter turn) to the right (clockwise).
              </p>
            </div>
            <h3><a href="#turn_left">Instruction: Turn 90&deg; to the left</a></h3>
            <div>
              <p>
              When turning 90&deg; to the left, the robot will stay in place and turn 90&deg; (a quarter turn) to the left (anti-clockwise).
              </p>
            </div>
            <h3><a href="#jump">Instruction: Jump</a></h3>
            <div>
              <p>
              Jumping is a combination of a move forward and a change in height. The direction of the movement is in the direction that the robot is facing. An upward jump is only successful if the destination tile is higher by exactly one step than the starting tile. If the height difference is bigger than one, the jump is not successful and no movement is performed. When jumping down, there is no limit to the height the robot can jump down from. The only condition is that the difference in height is at least one.
              </p>
            </div>
            <h3><a href="#light">Instruction: Light</a></h3>
            <div>
              <p>
              The light instruction can be used to toggle light tiles on or off. If the robot is located on an unlit light tile when the light instruction is executed, the light tile will be toggled on. However, if the robot is located on an already lit light tile, that light tile will be toggled off. When the robot is located on a normal tile, nothing happens.
              </p>
            </div>
            <h3><a href="#repeat">Instruction: Repeat</a></h3>
            <div>
              <p>
              The repeat instruction is a special instruction that can be used to repeat some instructions a certain number of times. The repeat instruction has a special frame where you can drop instructions from the instruction list. It also has a counter where you can define the number of times the instructions within the repeat instruction will be repeated. It is even possible to place a repeat instruction within a repeat instruction, which is essential for creating very small programs.
              </p>
            </div>
            <h3><a href="#medals">Medals</a></h3>
            <div>
              <p>
              Medals are rewarded for completing levels with only a very small amount of instructions. Sometimes, these small programs make the robot execute a lot of useless instructions, which is very inefficient and takes a lot of time. Please be aware that in computer science, the <em>best</em> program is not the one that <em>contains</em> the least amount of instructions but the one that has the robot <em>execute</em> the least amount of instructions.
              </p>
            </div>
          </div>
        </div>
        <div id="helpScreenVerticalBar"></div>
        <div id="videoContainer" class="jp-video jp-video-300p">
          <div class="jp-type-single">
            <div id="videoPlayer" class="jp-jplayer"></div>
            <div class="jp-gui">
              <div class="jp-video-play">
                <a href="javascript:;" class="jp-video-play-icon" tabindex="1">play</a>
              </div>
              <div class="jp-interface">
                <div class="jp-progress">
                  <div class="jp-seek-bar">
                    <div class="jp-play-bar"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <button class="mainMenuButton">ΑΡΧΙΚΟ ΜΕΝΟΥ / Main Menu</button>
      </div>

      <div id="levelSelectScreen" class="ui-screen ui-helper-hidden">
        <h1>ΕΠΙΛΟΓΗ ΕΠΙΠΕΔΟΥ / Level select</h1>
        <ul id="levelList">
        </ul>
        <button class="mainMenuButton">ΑΡΧΙΚΟ ΜΕΝΟΥ / Main Menu</button>
      </div>

      <div id="gameScreen" class="ui-screen ui-helper-hidden">
        <div id="canvasContainer">
          <canvas id="gameCanvas" width="690" height="666"></canvas>
        </div>
        <div id="buttonContainer">
          <button class="levelSelectButton">ΠΙΣΩ / Back</button>
          <button class="audioToggleButton">ΗΧΟΣ / Toggle Audio</button>
          <button id="clearButton">ΚΑΘΑΡΙΣΜΟΣ / Clear</button>
          <button id="runButton">ΕΝΑΡΞΗ / Run</button>
        </div>
        <div id="instructionsContainer">
          <h2>ΟΔΗΓΙΕΣ - Instructions</h2>
          <div>
            <ul>
                <li><p class="walk"><span class="ui-icon ui-icon-arrowthick-1-n" style="float: left;"></span>ΜΠΡΟΣΤΑ - Walk forward<span class="ui-icon ui-icon-close" style="float: right;"></span></p></li>
                <li><p class="turnRight"><span class="ui-icon ui-icon-arrowreturnthick-1-w flip" style="float: left;"></span>ΔΕΞΙΑ - Turn 90&deg; to the right<span class="ui-icon ui-icon-close" style="float: right;"></span></p></li>
                <li><p class="turnLeft"><span class="ui-icon ui-icon-arrowreturnthick-1-w" style="float: left;"></span>ΑΡΙΣΤΕΡΑ - Turn 90&deg; to the left<span class="ui-icon ui-icon-close" style="float: right;"></span></p></li>
                <li><p class="jump"><span class="ui-icon ui-icon-arrowthickstop-1-n" style="float: left;"></span>ΠΗΔΑ - Jump<span class="ui-icon ui-icon-close" style="float: right;"></span></p></li>
                <li><p class="light"><span class="ui-icon ui-icon-lightbulb" style="float: left;"></span>ΑΝΑΨΕ - Light<span class="ui-icon ui-icon-close" style="float: right;"></span></p></li>
                <li><p class="repeat"><span class="ui-icon ui-icon-refresh" style="float: left;"></span>ΕΠΑΝΑΛΑΒΕ - Repeat <span><input type="number" min="1" max="99" step="1" value="2"> ΦΟΡΕΣ/times</span><span class="ui-icon ui-icon-close" style="float: right;"></span></p>
                  <div class="droppable">
                    <div class="ui-widget-content">
                      <ul>
                        <li class="placeholder"><p class="placeholder">ΤΡΑΒΗΞΤΕ ΜΕ ΤΟ ΠΟΝΤΙΚΙ ΤΙΣ ΟΔΗΓΙΕΣ ΕΔΩ - Drop your instructions here</p></li>
                      </ul>
                    </div>
                  </div>
                </li>
            </ul>
          </div>
        </div>
        <div id="programContainer">
          <h2>ΠΡΟΓΡΑΜΜΑ / Program</h2>
          <div class="droppable">
            <ul>
            </ul>
          </div>
        </div>
      </div>

      <div id="credits2">
        <div style="float:left;"></div>
        <div style="float:right;"></div>
      </div>
    </div>

    <div id="dialogs">
      <div id="levelCompleteDialog" title="Level complete">
        <p>
          <span class="medal" style="float:left; margin:0 7px 50px 0;"></span>
          Congratulations! You have completed this level using <span class="nbrOfInstructions"></span> instructions!
        </p>
        <p class="message" style="margin-top: 10px"></p>
      </div>

      <div id="achievementDialog" title="Achievement unlocked!">
        <p>
          <span class="achievement" style="float:left; margin:0 7px 50px 0;"></span>
          <span class="message"></span>
        </p>
      </div>
    </div>
  </body>
</html>