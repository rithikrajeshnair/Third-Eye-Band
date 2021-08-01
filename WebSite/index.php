<?php
$username = "root";
$password = "password";
$hostname = "localhost";
$database ="name";
$server = mysqli_connect($hostname,$username,$password);
$connection = mysqli_select_db($server,$database);

if (!$server)
{
    echo "Connection lost";
}
$myquery = "SELECT `name`,`homelat`,`homelng`,`heartbeat`,`bodytemp` from `worlddata` WHERE id=1";
$query = mysqli_query($server,$myquery);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset=utf-8 />
<title>band101</title>
<meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<link rel="shortcut icon" href="android-icon-72x72.png" />

<script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.9/mapbox.js'></script>
<link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.9/mapbox.css' rel='stylesheet' />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
  body { margin:0; padding:0; }
  #map { position:absolute; top:48px; bottom:0; width:100%; }
    .stylepop.leaflet-popup-content {
    border:3px solid red;
}
.stylepop.leaflet-popup-tip {
    border:6px dashed red;
}
  .topnav {
  overflow: hidden;
  background-color: black;
}

.topnav a {
  float: left;
  display: block;
  color: black;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
  background-color: black;
  color: white;
}

.topnav .icon {
  display: none;
}

@media screen and (max-width: 600px) {
  .topnav a:not(:first-child) {display: none;}
  .topnav a.icon {
    float: right;
    display: block;
  }
}

@media screen and (max-width: 600px) {
  .topnav.responsive {position: relative;}
  .topnav.responsive .icon {
    position: absolute;
    right: 0;
    top: 0;
  }
  .topnav.responsive a {
    float: none;
    display: block;
    text-align: left;
  }
}
.btn {
    float: right;
  /*background-color: #4CAF50;*/ /* Blue background */
  background-color: black;
  border: none; /* Remove borders */
  color: white; /* White text */
  padding: 15px 15px; /* Some padding */
  font-size: 16px; /* Set a font size */
  cursor: pointer; /* Mouse pointer on hover */
}

/* Darker background on mouse-over */
/*.btn:hover {
  background-color: RoyalBlue;
}*/
</style>

<!--End Interactive Sidebar Head-->
</head>
<body onload="display_ct()">
    <div class="topnav" id="myTopnav">
    <a href="#home" class="active" id="ct"></a>
    <button class="btn" onclick="toggleFullScreen()" ><i class="fa fa-arrows-alt" aria-hidden="true"></i></button>
  <!--<a href="#news">News</a>
  <a href="#contact">Contact</a>
  <a href="#about">About</a>-->
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars" aria-hidden="true"></i>
  </a>
</div>

<!--<div class="topnav" id="myTopnav">
  <a href="#home" class="active" id="ct"></a>
  <button class="btn" onclick="toggleFullScreen()" ><i class="fa fa-arrows-alt" aria-hidden="true"></i></button>
  <a href="#news">News</a>
  <a href="#contact">Contact</a>
  <a href="#about">About</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
  </a>
</div>-->
<!-- Side navigation -->

<div id='map'></div>
<script>

L.mapbox.accessToken = 'pk.eyJ1IjoiYWFpemVtYmVyZyIsImEiOiJIQmdlUkVzIn0.kzKfi1ndNMUcY4sH07RaUQ';
var map = L.mapbox.map('map', 'mapbox.streets')
    .setView([38, -102.0], 4);
var polyline = L.polyline([]).addTo(map);

// Keep a tally of how many points we've added to the map.
var pointsAdded = 0;

// Start drawing the polyline.
add();

function add() {

    // `addLatLng` takes a new latLng coordinate and puts it at the end of the
    // line. You optionally pull points from your data or generate them. Here
    // we make a sine wave with some math.
    polyline.addLatLng(
        L.latLng(
            Math.cos(pointsAdded / 20) * 30,
            pointsAdded));


    // Pan the map along with where the line is being added.
    map.setView([0, pointsAdded], 3);

    // Continue to draw and pan the map by calling `add()`
    // until `pointsAdded` reaches 360.

}

// As with any other AJAX request, this technique is subject to the Same Origin Policy:
// http://en.wikipedia.org/wiki/Same_origin_policy
var featureLayer = L.mapbox.featureLayer()
    .loadURL('http://band101.hackp.cyberdome.org.in/myfile.json')
    // Once this layer loads, we set a timer to load it again in a few seconds.
    .on('ready', run)
    .addTo(map);
var clickCircle;

// function onMapClick(e) {
//    /* if (clickCircle != undefined) {
//       map.removeLayer(clickCircle);
//     };*/

