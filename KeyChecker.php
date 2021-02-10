<?php

$option = $argv[1];
$argument = $argv[2];

if ($option === '-s') {
    $data = checkKey($argument);
    printResult($data, $argument);
} elseif ($option === '-f') {
    $fh = fopen($argument, 'r');

    while ($apiKey = fgets($fh)) {
        $data = checkKey($apiKey);
        printResult($data, $apiKey);
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

/**
 * Print the output from SendGrid.
 *
 * @param  object  $data
 * @param  string  $apiKey
 *
 * @return void
 */
function printResult(object $data, string $apiKey) : void
{
    if (isset($data->errors)) {
        echo "------------------------------------------------------------------------------" . PHP_EOL;
        echo "API Key: " . $apiKey . PHP_EOL;
        echo PHP_EOL;
        echo "Result: API Key is Invalid." . PHP_EOL;
        echo "------------------------------------------------------------------------------" . PHP_EOL;

        return;
    }

    echo "------------------------------------------------------------------------------" . PHP_EOL;
    echo "API Key: " . $apiKey . PHP_EOL;
    echo PHP_EOL;
    echo "Result: API Key is Valid." . PHP_EOL;
    echo "Limit: " . $data->total . PHP_EOL;
    echo "Used: " . $data->used . PHP_EOL;
    echo "Reset: " . $data->reset_frequency . PHP_EOL;
    echo "------------------------------------------------------------------------------" . PHP_EOL;
}
