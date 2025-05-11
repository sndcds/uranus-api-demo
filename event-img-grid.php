<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Event Grid</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
        }

        .event-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0;
        }

        .event-tile {
            position: relative;
            width: 25%; /* 4 per row */
            aspect-ratio: 4 / 3;
            background-size: cover;
            background-position: center;
        }

        .overlay {
            box-sizing: border-box;
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            color: white;
            background: rgba(0, 0, 0, 0.0);
            padding: 4px 6px;
            font-size: 0.85em;
            line-height: 1.3;
            font-family: sans-serif;
        }
        .overlay span {
            background-color: rgba(255, 255, 255, 1);
            color: black;
            padding: 2px 4px;
            display: inline-block;
            margin-bottom: 2px; /* optional spacing between lines */
            width: max-content; /* so background wraps just the text */
        }
    </style>
</head>
<body>

<?php
include("events-main.php");

echo "<div class='event-grid'>";
foreach ($data['events'] as $event) {
    if (!empty($event['img_src_name'])) {
        $img = htmlspecialchars($event['img_src_name']);
        $backgroundUrl = "https://api.uranus.oklabflensburg.de/uploads/$img";
    }
    else {
        $backgroundUrl = "https://grain.one/img/uranus.jpg";
    }

    $date = htmlspecialchars($event['start_date']);
    $title = htmlspecialchars($event['event_title']);
    $venue = htmlspecialchars($event['venue_name']);
    $city = htmlspecialchars($event['venue_city']);

    echo "<div class='event-tile' style=\"background-image: url('$backgroundUrl')\">";
    echo "<div class='overlay'>
            <span><strong>$title</strong></span><br>
            <span>$date</span>
            <span>$venue, $city</span>
          </div>";
    echo "</div>";
}
echo "</div>";

?>

</body>
</html>