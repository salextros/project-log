<?php
require_once __DIR__ . '/funciones.php';

logout();
redirect_to(admin_url('login.php'));