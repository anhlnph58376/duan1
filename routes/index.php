<?php

$action = $_GET['action'] ?? '/';

$homeController = new HomeController;
match ($action) {
    '/'         => $homeController->index(),
};