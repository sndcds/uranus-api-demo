<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Eventliste – Tabellenansicht</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f9f9f9;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 12px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
        }
        .tag {
            display: inline-block;
            padding: 2px 6px;
            margin: 2px;
            border-radius: 12px;
            font-size: 0.75em;
        }
        .event-type {
            background-color: #ffe0f0;
            color: #800050;
        }
        .genre {
            background-color: #e0f0ff;
            color: #005080;
        }
    </style>
</head>
<body>

<?php
include("events-main.php");

echo "<h1>Gefundene Events: " . htmlspecialchars($data['total']) . "</h1>";
echo "<p><strong>Abfragezeit:</strong> " . htmlspecialchars($data['time']) . "</p>";

echo "<table>";
echo "<tr>
        <th>Datum</th>
        <th>Uhrzeit</th>
        <th>Titel</th>
        <th>Venue</th>
        <th>Stadt</th>
        <th>Event-Typen</th>
        <th>Genres</th>
      </tr>";

foreach ($data['events'] as $event) {
    $date = htmlspecialchars($event['start_date']);
    $time = htmlspecialchars($event['start_time'] ?? '-');
    $title = htmlspecialchars($event['title']);
    $venue = htmlspecialchars($event['venue_name']);
    $city = htmlspecialchars($event['venue_city'] ?? '-');

    $eventTypes = '';
    if (!empty($event['event_types']) && is_array($event['event_types'])) {
        $filtered = array_filter($event['event_types'], fn($t) => isset($t['name']) && $t['name'] !== null);
        foreach ($filtered as $type) {
            $eventTypes .= "<span class='tag event-type'>" . htmlspecialchars($type['name']) . "</span>";
        }
    }

    $genres = '';
    if (!empty($event['genre_types']) && is_array($event['genre_types'])) {
        $filteredGenres = array_filter($event['genre_types'], fn($g) => isset($g['name']) && $g['name'] !== null);
        foreach ($filteredGenres as $genre) {
            $genres .= "<span class='tag genre'>" . htmlspecialchars($genre['name']) . "</span>";
        }
    }

    echo "<tr>
            <td>$date</td>
            <td>$time</td>
            <td>$title</td>
            <td>$venue</td>
            <td>$city</td>
            <td>$eventTypes</td>
            <td>$genres</td>
          </tr>";
}

echo "</table>";
?>

</body>
</html>