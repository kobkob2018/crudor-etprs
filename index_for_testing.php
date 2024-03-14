<?php

// Define your access token and WhatsApp business number
$accessToken = 'EAANAw9SYST0BO9gpHjQs2yZBFC7P64N2zNUv4MLdr5ZA5YOVQjCXly2jH0rXFmALxeP0klM1zs5Py378VhXZAQ51uedkbu0hkbs4m4NJvLC74R51s1AfSSeShrBvnOLwSq2PfnK0bq14Gla3D5H5rlMqKwPSpEFwmYyetG6MjM6GyJ3nwp1ZA6Ni3KaJ1IFAHXNcVQZDZD';
$whatsappNumber = '972722706395';

// Define the API endpoint for sending WhatsApp messages
$apiEndpoint = 'https://graph.facebook.com/v13.0/me/messages?access_token=' . $accessToken;

// Define the message template including image and text
$templateMessage = [
    "recipient" => [
        "whatsapp" => "+{$whatsappNumber}"
    ],
    "message" => [
        "attachment" => [
            "type" => "template",
            "payload" => [
                "template_type" => "media",
                "elements" => [
                    [
                        "media_type" => "image",
                        "url" => "URL_TO_YOUR_IMAGE",
                        "buttons" => [],
                    ]
                ]
            ]
        ]
    ]
];

// Send the API request to send the message
$response = sendMessage($apiEndpoint, $templateMessage);

// Check the response
if ($response["status_code"] == 200) {
    echo "Image sent successfully!\n";
} else {
    echo "Failed to send image. Status code: {$response['status_code']}, Response: {$response['response']}\n";
}

// Define the text message template
$textMessage = [
    "recipient" => [
        "whatsapp" => "+{$whatsappNumber}"
    ],
    "message" => [
        "text" => "Your text message here"
    ]
];

// Send the API request to send the text message
$response = sendMessage($apiEndpoint, $textMessage);

// Check the response
if ($response["status_code"] == 200) {
    echo "Text message sent successfully!\n";
} else {
    echo "Failed to send text message. Status code: {$response['status_code']}, Response: {$response['response']}\n";
}

// Function to send API request
function sendMessage($endpoint, $message) {
    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [
        "status_code" => $statusCode,
        "response" => $response
    ];
}