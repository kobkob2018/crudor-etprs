<?php

//EAANAw9SYST0BOZCWnsEWzZB6xWxjAKko20KZCY5uem5YsLx3goHiyL4nuX7dw5WCaLnxTbwB7cCDzddyI8qIABVWHT607az5c1TQuMtJFkl3RPqTDLHQan0RQCHuR8COD4aosTm9W0ZCc4MVTR6wBvbL7uxZCK8qGH1DfQYFyxjr7SZCXZCC4Q0HyZCtc0TgxFqkeuX376N71FAM2vU2iFMZByLLIZBJbOSkwltF6SEnh9D6TFRBhITuXZC


$url = "https://graph.facebook.com/v17.0/163320550208543/messages";
// Create a new cURL resource
$ch = curl_init($url);

$data = array(
	'messaging_product'=> "whatsapp",
    "to"=> "972542393397", //972525572555 // 972542393397
    "type"=> "template",
    "template"=> array(
        "name"=> "hello_world",
        "language"=> array(
            "code"=> "en_US"
        )
    )
);


$data2 = array(
    'messaging_product' => "whatsapp",
    "to" => "972542393397",
    "type" => "text",
    "text" => array(
		"preview_url"=> false,
        "body" => "אה יה נקניק! מה הולך",
    )
); 

$payload = json_encode($data);

// Attach encoded JSON string to the POST fields
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

// Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer EAANAw9SYST0BO8LegodSj7UncsZAzT21oR0SOHpIv598V3r0d7cDqiKiJEQpOXIOqCTM4qsZBwZCLgljjWwwufNdGm6GdNPnOevFzhWSzCEij8nR8UZBxBWIiBXRjVIZCIwHJUaPutVED7eE5KDcYFguX0ZAXHbfxQYDGjWuDdUznZA9i6iawKRXP8ehcW6FsFqn3ux4aZBFD4mDhy87zOSiKMNATykzBakT'));

// Return response instead of outputting
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the POST request
$result = curl_exec($ch);

// Close cURL resource
curl_close($ch);

print($result);

exit();






