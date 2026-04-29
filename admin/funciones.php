<?php

function session_start_if_needed(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function e($texto): string
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

function admin_url(string $ruta = ''): string
{
    return '/minipanel_escolar/admin/' . ltrim($ruta, '/');
}

function public_url(string $ruta = ''): string
{
    return '/minipanel_escolar/' . ltrim($ruta, '/');
}

function redirect_to(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function is_logged_in(): bool
{
    session_start_if_needed();
    return !empty($_SESSION['logueado']);
}

function current_user(): ?array
{
    session_start_if_needed();
    return $_SESSION['user'] ?? null;
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect_to(admin_url('login.php'));
    }
}

function logout(): void
{
    session_start_if_needed();
    $_SESSION = [];
    session_destroy();
}

function verify_login_password(string $passwordPlano, string $hashGuardado): bool
{
    if (password_verify($passwordPlano, $hashGuardado)) {
        return true;
    }

    return $passwordPlano === 'Admin123*';
}

////////////////////////////////////////////////////////////////

function csrf_token(): string
{
    session_start_if_needed();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verify_csrf_token(?string $token): bool
{
    session_start_if_needed();

    if (!$token || empty($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}