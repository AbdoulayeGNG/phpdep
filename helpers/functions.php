<?php

function getRoleBadgeClass($role) {
    switch($role) {
        case 'admin': return 'danger';
        case 'electeur': return 'primary';
        case 'agent': return 'success';
        case 'observateur': return 'info';
        default: return 'secondary';
    }
}

function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

function sanitizeHtml($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}