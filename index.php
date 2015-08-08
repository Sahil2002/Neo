<?php
include 'PHP_Binding_0_1/wa_wrapper/WolframAlphaEngine.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
  <script src="js/gmaps.js"></script>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Neo: A Virtual Assistant</title>

  <!-- Bootstrap Core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="css/grayscale.css" rel="stylesheet">

  <!-- Custom Fonts -->
  <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
  <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">



  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->

</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">


  <!-- Navigation -->
  <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
          <i class="fa fa-bars"></i>
        </button>
        <a class="navbar-brand page-scroll" href="#page-top">
          <span class="light"></span>
        </a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
        <ul class="nav navbar-nav">
          <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
          <li class="hidden">
            <a href="#page-top"></a>
          </li>
          <li>
            <a class="page-scroll" href="#about">About</a>
          </li>
          <li>
            <a class="page-scroll" href="#download">Download</a>
          </li>
        </ul>
      </div>
      <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
  </nav>

  <!-- Intro Header -->
  <header class="intro">
    <div class="intro-body">
      <div class="container">
        <div class="row">
          <div class="col-md-8 col-md-offset-2">
            <h1 class="brand-heading">Neo</h1>
            <h1>The Virtual Assistant</h1>
            <div id=tb>
              <form method='POST' action='#'>
                <input type="text" name="q" id="interim_span" placeholder="What can I do for you?" value="
                <?php
                $queryIsSet = isset($_REQUEST['q']);
                if ($queryIsSet) {
                  echo $_REQUEST['q'];
                };
                ?>"
                >
              </form>
              <style type="text/css">
              input{
                font-size: 2em;
                color: black;
                border-radius: 8px;
                padding: 10px;
                outline: 0;
                border: 0;
                background: rgba(0,0,0,0.5);

              }
              </style>
            </div>
            <button id="start_button">
              <img id="start_img"></button>
              <a href="#" onclick="startButton(event)" class="btn btn-circle page-scroll">
                <i class="fa fa-microphone animated"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div id="info">
      <p id="info_start">Click on the microphone icon and begin speaking.</p>
      <p id="info_speak_now">Speak now.</p>
      <p id="info_no_speech">No speech was detected. You may need to adjust your
        <a href="//support.google.com/chrome/bin/answer.py?hl=en&amp;answer=1407892">
          microphone settings</a>.</p>
          <p id="info_no_microphone" style="display:none">
            No microphone was found. Ensure that a microphone is installed and that
            <a href="//support.google.com/chrome/bin/answer.py?hl=en&amp;answer=1407892">
              microphone settings</a> are configured correctly.</p>
              <p id="info_allow">Click the "Allow" button above to enable your microphone.</p>
              <p id="info_denied">Permission to use microphone was denied.</p>
              <p id="info_blocked">Permission to use microphone is blocked. To change,
                go to chrome://settings/contentExceptions#media-stream</p>
                <p id="info_upgrade">Web Speech API is not supported by this browser.
                  Upgrade to <a href="//www.google.com/chrome">Chrome</a>
                  version 25 or later.</p>
                </div>
                <?php
                $appID = 'G4UT54-4ETKTTH58Y';

                if (!$queryIsSet) die();

                $qArgs = array();
                if (isset($_REQUEST['assumption']))
                $qArgs['assumption'] = $_REQUEST['assumption'];

                // instantiate an engine object with your app id
                $engine = new WolframAlphaEngine( $appID );

                // we will construct a basic query to the api with the input 'pi'
                // only the bare minimum will be used
                $response = $engine->getResults( $_REQUEST['q'], $qArgs);

                // getResults will send back a WAResponse object
                // this object has a parsed version of the wolfram alpha response
                // as well as the raw xml ($response->rawXML)

                // we can check if there was an error from the response object
                if ( $response->isError() ) {
                  ?>
                  <h1>There was an error in the request</h1>
                  <?php
                  die();
                }
                ?>

                <h1>Results</h1>
                <br>

                <?php
                // if there are any assumptions, display them
                if ( count($response->getAssumptions()) > 0 ) {
                  ?>
                  <h2>Assumptions:</h2>
                  <ul>
                    <?php
                    // assumptions come as a hash of type as key and array of assumptions as value
                    foreach ( $response->getAssumptions() as $type => $assumptions ) {
                      ?>
                      <li><?php echo $type; ?>:<br>
                        <ol>
                          <?php
                          foreach ( $assumptions as $assumption ) {
                            ?>
                            <li><?php echo $assumption->name ." - ". $assumption->description;?>, to change search to this assumption <a href="simpleRequest.php?q=<?php echo urlencode($_REQUEST['q']);?>&assumption=<?php echo $assumption->input;?>">click here</a></li>
                            <?php
                          }
                          ?>
                        </ol>
                      </li>
                      <?php
                    }
                    ?>

                  </ul>
                  <?php
                }
                ?>

                <hr>

                <?php
                // if there are any pods, display them
                if ( count($response->getPods()) > 0 ) {
                  ?>
                  <h2>Pods</h2>
                  <table border=1 width="80%" align="center">
                    <?php
                    foreach ( $response->getPods() as $pod ) {
                      ?>
                      <tr>
                        <td>
                          <h3><?php echo $pod->attributes['title']; ?></h3>
                          <?php
                          // each pod can contain multiple sub pods but must have at least one
                          foreach ( $pod->getSubpods() as $subpod ) {
                            // if format is an image, the subpod will contain a WAImage object
                            ?>
                            <img src="<?php echo $subpod->image->attributes['src']; ?>">
                            <hr>
                            <?php
                          }
                          ?>

                        </td>
                      </tr>
                      <?php
                    }
                    ?>
                  </table>
                  <?php
                }
                ?>
                <div id="results">
                  <span id="final_span" class="final"></span>
                  <span id="interim_span" class="interim"></span>
                  <p>
                  </div>
                  <div class="center">
                    <div class="sidebyside" style="text-align:right">
                      <!-- <button id="copy_button" class="button" onclick="copyButton()"> -->
                      <!-- Copy and Paste</button> -->
                      <div id="copy_info" class="info">
                      </div>
                    </div>
                    <div class="sidebyside">
                      <button id="email_button" class="button" onclick="emailButton()">
                        Create Email</button>
                        <div id="email_info" class="info">
                        </div>
                      </div>
                      <p>
                      </div>

                      <div id="location">
                      </div>
                      <div id="results"></div>
                      <br>
                      <br>
                      <h3 id="map_description">Here is the current time:-</h3>
                      <br>
                      <canvas id="canvas" width="400" height="400" style="background-color:#333">
                      </canvas>
                      <script>
                      var canvas = document.getElementById("canvas");
                      var ctx = canvas.getContext("2d");
                      var radius = canvas.height / 2;
                      ctx.translate(radius, radius);
                      radius = radius * 0.90
                      setInterval(drawClock, 1000);

                      function drawClock() {
                        drawFace(ctx, radius);
                        drawNumbers(ctx, radius);
                        drawTime(ctx, radius);
                      }

                      function drawFace(ctx, radius) {
                        var grad;
                        ctx.beginPath();
                        ctx.arc(0, 0, radius, 0, 2*Math.PI);
                        ctx.fillStyle = 'white';
                        ctx.fill();
                        grad = ctx.createRadialGradient(0,0,radius*0.95, 0,0,radius*1.05);
                        grad.addColorStop(0, '#333');
                        grad.addColorStop(0.5, 'white');
                        grad.addColorStop(1, '#333');
                        ctx.strokeStyle = grad;
                        ctx.lineWidth = radius*0.1;
                        ctx.stroke();
                        ctx.beginPath();
                        ctx.arc(0, 0, radius*0.1, 0, 2*Math.PI);
                        ctx.fillStyle = '#333';
                        ctx.fill();
                      }

                      function drawNumbers(ctx, radius) {
                        var ang;
                        var num;
                        ctx.font = radius*0.15 + "px arial";
                        ctx.textBaseline="middle";
                        ctx.textAlign="center";
                        for(num = 1; num < 13; num++){
                          ang = num * Math.PI / 6;
                          ctx.rotate(ang);
                          ctx.translate(0, -radius*0.85);
                          ctx.rotate(-ang);
                          ctx.fillText(num.toString(), 0, 0);
                          ctx.rotate(ang);
                          ctx.translate(0, radius*0.85);
                          ctx.rotate(-ang);
                        }
                      }

                      function drawTime(ctx, radius){
                        var now = new Date();
                        var hour = now.getHours();
                        var minute = now.getMinutes();
                        var second = now.getSeconds();
                        //hour
                        hour=hour%12;
                        hour=(hour*Math.PI/6)+
                        (minute*Math.PI/(6*60))+
                        (second*Math.PI/(360*60));
                        drawHand(ctx, hour, radius*0.5, radius*0.07);
                        //minute
                        minute=(minute*Math.PI/30)+(second*Math.PI/(30*60));
                        drawHand(ctx, minute, radius*0.8, radius*0.07);
                        // second
                        second=(second*Math.PI/30);
                        drawHand(ctx, second, radius*0.9, radius*0.02);
                      }

                      function drawHand(ctx, pos, length, width) {
                        ctx.beginPath();
                        ctx.lineWidth = width;
                        ctx.lineCap = "round";
                        ctx.moveTo(0,0);
                        ctx.rotate(pos);
                        ctx.lineTo(0, -length);
                        ctx.stroke();
                        ctx.rotate(-pos);
                      }
                      </script>
                      <br>
                      <br>
                      <!-- map starts now! -->
                      <h3 id="map_description">Here is a map of your location:</h3>
                      <div id="map"></div>
                      <script>
                      var map = new GMaps({
                        el: '#map',
                        lat: -12.043333,
                        lng: -77.028333,
                        zoom: 19
                      });
                      </script>
                      <script>
                      GMaps.geolocate({
                        success: function(position) {
                          map.setCenter(position.coords.latitude, position.coords.longitude);
                        },
                        error: function(error) {
                          alert('Geolocation failed: '+error.message);
                        },
                        not_supported: function() {
                          alert("Your browser does not support geolocation");
                        },
                        always: function() {
                          alert("Succesfully located and mapped your position!");
                        }
                      });
                      </script>


                    <!-- About Section -->
                    <section id="about" class="container content-section text-center">
                    <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                    <h2 class="heads">About Neo</h2>
                    <style>
                    .heads {
                      font-weight: 900;
                      color: red;
                    }
                    </style>
                    <p id="llist"> Neo is a virtual assistant made mainly using the following API's:
                    <ul id="llist">
                    <li> Wolfram Alpha API </li>
                    <li> Google maps </li>
                    <li> Google calender </li>
                    <li> Wunderground </li>
                    <li> Google mail </li>
                    <li> Google speech recognition </li>
                    <li> Google Play Store </li>

                    </ul>
                    <style type = "text/css">
                    #llist {
                      color: green;
                      font-size: 2em;
                    }
                    </style>
                    </p>
                    </div>
                    </div>
                    </section>

                    <!-- Download Section -->
                    <section id="download" class="content-section text-center">
                    <div class="download-section">
                    <div class="container">
                    <div class="col-lg-8 col-lg-offset-2">
                    <h2>Download Neo</h2>
                    <p>You can download Neo from Github. All you have to do is clone it from https://github.com/rocka0/neo</p>
                    <a href="https://www.github.com/rocka0/Neo" class="btn btn-default btn-lg">Click here to download</a>
                    </div>
                    </div>
                    </div>
                    </section>

                    <!-- Footer -->
                    <footer>
                    <div class="container text-center">
                    <p>Developed by Joshua, Tushar and Sahil</p>
                    </div>
                    </footer>

                    <!-- jQuery -->
                    <script src="js/jquery.js"></script>

                    <!-- Bootstrap Core JavaScript -->
                    <script src="js/bootstrap.min.js"></script>

                    <!-- Plugin JavaScript -->
                    <script src="js/jquery.easing.min.js"></script>
                    <!-- Custom Theme JavaScript -->
                    <script src="js/grayscale.js"></script>
                    <!--
                    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBnFZBVB1kUzASIcp6CqchP2S3PXCaLPH0">
                    -->
                    <!-- App JavaScript -->
                    <script src="js/app.js"></script>

                    <script>
                    var msg = new SpeechSynthesisUtterance('Hello. I am Neo. How may I help you?');
                    window.speechSynthesis.speak(msg);
                    </script>



                    </body>

                    </html>
