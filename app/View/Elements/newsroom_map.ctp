  <?php $newsAddress= $data['Company']['address'].', '.$data['Company']['city'].', '.$data['Company']['state'].'-'.$data['Company']['zip_code']; 
  
  $googleMapKey=Configure::read('google_map_key');

  echo "<h4><i class='fa fa-map-marker-alt'></i> $newsAddress.</h4>";
  ?>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleMapKey;?>"></script> 
     <div class="pr-2">
      
      <div id="map" style="width: 100%; height: 480px;"></div>
        <div class="hide"> 
            <input id="Encode" type="button" value="Encode" onclick="codeAddress()">
        </div> 
     </div>
      <script> 
      var geocoder;
      var map;
      function initialize() {
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(-34.397, 150.644);
        var mapOptions = {
          zoom: 10,
          center: latlng
        }
        map = new google.maps.Map(document.getElementById('map'), mapOptions);
      }
    
      function codeAddress() {
        initialize();
        var address = "<?php echo $newsAddress;?>";
        geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == 'OK') {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }
    
    
      window.onload = function(){
        $( "#Encode" ).trigger( "click" );
        }
    </script>