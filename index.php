<!DOCTYPE html>
<html>
<head>
    <title>Fishing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 10px;
            background: #f9f9f9;
        }
        .container {
            max-width: 400px;
            margin: auto;
            background: #fff;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-top: 12px;
            margin-bottom: 4px;
        }
        input[type="text"], input[type="file"] {
            width: 100%;
            box-sizing: border-box;
            padding: 8px;
            margin-bottom: 12px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #0074d9;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #005fa3;
        }
        @media (max-width: 500px) {
            .container {
                max-width: 98vw;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <label for="competitorNumber">introduza o número do concorrente/competitors num:</label>
    <input type="text" id="competitorNumber">

    <label for="fishLength">introduza o comprimento do peixe/enter fish length(cm):</label>
    <input type="text" id="fishLength">

    <label for="fishImage">introduza a imagem do peixe/upload fish image</label>
    <input type="file" id="fishImage" accept="image/*">

    <button onclick="saveData()">Save</button>
</div>
<script>
function saveData() {
    var competitorNumber = document.getElementById('competitorNumber').value;
    var fishLength = document.getElementById('fishLength').value;
    var formData = new FormData();
    formData.append('competitorNumber', competitorNumber);
    formData.append('fishLength', fishLength);
    formData.append('fishImage', document.getElementById('fishImage').files[0]);

    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(() => {
        alert(
            "Competitor Number: " + competitorNumber + "\n" +
            "Fish Length: " + fishLength
        );
    });
}
</script>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $competitorNumber = isset($_POST['competitorNumber']) ? preg_replace('/[^0-9a-zA-Z]/', '', $_POST['competitorNumber']) : '';
    $fishLength = isset($_POST['fishLength']) ? $_POST['fishLength'] : '';
    $fishImage = isset($_FILES['fishImage']) ? $_FILES['fishImage'] : null;

    if ($competitorNumber && $fishLength && $fishImage && isset($fishImage['tmp_name'])) {
        $baseDir = __DIR__ . '/competitors/';
        $competitorDir = $baseDir . $competitorNumber . '/';

        if (!is_dir($competitorDir)) {
            mkdir($competitorDir, 0777, true);
        }

        // Find next available fish number
        $fishNum = 1;
        while (file_exists($competitorDir . "fish{$fishNum}.txt") || file_exists($competitorDir . "fish{$fishNum}.jpg")) {
            $fishNum++;
        }

        // Save fish length as text file
        file_put_contents($competitorDir . "fish{$fishNum}.txt", $fishLength);

        // Save image
        $targetFile = $competitorDir . "fish{$fishNum}.jpg";
        move_uploaded_file($fishImage['tmp_name'], $targetFile);

        echo 'Data saved successfully!';
    } else {
        echo "";
    }
}
?>
</body>
</html>