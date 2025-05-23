<?php
include_once("config.php");
include("html-page-head.php");


$eventId = $_GET['id'] ?? null;
if (!$eventId) {
    echo "<p>⚠️ No event ID provided.</p>";
    exit;
}

$apiUrl = $apiBaseUrl . '/query?mode=event&events=' . urlencode($eventId) . '&start=1970-01-01';
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

// Fetch and decode
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200 || !$response) {
    echo "<p>⚠️ Failed to fetch event.</p>";
    exit;
}

$data = json_decode($response, true);
$event = $data['events'][0] ?? null;
if (!$event) {
    echo "<p>⚠️ Event not found.</p>";
    exit;
}
?>

<body>
<div class="container">

    <?php
        if (!empty($event['has_main_image'])) {
            $imageId = htmlspecialchars((string)($event['image_id'] ?? ''));
            $imageFocusX = htmlspecialchars((string)($event['image_focus_x'] ?? '0.5'));
            $imageFocusY = htmlspecialchars((string)($event['image_focus_y'] ?? '0.5'));
            $imageParams = [
                'id' => $imageId,
                'mode' => 'cover',
                'width' => 960,
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

        echo '<div style="background-image:url(' . $imageUrl . '); width: 100%; aspect-ratio: 3 / 2; background-size: cover;"> </div>';

        if ($event['title'] != null) {
            echo "<h1>" . htmlspecialchars($event['title']) . "</h1>";
        }
        if ($event['subtitle'] != null) {
            echo "<h2>" . htmlspecialchars($event['subtitle']) . "</h2>";
        }

        echo '<p class="meta">';
        if ($event['start_date'] != null) {
            echo htmlspecialchars($event['start_date']);
        }
        if ($event['start_time'] != null) {
            echo ' / ' . htmlspecialchars($event['start_time']) . ' Uhr';
        }
        echo '</p>';
        echo '<p class="meta">';
        if ($event['end_date'] != null) {
            echo ' / ' . htmlspecialchars($event['end_date']);
        }
        if ($event['end_time'] != null) {
            echo ' / ' . htmlspecialchars($event['end_time']);
        }
        if ($event['entry_time'] != null) {
            echo '/ Einlass: ' . htmlspecialchars($event['entry_time']);
        }
        if ($event['duration'] != null) {
            echo '/ Dauer: ' . htmlspecialchars($event['duration']) . ' Min.';
        }
        echo '</p>';
    ?>

    <div class="tags event-type-tags">
        <?php foreach ($event['event_types'] ?? [] as $et): ?>
            <span><?= htmlspecialchars($et['name']) ?></span>
        <?php endforeach; ?>
    </div>

    <div class="tags genre-tags">
        <?php foreach ($event['genre_types'] ?? [] as $gt): ?>
            <span><?= htmlspecialchars($gt['name']) ?></span>
        <?php endforeach; ?>
    </div>

    <?php if (!empty($event['description'])): ?>
        <div class="section">
            <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($event['participation_info'])): ?>
        <div class="section">
            <p><strong>Teilnahmeinfos:</strong> <?= nl2br(htmlspecialchars($event['participation_info'])) ?></p>
        </div>
    <?php endif; ?>

    <?php if (!empty($event['meeting_point'])): ?>
        <div class="section">
            <p><strong>Treffpunkt:</strong> <?= nl2br(htmlspecialchars($event['meeting_point'])) ?></p>
        </div>
    <?php endif; ?>


  <div class="section">
    <h3>Ort</h3>
    <p><?= htmlspecialchars($event['venue_name'] ?? '-') ?>, <?= htmlspecialchars($event['venue_street'] ?? '-') ?> <?= htmlspecialchars($event['venue_house_number'] ?? '-') ?><br>
       <?= htmlspecialchars($event['venue_postal_code'] ?? '-') ?> <?= htmlspecialchars($event['venue_city'] ?? '-') ?>, <?= htmlspecialchars($event['venue_country'] ?? '-') ?>
    </p>
  </div>

  <div class="section">
    <h3>Raum</h3>
    <p><?= htmlspecialchars($event['space_name'] ?? '-') ?> — Capacity: <?= htmlspecialchars($event['space_total_capacity'] ?? '-') ?>, Seating: <?= htmlspecialchars($event['space_seating_capacity'] ?? '-') ?></p>
  </div>

  <div class="section">
    <h3>Veranstalter</h3>
    <p><?= htmlspecialchars($event['organizer_name']) ?></p>
  </div>

  <div class="section">
    <h3>Barriereinformationen</h3>
    <div class="tags">
      <?php foreach ($event['accessibility_flag_names'] ?? [] as $flag): ?>
        <span><?= htmlspecialchars($flag) ?></span>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="section">
    <h3>Infos für den Besuch</h3>
    <div class="tags">
      <?php foreach ($event['visitor_info_flag_names'] ?? [] as $flag): ?>
        <span><?= htmlspecialchars($flag) ?></span>
      <?php endforeach; ?>
    </div>
  </div>

</div>

</body>
</html>