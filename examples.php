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
// Define table content as JSON
$json = '[
    {"description": "Nach Land", "link": "event-img-grid.php?mode=event&country=DNK"},
    {"description": "Nach PLZ", "link": "event-img-grid.php?mode=event&postal_code=24939"},
    {"description": "Nach Datum", "link": "event-img-grid.php?mode=event&date_start=2025-01-01&date_end=2026-01-01"},
    {"description": "Detailkacheln", "link": "event-tiles.php?mode=event&date_start=2025-01-01"},
    {"description": "Liste", "link": "event-list.php?mode=event&date_start=2025-01-01"}
]';

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
} else {
    echo "<p>Fehler beim Verarbeiten der JSON-Daten.</p>";
}
?>

</body>
</html>