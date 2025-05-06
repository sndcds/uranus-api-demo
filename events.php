<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Eventliste</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .event {
            background-color: white;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .event h2 {
            margin-top: 0;
        }
        .event img {
            max-width: 300px;
            display: block;
            margin-top: 10px;
        }
        .meta {
            color: #555;
        }
        summary {
            cursor: pointer;
            color: #0077cc;
        }
        .details {
            color: #555;
            font-size: 0.8em;
        }

        .genre-tags {
            display: inline;
            margin: 5px 0;
        }

        .event-type-tags {
            display: inline-block;
            margin: 5px 0;
        }

        .tag {
            background-color: #e0f0ff;
            color: #005080;
            display: inline-block;
            padding: 4px 8px;
            margin: 2px;
            border-radius: 12px;
            font-size: 0.7em;
            font-family: sans-serif;
        }

        .genre-tags .tag {
            background-color: #e0f0ff;
            color: #005080;
        }

        .event-type-tags .tag {
            background-color: #ffe0f0;
            color: #800050;
        }

    </style>
</head>
<body>

<?php

// Base URL of your API
$apiBaseUrl = "http://localhost:9090/query";

// Build query string from current page's URL parameters
$queryString = http_build_query($_GET);

// Full API URL with parameters
$apiUrl = $apiBaseUrl . '?' . $queryString;

// Fetch JSON data from the API
$json = file_get_contents($apiUrl);
if ($json === false) {
    die("Error fetching data from API: $apiUrl");
}

// Decode JSON into associative array
$data = json_decode($json, true);
// Basic error handling
if (!$data || !isset($data['events'])) {
    echo "<p>⚠️ Fehler beim Abrufen der Eventdaten.</p>";
    exit;
}

echo "<h1>Gefundene Events: " . htmlspecialchars($data['total']) . "</h1>";
echo "<p class='meta'>Abfragezeit: " . htmlspecialchars($data['time']) . "</p>";

// Display each event
foreach ($data['events'] as $event) {
    echo "<div class='event'>";
    echo "<h2>" . htmlspecialchars($event['event_title']) . "</h2>";

    echo "<p class='meta'>" . htmlspecialchars($event['date_start']) .
         " / " . htmlspecialchars($event['time_start']) . " / ";
    echo htmlspecialchars($event['venue_name']) .
         " / " . htmlspecialchars($event['venue_city']) . "</p>";

    if (!empty($event['teaser_text'])) {
        echo "<p><em>" . nl2br(htmlspecialchars($event['teaser_text'])) . "</em></p>";
    }

    echo "<p>" . nl2br(htmlspecialchars($event['description_preview'])) . "</p>";

    if (!empty($event['description'])) {
        echo "<details><summary>Vollständige Beschreibung</summary>";
        echo "<p>" . nl2br(htmlspecialchars($event['description'])) . "</p>";
        echo "</details>";
    }

    if (!empty($event['event_types']) && is_array($event['event_types'])) {
        $filtered = array_filter($event['event_types'], fn($t) => isset($t['name']) && $t['name'] !== null);
        if (!empty($filtered)) {
            echo '<div class="event-type-tags">';
            foreach ($filtered as $type) {
                echo '<span class="tag">' . htmlspecialchars($type['name']) . '</span>';
            }
            echo '</div>';
        }
    }

    if (!empty($event['genre_types']) && is_array($event['genre_types'])) {
        $filteredGenres = array_filter($event['genre_types'], fn($g) => isset($g['name']) && $g['name'] !== null);
        if (!empty($filteredGenres)) {
            echo '<div class="genre-tags">';
            foreach ($filteredGenres as $genre) {
                echo '<span class="tag">' . htmlspecialchars($genre['name']) . '</span>';
            }
            echo '</div>';
        }
    }

    if (!empty($event['img_src_name'])) {
        $img = htmlspecialchars($event['img_src_name']);
        echo "<https://api.uranus.oklabflensburg.de/uploads/$img' alt='Event Bild'>";
    }

    echo "<p class='details'><strong>Veranstalter:</strong> " . htmlspecialchars($event['organizer_name']) . "</p>";
    echo "<p class='details'><strong>Raum:</strong> " . htmlspecialchars($event['space_name']);
    if ($event['space_total_capacity'] != null) {
        echo " (Kapazität: " . htmlspecialchars($event['space_total_capacity']) . ")</p>";
    }


    echo "</div>";
}
?>

</body>
</html>