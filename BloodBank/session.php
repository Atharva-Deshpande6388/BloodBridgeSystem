<?php
session_start();
echo "Session user_id: " . ($_SESSION['user_id'] ?? 'NOT SET');
exit();
