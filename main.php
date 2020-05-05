<html>

<head>
  <title> Review system</title>
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="index.php">Zest</a>
      </div>
      <ul class="nav navbar-nav">
        <li class="active"><a href="main.php">Home</a></li>
        <li><a href="about.php">About Us</a></li>
        <!-- <li><a href="#">Page 3</a></li>-->
      </ul>
    </div>
  </nav>

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<style>
  h3 {
    color: white;
  }

  p {
    color: white;
    font-size: 18px;
  }
  /* Set the size of the div element that contains the map */
  #map {
    height: 575px;
    /* The height is 400 pixels */
    width: 575px;
    /* The width is the width of the web page */
  }
</style>
<!--d1-->

<body background="back.jpg" padding="10px" style="padding:20px" onload="bodyOnLoad()">
<table>
  <tr>
    <?php
      error_reporting(E_ALL);
      // Get cURL resource
      $curl = curl_init();
      $q = '';
      if (isset($_POST['query'])) {
        $q = $_POST['query'];
        $q = str_replace(" ", "+", $q);
        echo $q;
      }
      // Curl options
      curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Accept: application/json', 'user-key: 6742eba73ca3f74bacaeebbef21eb8c5'],
        CURLOPT_URL => 'https://developers.zomato.com/api/v2.1/search?q=' . $q . '&lat=18.5204&lon=73.8567',
      ));

      // Send the request & save response to $resp
      $resp = curl_exec($curl);

      // Check for errors if curl_exec fails
      if (!curl_exec($curl)) {
        die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
      }

      // Close request to clear up some resources
      curl_close($curl);

      echo "<script>console.log(" . $resp . ")</script>";

      // Decode json
      $jsonZomato = json_decode($resp, true);
      $arrayofRest = array();
      $restaurants = $jsonZomato['restaurants'];

      $count = 0;
      foreach ($restaurants as $restaurant) {
        if ($count % 4 == 0) {
          echo '</tr><tr>';
        }
        $res = $restaurant['restaurant'];
        $local = $res['location'];
        $thumb = $res['thumb'];
        $name = $res['name'];
        $locality = $local['locality'];
        $latitude = $local['latitude'];
        $longitude = $local['longitude'];
        $resId = $res['id'];
        $arrayofRest[$count] = $res;
        echo '<div class="card col-md-3" style="width:400px"><center>
              <img class="card-img-top img-rounded" src="' . $thumb . '" alt="Card image" width="200px" height="200px" style="border:4px">
              
              <div class="card-body">
                <p class="card-text">' . $res['name'] . '<br>
              ' . $locality . '
                <br>
                <input id="' . $locality . '" type="hidden" name="locality" value="' . $locality . '"/>
                <input id="' . $name . '" type="hidden" name="rest-name" value="' . $name . '"/>
                <input id="' . $thumb . '" type="hidden" name="thumbnail" value="' . $thumb . '"/>
                
                <input type="hidden" name="count" value="' . $count . '"/>
                <button data-longitude="' . $longitude . '" data-latitude=' . $latitude . ' data-locality="' . $locality . '" data-name="' . $name . '" data-id="' . $resId . '" type="button" class="reviewBtn btn btn-primary">Review</button>
                <button data-longitude="' . $longitude . '" data-latitude=' . $latitude . ' data-locality="' . $locality . '" data-name="' . $name . '" data-id="' . $resId . '" type="button" class="othersReviewsBtn btn btn-primary">Other reviews</button>
              </div></center></p>
              </div></td>';


        $count = $count + 1;
      }
      ?>
  </table>


  <!-- Modal -->
  <form method="POST" action="approved.php">
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Write your Review</h4>
          </div>
          <div class="modal-body">
            <!--The name attributes are used to refer the submitted values in the backend-->
            <label id="resNamelabel" name="restoName" type="text" readonly="readonly"></label>
            <br>
            <label id="resLocalitylabel" name="restoLocality" type="text" readonly="readonly"></label>
            <br>
            <input id="resName" name="restoName" type="hidden" readonly="readonly"></label>
            <br>
            <input id="resLocality" name="restoLocality" type="hidden" readonly="readonly"></label>
            <br>
            <input class="form-control" name="personname" id="personname" type="text" required placeholder="Your name" />
            <br>
            <textarea class="form-control" name="review" id="review" type="text" required placeholder="Your review"></textarea>
            <div id="reviews"></div>
          </div>
          <div id="map"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </form>


  <!-- Modal -->
  <form method="POST" action="review.php">
    <div id="myModalRev" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Check out what others have to say</h4>
          </div>
          <div class="modal-body">
            <!--The name attributes are used to refer the submitted values in the backend-->
            <label id="resNamelabel2" name="restoName2" type="text" readonly="readonly"></label>
            <br>
            <label id="resLocalitylabel2" name="restoLocality2" type="text" readonly="readonly"></label>
            <br>
            <label id="resRatingLabel" name="restoRating" type="text" readonly="readonly"></label>
            <br>
            <div id="reviewsDiv">Reviews</div>
          </div>
          <!-- <div id="map"></div>-->
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <!--Script for map-->
  <!-- <script async defer-->
  <!--    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB7IBlVhk0BIrpbnPQcsRkWllpxApH9L-c&callback=initMap">-->
  <!--</script>-->

  <script>
    function bodyOnLoad() {
      //console.log("body loaded");
      $(".reviewBtn").click(showModal); //Attach an event listener to all the buttons of class 'reviewBtn'
      $(".othersReviewsBtn").click(showModalOthers);
    }

    function initMap() {
      // The location of Uluru
      var latitude = 0;
      var uluru = {
        lat: 0,
        lng: 0
      };
      // The map, centered at Uluru
      var map = new google.maps.Map(
        document.getElementById('map'), {
          zoom: 4,
          center: uluru
        });
      // The marker, positioned at Uluru
      var marker = new google.maps.Marker({
        position: uluru,
        map: map
      });
    }


    function showModal() {
      $("#myModal").modal(); //Shows the modal
      document.getElementById("resLocalitylabel").innerHTML = this.getAttribute("data-locality"); //Set the value by the 'data-locality' attribute of the button that was clicked
      document.getElementById("resNamelabel").innerHTML = this.getAttribute("data-name"); //Set the value by the 'data-name' attribute of the button that was clicked
      document.getElementById("resLocality").value = this.getAttribute("data-locality"); //Set the value by the 'data-locality' attribute of the button that was clicked
      document.getElementById("resName").value = this.getAttribute("data-name");
      var latitude = parseFloat(this.getAttribute("data-latitude"));
      var longitude = parseFloat(this.getAttribute("data-longitude"));
      var uluru = {
        lat: latitude,
        lng: longitude
      };
      // The map, centered at Uluru
      //   var map = new google.maps.Map(
      //       document.getElementById('map'), {zoom: 15, center: uluru});
      //   // The marker, positioned at Uluru
      //   var marker = new google.maps.Marker({position: uluru, map: map});

      //console.log(this.getAttribute("data-latitude"));
    }

    function httpGet(theUrl) {
      var xmlHttp = new XMLHttpRequest();
      xmlHttp.open("GET", theUrl, false); // false for synchronous request
      xmlHttp.send(null);
      return xmlHttp.responseText;
    }

    function showModalOthers() {
      $("#myModalRev").modal();
      document.getElementById("resLocalitylabel2").innerHTML = this.getAttribute("data-locality"); //Set the value by the 'data-locality' attribute of the button that was clicked
      document.getElementById("resNamelabel2").innerHTML = this.getAttribute("data-name"); //Set the value by the 'data-name' attribute of the button that was clicked
      /*var latitude = parseFloat(this.getAttribute("data-latitude"));
	var longitude = parseFloat(this.getAttribute("data-longitude"));
		var uluru = {lat: latitude, lng: longitude};
  // The map, centered at Uluru
  var map = new google.maps.Map(
      document.getElementById('map'), {zoom: 15, center: uluru});
  // The marker, positioned at Uluru
   var marker = new google.maps.Marker({position: uluru, map: map});*/


      document.getElementById("resLocality").display = 'none';;
      document.getElementById("resName").display = 'none';
      var requestParam = "http://127.0.0.1:10000/rating?rest=" + this.getAttribute("data-name")
      $.get(requestParam, function(data, status) {
        if (JSON.parse(data).rating == 'none') document.getElementById("resRatingLabel").innerHTML = "No reviews available.";
        else document.getElementById("resRatingLabel").innerHTML = "Rating : " + JSON.parse(data).rating + " : on a scale of 0 to 5.";
      });
      var rname = this.getAttribute("data-name");
      var rloc = this.getAttribute("data-locality");
      document.getElementById('reviewsDiv').innerHTML = '';
      $.get('review.php', {
        type: "getRevs",
        resName: rname,
        resLocality: rloc
      }, function(data) {

        console.log(data + ",length: " + data.length);
        if (data.indexOf(",") > -1) {

          dataArr = data.split(".");
          //console.log( dataArr[0].split(",")[0] + " ");
          var i = 0,
            j = dataArr.length;

          for (i; i < j; i++) {

            if (dataArr[i].indexOf(",") > -1) {
              sDataArrs = dataArr[i].split(",");
              document.getElementById('reviewsDiv').innerHTML += '<br><p style="color:green"><b>' + sDataArrs[0] + '</b> says:</p><p style="color:blue">' + sDataArrs[1] + '</p>';
              //console.log( '<p><b>'+sDataArrs[0]+'</b> says:</p><p style="color:blue">'+ sDataArrs[1]+'</p>' );
            }
          }
        } else {
          document.getElementById('reviewsDiv').innerHTML = "<br><br><b>" + data + "</>";
        }
      });
    }
  </script>


</body>

</html>