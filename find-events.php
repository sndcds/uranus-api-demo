<?php
include_once('config.php');
$apiUrl = $apiBaseUrl . "/query?mode=event";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event API Query</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2rem;
            background-color: #f9f9f9;
        }

        h1 {
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            max-width: 700px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-group {
            display: flex;
            margin-bottom: 1rem;
        }

        .form-group label {
            width: 250px;
            font-weight: bold;
            padding-right: 10px;
            align-self: center;
        }

        .form-group input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input[type="number"] {
            max-width: 200px;
        }

        .form-group input[type="submit"] {
            margin-left: 250px;
            width: auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h1>Event Search</h1>

<form method="POST" action="event-tiles.php">
    <?php
    $fields = [
        ['lang', 'Language (e.g. en, de)', 'text'],
        ['start', 'Start Date (YYYY-MM-DD)', 'date'],
        ['end', 'End Date (YYYY-MM-DD)', 'date'],
        ['search', 'Search', 'text'],
        ['events', 'Event IDs (comma-separated)', 'text'],
        ['venues', 'Venue IDs (comma-separated)', 'text'],
        ['spaces', 'Space IDs (comma-separated)', 'text'],
        ['organizers', 'Organizer IDs (comma-separated)', 'text'],
        ['countries', 'Country Codes (comma-separated)', 'text'],
        ['postal_code', 'Postal Code', 'text'],
        ['city', 'City', 'text'],
        ['accessibility', 'Accessibility Flags (comma-separated)', 'text'],
        ['visitor_infos', 'Visitor Info Flags (comma-separated)', 'text'],
        ['lon', 'Longitude', 'text'],
        ['lat', 'Latitude', 'text'],
        ['radius', 'Radius (meters)', 'number'],
        ['event_types', 'Event Types (comma-separated IDs)', 'text'],
        ['genre_types', 'Genre Types (comma-separated IDs)', 'text'],
        ['limit', 'Limit', 'number'],
        ['offset', 'Offset', 'number'],
    ];

    foreach ($fields as [$id, $label, $type]) {
        echo '<div class="form-group">';
        echo "<label for=\"$id\">$label:</label>";
        echo "<input type=\"$type\" id=\"$id\" name=\"$id\">";
        echo '</div>';
    }
    ?>

    <div class="form-group">
        <input type="submit" value="Search Events">
    </div>
</form>

</body>
</html>