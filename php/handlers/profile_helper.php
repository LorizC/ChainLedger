<?php
/**
 * Get the user's profile image path with fallback to default avatar.
 *
 * @param string|null $profileImage Relative path from session or database
 * @return string Final image path to display
 */
function getProfileImage(?string $profileImage): string {
    // If image not set, return default immediately
    if (empty($profileImage)) {
        return '../../images/avatars/profile.png';
    }

    // Normalize path to check if file exists
    $profilePath = __DIR__ . '/../../' . ltrim($profileImage, '/');

    // If file does not exist → return default
    if (!file_exists($profilePath)) {
        return '../../images/avatars/profile.png';
    }

    return $profileImage;
}
