<?php

$option = $argv[1];
$argument = $argv[2];

if ($option === '-s') {
    $data = checkKey($argument);

    echo json_encode($data, JSON_PRETTY_PRINT);
} elseif ($option === '-f') {
    $fh = fopen($argument, 'r');

    while ($apiKey = fgets($fh)) {
        $data = checkKey($apiKey);

        echo json_encode($data, JSON_PRETTY_PRINT);
        echo PHP_EOL;
    }

    fclose($fh);
} else {
    echo "Invalid Option";

    exit;
}

/**
 * Check API Key from SendGrid.
 *
 * @param  string  $apiKey
 *
 * @return array
 */
function checkKey(string $apiKey) : object
{
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://api.sendgrid.com/v3/user/credits',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $apiKey
        ]
    ]);

    $httpRequest = curl_exec($ch);

    curl_close($ch);

    return json_decode($httpRequest);
}
