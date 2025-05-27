<?php
function formatDate($dateString) {
    if (!$dateString) return '—';
    try {
        $date = DateTime::createFromFormat('Y-m-d H:i:s.u', $dateString);
        if (!$date) {
            $date = new DateTime($dateString); // Fallback for different formats
        }
        return $date->format('Y-m-d');
    } catch (Exception $e) {
        return '—';
    }
}
?>