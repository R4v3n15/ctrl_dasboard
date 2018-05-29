<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4" id="map">
        
    </main>
</div>


<script>
      var customLabel = {
        '1': {
          label: 'T'
        },
        '2': {
          label: 'A'
        }
      };

        function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(19.579994462915835, -88.04420235898436),
          zoom: 14
        });
        var infoWindow = new google.maps.InfoWindow;

          // Change this depending on the name of your PHP or XML file
          // downloadUrl('https://storage.googleapis.com/mapsdevsite/json/mapmarkers2.xml', function(data) {
          downloadUrl(_root_ + 'mapa/getXMarks', function(data) {
            var xml = data.responseXML;
            console.log(xml);
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var name = markerElem.getAttribute('name');
              var address = markerElem.getAttribute('address');
              var type = markerElem.getAttribute('type');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));

              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = name
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));

              var text = document.createElement('text');
              text.textContent = address
              infowincontent.appendChild(text);
              var icon = customLabel[type] || {};
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: icon.label
              });
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
            });
          });
        }



      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
</script>