//     clickCircle = L.circle(e.latlng, 1000000, {
//         color: 'red',
//         fillOpacity: 0,
//         opacity: 0.5
//       }).addTo(map);
// }
// map.on('click', onMapClick);
var i=0;
function getDistance(origin, destination) {
    // return distance in meters
    var lon1 = toRadian(origin[1]),
        lat1 = toRadian(origin[0]),
        lon2 = toRadian(destination[1]),
        lat2 = toRadian(destination[0]);

    var deltaLat = lat2 - lat1;
    var deltaLon = lon2 - lon1;

    var a = Math.pow(Math.sin(deltaLat/2), 2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(deltaLon/2), 2);
    var c = 2 * Math.asin(Math.sqrt(a));
    var EARTH_RADIUS = 6371;
    return c * EARTH_RADIUS * 1000;
}
function toRadian(degree) {
    return degree*Math.PI/180;
}
<?php while($row = mysqli_fetch_assoc($query)):?>
    var name = "<?php echo($row['name']); ?>";
    var hlat = "<?php echo($row['homelat']); ?>";
    var hlng = "<?php echo($row['homelng']); ?>";
    var hb = "<?php echo($row['heartbeat']); ?>";
    var bt = "<?php echo($row['bodytemp']); ?>";

<?php endwhile; ?>


function run() {
    featureLayer.eachLayer(function(l) {
        map.panTo(l.getLatLng());
        var latlng = l.getLatLng();
        const lat = l.getLatLng().lat;
        const lng = l.getLatLng().lng;
      //polyline.addLatLng(L.latLng(lat,lng));
      if(i==0){
        clat=lat;
        clng=lng;
        i=i+1;
        var circle = L.circle([hlat,hlng], 500, {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0
        }).addTo(map);

    }
    var distance = getDistance([lat,lng],[hlat,hlng]);
    if(distance > 500000)
    {
      setTimeout(function(){
        alert("OUTSIDE PERIMETER!!");
      },100);
      }
    else{
      console.log("INSIDE!!!!!");
    }
     // var popup = L.popup()
     // .setLatLng(latlng)
     // .setContent("")
     // .openOn(map);
    var photoImg = '<img src="http://band101.hackp.cyberdome.org.in/Images/walking.png" width="65px"/>';
    var man = '<img src="http://band101.hackp.cyberdome.org.in/Images/ma.jpeg" width="14px"/>';
    var heart = '<img src="http://band101.hackp.cyberdome.org.in/Images/heart.png" width="14px"/>';
    var bodt = '<img src="http://band101.hackp.cyberdome.org.in/Images/bt.png" width="14px"/>';
    var loc = '<img src="http://band101.hackp.cyberdome.org.in/Images/loc.png" width="14px"/>';
     function onMapClick(e) {
         var popup = L.popup({className:'stylepop'})
        .setLatLng(e.latlng)
        .setContent("&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp" + photoImg + "</br>" + man + "<b><a style='color:maroon;font-size:15px;'>&nbspName : "+name+"        "+ "<br />"+ loc +"&nbspHome : "+hlat+","+hlng+"</br>"+ heart + "&nbspHeartbeat : "+hb+"</br>"+ bodt +"&nbspBodytemp : "+bt)
        .addTo(map);
    }
        l.on('click', onMapClick);

    });
    window.setTimeout(function() {
        featureLayer.loadURL('http://band101.hackp.cyberdome.org.in/myfile.json');
    }, 2000);
}

</script>
<script>
var d = new Date();
document.getElementById("demo").innerHTML = d;
</script>
<script type="text/javascript">
function display_c(){
var refresh=1000; // Refresh rate in milli seconds
mytime=setTimeout('display_ct()',refresh)
}

function display_ct() {
var x = new Date()
document.getElementById('ct').innerHTML = x;
display_c();
 }
</script>
<script>
    function toggleFullScreen() {
  if ((document.fullScreenElement && document.fullScreenElement !== null) ||
   (!document.mozFullScreen && !document.webkitIsFullScreen)) {
    if (document.documentElement.requestFullScreen) {
      document.documentElement.requestFullScreen();
    } else if (document.documentElement.mozRequestFullScreen) {
      document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullScreen) {
      document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
    }
  } else {
    if (document.cancelFullScreen) {
      document.cancelFullScreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitCancelFullScreen) {
      document.webkitCancelFullScreen();
    }
  }
}
</script>
<script>
function myFunction() {
  var x = document.getElementById("myTopnav");
  if (x.className === "topnav") {
    x.className += " responsive";
  } else {
    x.className = "topnav";
  }
}
</script>



</body>
</html>
