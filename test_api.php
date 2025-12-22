<?php

// Test API endpoints
$baseUrl = 'http://127.0.0.1:8000';

// Test get settings
echo "Testing GET settings...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/admin/ui-configuration/settings');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

// Test update settings (simple data)
echo "Testing POST update...\n";
$data = [
    'site_name' => 'Test Site',
    'primary_color' => '#FF0000',
    'email_notifications' => '1',
    'browser_notifications' => '0'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/admin/ui-configuration/update');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest',
    'X-CSRF-TOKEN: test' // This will fail but we can see the error
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";