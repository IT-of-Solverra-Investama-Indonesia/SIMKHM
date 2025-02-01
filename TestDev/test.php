<!DOCTYPE html>
<html>
<head>
  <title>Leaflet Map with Search and Click Marker</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
  <style>
    #map {
      height: 300px;
      width: 100%;
    }
    #coordinates {
      margin-top: 10px;
    }
    #search {
      margin-bottom: 10px;
    }
    #results {
      /* border: 1px solid #ccc; */
      /* max-height: 150px; */
      /* overflow-y: auto; */
      background: white;
      position: absolute;
      /* width: calc(100% - 22px); */
      z-index: 1000;
      width: 100%;
    }
    .result-item {
      width: 100%;
      padding: 8px;
      cursor: pointer;
    }
    .result-item:hover {
      background-color: #f0f0f0;
    }
  </style>
</head>
<body>
  <div class="container-fluid mt-2">
        <div class="row">
            <div class="col-8">
                <input type="text" class="form-control mb-2" id="searchInput" placeholder="Enter location" autocomplete="off">
                <div id="results" class="w-100"></div>
            </div>
            <div class="col-4">
                <button class="btn w-100 btn-primary" onclick="searchLocation()">Search</button>
            </div>
            <div class="col-md-12">
                <div class="card shadow p-2 w-100">
                    <div id="map" class="w-100"></div>
                </div>
                <div id="coordinates">Click on the map to get the location coordinates (Latitude, Longitude)</div>
            </div>
        </div>
  </div>
   
  <!-- Load Leaflet library -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <!-- Load Leaflet Search Plugin -->
  <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

  <script>
    // Initialize the map
    var map = L.map('map');

    // Use OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Create a marker variable to keep track of the current marker
    var marker;

    // Function to set map view to user's current location
    function setMapToUserLocation(lat, lng) {
      map.setView([lat, lng], 13);

      // Add a marker at the user's location
      if (marker) {
        marker.setLatLng([lat, lng]);
      } else {
        marker = L.marker([lat, lng]).addTo(map);
      }
      marker.setPopupContent(`Your Location: Latitude: ${lat}, Longitude: ${lng}`).openPopup();

      // Update the coordinates display
      document.getElementById("coordinates").innerHTML = `Your Location: Latitude: ${lat}, Longitude: ${lng}`;
    }

    // Function to search for a location
    function searchLocation() {
      var query = document.getElementById('searchInput').value;
      var geocoder = L.Control.Geocoder.nominatim();

      geocoder.geocode(query, function(results) {
        if (results.length > 0) {
          var latlng = results[0].center;

          // Place a marker at the searched location
          if (marker) {
            marker.setLatLng(latlng);
          } else {
            marker = L.marker(latlng).addTo(map);
          }
          marker.setPopupContent(`Searched Location: Latitude: ${latlng.lat}, Longitude: ${latlng.lng}`).openPopup();

          // Center the map on the searched location
          map.setView(latlng, 13);

          // Update the coordinates display
          document.getElementById("coordinates").innerHTML = `Searched Location: Latitude: ${latlng.lat}, Longitude: ${latlng.lng}`;
        } else {
          alert('Location not found');
        }
      });
    }

    // Function to handle input and display results
    function handleInput() {
      var query = document.getElementById('searchInput').value;
      var resultsContainer = document.getElementById('results');
      resultsContainer.innerHTML = '';
      
      if (query.length > 2) {  // Only perform search if query length is greater than 2
        var geocoder = L.Control.Geocoder.nominatim();
        
        geocoder.geocode(query, function(results) {
          resultsContainer.innerHTML = '';
          
          results.forEach(function(result) {
            var latlng = result.center;
            var div = document.createElement('div');
            div.className = 'result-item';
            div.textContent = result.name;
            div.onclick = function() {
              map.setView(latlng, 13);
              
              // Place a marker at the selected  #ccclocation
              if (marker) {
                marker.setLatLng(latlng);
              } else {
                marker = L.marker(latlng).addTo(map);
              }
              marker.setPopupContent(`Selected Location: Latitude: ${latlng.lat}, Longitude: ${latlng.lng}`).openPopup();
              
              // Update the coordinates display
              document.getElementById("coordinates").innerHTML = `Selected Location: Latitude: ${latlng.lat}, Longitude: ${latlng.lng}`;
              
              resultsContainer.innerHTML = '';  // Clear results after selection
            };
            resultsContainer.appendChild(div);
          });
        });
      }
    }

    // Attach input event listener for autocomplete
    document.getElementById('searchInput').addEventListener('input', handleInput);

    // Try to get the user's geolocation
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;

        // Set the map view to the user's location
        setMapToUserLocation(lat, lng);

        // Capture latitude and longitude when the map is clicked
        map.on('click', function(e) {
          var lat = e.latlng.lat;
          var lng = e.latlng.lng;

          // Update coordinates display
          document.getElementById("coordinates").innerHTML = `Clicked Location: Latitude: ${lat}, Longitude: ${lng}`;
          console.log("Clicked Location: ", lat, lng);

          // Place a marker at the clicked location
          if (marker) {
            marker.setLatLng([lat, lng]);
          } else {
            marker = L.marker([lat, lng]).addTo(map);
          }
          marker.setPopupContent(`Clicked Location: Latitude: ${lat}, Longitude: ${lng}`).openPopup();
        });
      }, function(error) {
        console.error('Error getting location: ', error);
        // Default to a fixed location if geolocation fails
        setMapToUserLocation(51.505, -0.09);
      });
    } else {
      // Browser doesn't support Geolocation, use default location
      setMapToUserLocation(51.505, -0.09);
    }
  </script>
</body>
</html>
