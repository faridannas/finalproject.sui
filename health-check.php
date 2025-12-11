#!/usr/bin/env php
<?php

/**
 * Quick Health Check Script
 * Tests critical endpoints and functionality
 */

echo "🔍 SEBLAK UMI AI - HEALTH CHECK\n";
echo "================================\n\n";

$baseUrl = 'http://127.0.0.1:8000';
$results = [];

// Test endpoints
$endpoints = [
    'Landing Page' => '/',
    'Products Page' => '/products',
    'Categories Page' => '/categories',
    'Testimonials Page' => '/testimonials',
    'Login Page' => '/login',
    'Register Page' => '/register',
];

echo "📡 Testing Public Endpoints...\n";
echo "--------------------------------\n";

foreach ($endpoints as $name => $path) {
    $url = $baseUrl . $path;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $status = ($httpCode >= 200 && $httpCode < 400) ? '✅' : '❌';
    $results[$name] = $httpCode;
    
    echo sprintf("%-25s %s HTTP %d\n", $name, $status, $httpCode);
}

echo "\n";
echo "🔐 Testing Protected Endpoints (Should redirect to login)...\n";
echo "--------------------------------\n";

$protectedEndpoints = [
    'Dashboard' => '/dashboard',
    'Cart' => '/cart',
    'Checkout' => '/checkout',
    'Orders' => '/orders',
    'Profile' => '/profile',
];

foreach ($protectedEndpoints as $name => $path) {
    $url = $baseUrl . $path;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Don't follow redirects
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Protected routes should return 302 (redirect) or 401/403
    $status = ($httpCode == 302 || $httpCode == 401 || $httpCode == 403) ? '✅' : '⚠️';
    $results[$name] = $httpCode;
    
    echo sprintf("%-25s %s HTTP %d\n", $name, $status, $httpCode);
}

echo "\n";
echo "🔒 Testing Admin Endpoints (Should be protected)...\n";
echo "--------------------------------\n";

$adminEndpoints = [
    'Admin Dashboard' => '/admin/dashboard',
    'Admin Products' => '/admin/products',
    'Admin Orders' => '/admin/orders',
    'Admin Categories' => '/admin/categories',
];

foreach ($adminEndpoints as $name => $path) {
    $url = $baseUrl . $path;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Admin routes should return 302 (redirect) or 401/403
    $status = ($httpCode == 302 || $httpCode == 401 || $httpCode == 403) ? '✅' : '⚠️';
    $results[$name] = $httpCode;
    
    echo sprintf("%-25s %s HTTP %d\n", $name, $status, $httpCode);
}

echo "\n";
echo "📊 SUMMARY\n";
echo "================================\n";

$total = count($results);
$success = count(array_filter($results, function($code) {
    return ($code >= 200 && $code < 400) || $code == 302 || $code == 401 || $code == 403;
}));

echo "Total Endpoints Tested: $total\n";
echo "Successful: $success\n";
echo "Failed: " . ($total - $success) . "\n";

if ($success == $total) {
    echo "\n✅ ALL CHECKS PASSED!\n";
} else {
    echo "\n⚠️ SOME CHECKS FAILED - Please review above\n";
}

echo "\n";
echo "💡 Note: This is a basic connectivity check.\n";
echo "   For complete testing, please use browser testing.\n";
echo "\n";
