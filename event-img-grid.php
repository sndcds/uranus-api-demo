<?php
include("events-fetch.php");
include("html-page-head.php");
?>

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