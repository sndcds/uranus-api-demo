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

        h1, .meta {
            text-align: center;
        }

        .event-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .event-tile {
            background-color: white;
            border-radius: 10px;
            width: 300px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }

        .event-tile h2 {
            font-size: 1.2em;
            margin: 0 0 5px;
        }

        .event-tile .meta {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 10px;
        }

        .event-tile img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
            margin-bottom: 10px;
        }

        summary {
            cursor: pointer;
            color: #0077cc;
        }

        .tag {
            background-color: #e0f0ff;
            color: #005080;
            display: inline-block;
            padding: 4px 8px;
            margin: 2px;
            border-radius: 12px;
            font-size: 0.7em;
        }

        .genre-tags .tag {
            background-color: #e0f0ff;
            color: #005080;
        }

        .event-type-tags .tag {
            background-color: #ffe0f0;
            color: #800050;
        }

        .details {
            font-size: 0.8em;
            color: #444;
        }
    </style>
</head>
<body>

<?php
include("events-fetch.php");

echo "<h1>Gefundene Events: " . htmlspecialchars($data['total']) . "</h1>";
echo "<p class='meta'>Abfragezeit: " . htmlspecialchars($data['time']) . "</p>";

echo "<div class='event-grid'>";
foreach ($data['events'] as $event) {
    if (!empty($event['has_main_image'])) {
        $imageId = htmlspecialchars((string)($event['image_id'] ?? ''));
        $imageFocusX = htmlspecialchars((string)($event['image_focus_x'] ?? '0.5'));
        $imageFocusY = htmlspecialchars((string)($event['image_focus_y'] ?? '0.5'));
        $imageParams = [
            'id' => $imageId,
            'mode' => 'cover',
            'width' => 333,
            'ratio' => '3by2',
            'focusx' => $imageFocusX,
            'focusy' => $imageFocusY,
            'type' => 'webp',
            'quality' => 90,
        ];
        $imageUrl = $apiBaseUrl . '/image/get?' . http_build_query($imageParams);
    }
    else {
        $imageUrl = "https://grain.one/img/uranus.jpg";
    }

    echo "<div class='event-tile pluto-image-tile'>";
    echo "<h2>" . htmlspecialchars($event['title']) . "</h2>";
    echo "<div class='meta'>" . htmlspecialchars($event['start_date']) . " – " .
         htmlspecialchars($event['start_time']) . "<br>" .
         htmlspecialchars($event['venue_name']) . " / " .
         htmlspecialchars($event['venue_city'] ?? '-') . "</div>";

    echo '<div class="event-tile pluto-image-tile" style="background-image:url(' . $imageUrl . '); box-sizing: border-box; width: 100%; aspect-ratio: 3 / 2; background-size: cover;"> </div>';

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

    require_once('text-utils.php');
    $short = "";
    if (!empty($event['teaser_text'])) {
        $short = $event['teaser_text'];
    }
    else {
        $short = getFirstWords($event['description'], 30, ' ...');
    }

    echo "<p>" . nl2br(htmlspecialchars($short)) . "</p>";

    if (!empty($event['description'])) {
        echo "<details><summary>Beschreibung</summary>";
        echo "<p>" . nl2br(htmlspecialchars($event['description'])) . "</p>";
        echo "</details>";
    }

    echo "<p class='details'><strong>Veranstalter:</strong> " . htmlspecialchars($event['organizer_name']) . "</p>";
    echo "<p class='details'><strong>Raum:</strong> " . htmlspecialchars($event['space_name']);
    if ($event['space_total_capacity'] != null) {
        echo " (Kapazität: " . htmlspecialchars($event['space_total_capacity']) . ")";
    }
    echo "</p>";

    if (!empty($event['accessibility_flag_names'])) {
        echo "<ul style='font-size:0.8em;'>";
        foreach ($event['accessibility_flag_names'] as $feature) {
            echo "<li>" . htmlspecialchars($feature) . "</li>";
        }
        echo "</ul>";
    }

    if (!empty($event['visitor_info_flag_names'])) {
        echo "<ul style='font-size:0.8em;'>";
        foreach ($event['visitor_info_flag_names'] as $info) {
            echo "<li>" . htmlspecialchars($info) . "</li>";
        }
        echo "</ul>";
    }

    echo "</div>";
}
echo "</div>";

?>

</body>
</html>