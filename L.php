<!DOCTYPE html>
<html>
<head>
    <title>Leaderboards</title>
</head>
<body>
<h1>Leaderboards</h1>
<p>Check the latest fishing competition results below:</p>
<?php
$baseDir = __DIR__ . '/competitors/';
$competitorDirs = glob($baseDir . '*', GLOB_ONLYDIR);

foreach ($competitorDirs as $competitorDir) {
    $competitorNumber = basename($competitorDir);
    $fishTxtFiles = glob($competitorDir . '/fish*.txt');
    if (count($fishTxtFiles) === 0) continue;

    // Collect fish data
    $fishData = [];
    foreach ($fishTxtFiles as $fishTxtFile) {
        $fishNum = basename($fishTxtFile, '.txt');
        $fishLengthRaw = file_get_contents($fishTxtFile);
        $fishImgFile = $competitorDir . '/' . $fishNum . '.jpg';
        $fishData[] = [
            'num' => $fishNum,
            'length' => $fishLengthRaw,
            'img' => file_exists($fishImgFile)
                ? "<img src='competitors/$competitorNumber/$fishNum.jpg' style='max-width:100px;max-height:100px;'>"
                : "No image"
        ];
    }

    // Sort by length descending (as float)
    usort($fishData, function($a, $b) {
        return floatval($b['length']) <=> floatval($a['length']);
    });

    // Take top 3
    $topFish = array_slice($fishData, 0, 3);

    // Calculate total points (sum of top 3 fish points)
    $points = 0;
    foreach ($topFish as $fish) {
        $length = floatval($fish['length']);
        $points += 100 + max(0, floor($length - 15));
    }

    echo "<h2>Competitor $competitorNumber - Total Points: $points</h2>";
    echo "<table border='1'><tr><th>Fish #</th><th>Length (cm)</th><th>Fish Points</th><th>Image</th></tr>";

    foreach ($topFish as $fish) {
        $length = floatval($fish['length']);
        $fishPoints = 100 + max(0, floor($length - 15));
        echo "<tr>
                <td>{$fish['num']}</td>
                <td>{$fish['length']}</td>
                <td>$fishPoints</td>
                <td>{$fish['img']}</td>
              </tr>";
    }
    echo "</table><br>";
}
?>
</body>
</html>