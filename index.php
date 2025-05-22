<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Linkliste</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        th {
            background-color: #eee;
            text-align: left;
        }
        a {
            color: #0077cc;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h1>Beispiele</h1>

<?php

include_once('config.php');

$data = [
    ["description" => "Open Data (JSON)", "link" => $apiBaseUrl . "/query?mode=event&start=2024-01-01"],
    ["description" => "Liste", "link" => "event-list.php?mode=event&start=2024-01-01"],
    ["description" => "Details", "link" => "event-tiles.php?mode=event&start=2024-01-01"],
    ["description" => "Stadt", "link" => "event-img-grid.php?mode=event&city=*flens*"],
    ["description" => "Land", "link" => "event-img-grid.php?mode=event&countries=DEU"],
    ["description" => "PLZ", "link" => "event-img-grid.php?mode=event&postal_code=2493*"],
    ["description" => "Spielstätte", "link" => "event-img-grid.php?mode=event&venues=13"],
    ["description" => "Raum", "link" => "event-list.php?mode=event&start=2024-01-01&spaces=64"],
    ["description" => "Radius", "link" => "event-img-grid.php?mode=event&start=2024-01-01&lon=9.431297&lat=54.791603&radius=80"],
    ["description" => "Titel", "link" => "event-img-grid.php?mode=event&title=*pap*"],
    ["description" => "Text", "link" => "event-img-grid.php?mode=event&search=*Besteck*"],
    ["description" => "Datum", "link" => "event-img-grid.php?mode=event&start=2024-01-01&date=2026-01-01"],
    ["description" => "Uhrzeit", "link" => "event-img-grid.php?mode=event&time=10,14"],
    ["description" => "Art(en)", "link" => "event-list.php?mode=event&start=2024-01-01&event_types=1"],
    ["description" => "Genre(s)", "link" => "event-list.php?mode=event&start=2024-01-01&genre_types=2"],
    ["description" => "Sprache", "link" => "event-list.php?mode=event&start=2024-01-01&lang=da"],
    ["description" => "Barrierefreiheit", "link" => "event-tiles.php?mode=event&start=2024-01-01&accessibility=5"],
    ["description" => "Besucherinfos", "link" => "event-tiles.php?mode=event&start=2024-01-01&visitor_infos=4"],
    ["description" => "Venue Map (JSON)", "link" => $apiBaseUrl . "/query?mode=venue-map&start=2024-01-01"],
    ["description" => "Karte Leaflet, Events", "link" => "map-leaflet.php"],
    ["description" => "Karte MapLibre, Venues", "link" => "map-maplibre.php"],
    ["description" => "Image", "link" => $apiBaseUrl . "/image/get?id=52&mode=cover&width=400&ratio=3by2&focusx=0.5&focusy=0.5&type=webp&quality=90"],

    ["description" => "Login", "link" => "login.php"],
    ["description" => "Bild hochladen", "link" => "upload-image.php"],
    ["description" => "Event erstellen", "link" => "create-event.php"],
];

$json = json_encode($data);


// Decode JSON into array
$data = json_decode($json, true);

// Check for valid JSON
if (is_array($data)) {
    echo "<table>";
    echo "<thead><tr><th>Beschreibung</th><th>Link</th></tr></thead>";
    echo "<tbody>";
    foreach ($data as $entry) {
        $desc = htmlspecialchars($entry['description']);
        $link = htmlspecialchars($entry['link']);
        echo "<tr>";
        echo "<td>$desc</td>";
        echo "<td><a href=\"$link\" target=\"_blank\">$link</a></td>";
        echo "</tr>";
    }
    echo "</tbody></table>";
}
else {
    echo "<p>Fehler beim Verarbeiten der JSON-Daten.</p>";
}

echo '<h1>Cookies</h1>';
foreach ($_COOKIE as $name => $value) {
    echo htmlspecialchars($name) . ': ' . htmlspecialchars($value) . '<br>';
}
?>

</body>
</html>