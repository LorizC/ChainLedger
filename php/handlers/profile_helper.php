<?php
/**
 * Get the user's profile image path with fallback to default avatar.
 *
 * @param string|null $profileImage Relative path from session or database
 * @return string Final image path to display
 */
function getProfileImage(?string $profileImage): string {
    $basePath = '../../images/avatars/';
    $default  = $basePath . 'profile.png';

    if (empty($profileImage)) {
        return $default;
    }

    // Remove accidental '../../' or leading slashes
    $cleanPath = str_replace(['../', './'], '', $profileImage);
    $fullPath = __DIR__ . '/../../' . $cleanPath;

    if (!file_exists($fullPath)) {
        return $default;
    }

    // ✅ Return a valid relative path from sidebar’s location
    if (!str_starts_with($cleanPath, 'images/')) {
        return '../../' . $cleanPath;
    }

    return '../../' . $cleanPath;
}
