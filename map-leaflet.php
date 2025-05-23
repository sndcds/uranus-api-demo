<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Event Map</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="fonts/inter.css">

    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Inter';
        }

        #map {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>

<!--h2>Event Map</h2-->
<div id="map"></div>

<script>
    // Initialize the map centered on Schleswig-Holstein
    const map = L.map('map').setView([54.3, 9.5], 8);

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
    }).addTo(map);

    <?php include("config.php"); ?>
    const apiUrl = <?php echo json_encode($apiBaseUrl . "/query?mode=event&start=2024-01-01"); ?>;
    fetch (apiUrl)
        .then(response => response.json())
        .then(data => {
            const events = data.events || [];

            events.forEach(event => {
                if (event.venue_lat && event.venue_lon) {
                    const marker = L.marker([event.venue_lat, event.venue_lon]).addTo(map);
                    marker.bindPopup(`
            <strong>${event.title}</strong><br/>
            ${event.venue_name}, ${event.venue_city}<br/>
            ${event.start_date} ${event.start_time || ""}
          `);
                }
            });
        })
        .catch(error => {
            console.error('Error loading events:', error);
        });
</script>

</body>
</html>