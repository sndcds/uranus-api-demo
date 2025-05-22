<?php
include("events-main.php");
?>

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
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .event-grid a {
            display: block;
            width: 100%;
            height: 100%;
            text-decoration: none;
        }

        .event-tile {
            position: relative;
            width: 100%;
            padding-top: 75%; /* 4:3 ratio */
            background-size: cover;
            background-position: center;
            background-color: #ccc;
        }

        .overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            box-sizing: border-box;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.6), transparent);
            color: white;
            padding: 6px;
            font-size: 0.85em;
            line-height: 1.4;
            font-family: sans-serif;
        }

        .overlay span, h1 {
            background-color: rgba(255, 255, 255, 0.9);
            color: black;
            padding: 2px 4px;
            display: inline-block;
            margin-bottom: 2px;
            white-space: nowrap;
        }

        .overlay h1 {
            font-size: 1.6em;
            margin-top: 0;
        }

        @media (max-width: 1280px) {
            .event-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 16px;
            }
        }

        @media (max-width: 960px) {
            .event-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 16px;
            }
        }

        @media (max-width: 640px) {
            .event-grid {
                display: grid;
                grid-template-columns: repeat(1, 1fr);
                gap: 16px;
            }
        }
    </style>
</head>
<body>

<div class="event-grid">
<?php
foreach ($data['events'] as $i => $event) {
    $image_id = htmlspecialchars((string)($event['image_id'] ?? ''));
    $image_focus_x = htmlspecialchars((string)($event['image_focus_x'] ?? '0.5'));
    $image_focus_y = htmlspecialchars((string)($event['image_focus_y'] ?? '0.5'));

    $image_params = [
        'id' => $image_id,
        'mode' => 'cover',
        'width' => 640,
        'ratio' => '3by2',
        'focusx' => $image_focus_x,
        'focusy' => $image_focus_y,
        'type' => 'webp',
        'quality' => 90,
    ];

    $imageUrl = $apiBaseUrl . '/image/get?' . http_build_query($image_params);
    $divId = "tile-$i";

    $url    = "event.php?id=" . urlencode($event['id']);
    $title  = htmlspecialchars($event['title']);
    $date   = htmlspecialchars($event['start_date']);
    $venue  = htmlspecialchars($event['venue_name']);
    $city   = htmlspecialchars($event['venue_city']);

    echo <<<HTML
    <a href="$url">
        <div class="event-tile pluto-image-tile" id="$divId" style="background-image:url($imageUrl);">
            <div class="overlay">
                <span>$date</span><br>
                <h1>$title</h1><br>
                <span>$venue, $city</span>
                $imageUrl
            </div>
        </div>
    </a>
    HTML;
}
?>
</div>

<!-- <script type="module" src="image-loader.js"></script>-->

</body>
</html>