<?php
require_once './component/Bootstrap.php';

$controller = new FrontController();
$params = $controller->handle($_SERVER['SCRIPT_NAME'] ?? '/index.php');
extract($params);

if (isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME'] === '/calendar-action.php') {
    require './calendar-action.php';
    exit;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo View::escape($pageTitle); ?></title>
  <link rel="stylesheet" href="/style.css" />
  <link rel="stylesheet" href="/responsive-fixes.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" />
  <?php
    $css = substr($_SERVER['SCRIPT_NAME'], 0, -3) . 'css';
    echo '<link rel="stylesheet" href="' . View::escape($css) . '" />';
  ?>
</head>
<body>
  <?php
    switch ($_SERVER['SCRIPT_NAME']) {
      case '/login.php':
      case '/register.php':
        break;
      default:
        require './component/nav.php';
    }
    require '.' . $_SERVER['SCRIPT_NAME'];
  ?>
</body>
