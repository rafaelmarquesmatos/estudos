<?php
// 26/05/2026;
session_start();

$mostrarEdit = false;
$currentEditIndex = "";

if (!isset($_SESSION["contatos"])) {
  $_SESSION["contatos"] = [];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nome = trim($_POST["nome"] ?? "");
  $numero = trim($_POST["numero"] ?? "");
  $delete = trim($_POST["delete"] ?? "");
  $mostrarEditPost = trim($_POST["mostrar_edit"] ?? "");
  $index = trim($_POST["index"] ?? "");
  $novoNome = trim($_POST["novo_nome"] ?? "");
  $novoNumero = trim($_POST["novo_numero"] ?? "");

  // Cadastro
  if ($nome !== "" && $numero !== "") {
    $_SESSION["contatos"][] = [
      "nome" => $nome,
      "numero" => $numero
    ];

    header("Location: index.php");
    exit;
  }

  // Apagar
  if ($delete !== "") {
    if (isset($_SESSION["contatos"][$delete])) {
      unset($_SESSION["contatos"][$delete]);

      $_SESSION["contatos"] = array_values($_SESSION["contatos"]);
    }

    header("Location: index.php");
    exit;
  }

  // Mostrar
  if ($mostrarEditPost !== "") {
    if (isset($_SESSION["contatos"][$mostrarEditPost])) {
      $mostrarEdit = true;
      $currentEditIndex = $mostrarEditPost;
    }
  }

  // Editar
  if ($novoNome !== "" && $novoNumero !== "" && $index !== "") {
    $_SESSION["contatos"][$index] = [
      "nome" => $novoNome,
      "numero" => $novoNumero
    ];

    $mostrarEdit = false;
    $currentEditIndex = "";

    header("Location: index.php");
    exit;
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html,
    body {
      width: 100dvw;
      height: 100dvh;
    }
  </style>
</head>

<body>
  <main>
    <form method="POST">
      <h1>Cadastre um contato</h1>

      <label for="nome">Nome</label>
      <input type="text" name="nome" id="nome">

      <label for="numero">Numero</label>
      <input type="tel" name="numero" id="numero">
      <button type="submit">Cadastrar</button>
    </form>

    <h1>Lista de contatos</h1>

    <ul>
      <?php foreach ($_SESSION["contatos"] as $index => $contato): ?>
        <li>
          <span><?= htmlspecialchars($contato["nome"]) ?></span>
          <span><?= htmlspecialchars($contato["numero"]) ?></span>
          <form method="POST">
            <input type="hidden" name="delete" value="<?= $index ?>">
            <button type="submit">delete</button>
          </form>
          <form method="POST">
            <input type="hidden" name="mostrar_edit" value="<?= $index ?>">
            <button type="submit">edit</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>


    <?php if ($mostrarEdit): ?>
      <form method="POST">
        <h1>Editar contato</h1>

        <label for="novo_nome">Novo nome</label>
        <input type="text" name="novo_nome" id="novo_nome" value="<?= htmlspecialchars($_SESSION["contatos"][$currentEditIndex]["nome"]) ?>">

        <label for="novo_numero">Novo numero</label>
        <input type="tel" name="novo_numero" id="novo_numero" value="<?= htmlspecialchars($_SESSION["contatos"][$currentEditIndex]["numero"]) ?>">
        <input type="hidden" name="index" value="<?= $currentEditIndex ?>">
        <button type="submit">Salvar</button>
      </form>
    <?php endif; ?>
  </main>
</body>

</html>