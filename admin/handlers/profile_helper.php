<?php
/**
 * Get the user's profile image path with fallback to default avatar.
 *
 * @param string|null $profileImage Filename stored in session or database
 * @return string Relative path to display in HTML
 */
function getProfileImage(?string $profileImage): string {
    // Base path from 'dist/admin/includes/sidebar.php' to avatars folder
    $basePath = '../../assets/images/user/';
    $default  = $basePath . 'profile.png';

    // If no image provided, return default
    if (empty($profileImage)) {
        return $default;
    }

    // Construct the full server path to check if the file exists
    $serverPath = __DIR__ . '/../../assets/images/user/' . $profileImage;

    if (file_exists($serverPath)) {
        return $basePath . $profileImage;
    }

    // Fallback to default if file not found
    return $default;
}

// Usage in sidebar.php
$profileImage = getProfileImage($_SESSION['user']['profile_image'] ?? null);
