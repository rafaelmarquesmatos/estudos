<?php
// 26/05/2026;
session_start();

if (!isset($_SESSION["task"])) {
  $_SESSION["task"] = [];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  if (isset($_POST["add"]) && trim($_POST["add"]) !== "") {
    $_SESSION["task"][] = $_POST["add"];
  }

  if (isset($_POST["delete"])) {
    $index = $_POST["delete"];

    if (isset($_SESSION["task"][$index])) {
      unset($_SESSION["task"][$index]);
      $_SESSION["task"] = array_values($_SESSION["task"]);
    }
  }

  header("Location: index.php");
  exit;
}

$tasks = $_SESSION["task"];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todo List</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: 0;
      font-family: Arial;
    }

    html,
    body {
      width: 100dvw;
      height: 100dvh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: #f2f2f2;
    }

    .box {
      background-color: white;
      padding: 2rem;
      width: 700px;
      border-radius: 8px;
    }

    #actions {
      display: flex;
      gap: 0.5rem;
    }

    #button-add {
      padding: 1rem;
    }

    #button-delete {
      padding: 0.5rem;
    }

    input {
      padding: 0.5rem 0.7rem;
      flex: 1;
    }

    ul {
      margin-top: 2rem;
    }

    li {
      display: flex;
      justify-content: space-between;
      gap: 1rem;
      margin-bottom: 8px;
    }
  </style>
</head>

<body>
  <main class="box">

    <form method="POST">
      <div id="actions">
        <input type="text" name="add" placeholder="task">
        <button type="submit" id="button-add">Add</button>
      </div>
    </form>

    <ul>
      <?php foreach ($tasks as $index => $task): ?>
        <li>
          <?= htmlspecialchars($task) ?>

          <form method="POST">
            <input type="hidden" name="delete" value="<?= $index ?>">
            <button type="submit" id="button-delete">delete</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>

  </main>
</body>

</html>