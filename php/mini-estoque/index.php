<?php
session_start();

if (!isset($_SESSION["products"])) {
  $_SESSION["products"] = [];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $product_name = trim($_POST["product_name"] ?? "");
  $product_qtd = trim($_POST["product_qtd"] ?? "");

  $edit_product_id = $_POST["edit_product_id"] ?? "";
  $edit_product_name = trim($_POST["edit_product_name"] ?? "");
  $edit_product_qtd = trim($_POST["edit_product_qtd"] ?? "");

  $delete_product_id = trim($_POST["delete_product_id"] ?? "");

  if ($product_name !== "" && ctype_digit($product_qtd)) {
    $id = bin2hex(random_bytes(8));

    $_SESSION["products"][$id] = [
      "name" => $product_name,
      "qtd" => (int) $product_qtd
    ];

    header("Location: index.php");
    exit;
  }

  if (
    $edit_product_id !== "" &&
    isset($_SESSION["products"][$edit_product_id]) &&
    $edit_product_name !== "" &&
    ctype_digit($edit_product_qtd)
  ) {
    $_SESSION["products"][$edit_product_id] = [
      "name" => $edit_product_name,
      "qtd" => (int) $edit_product_qtd
    ];

    header("Location: index.php");
    exit;
  }

  if (
    $delete_product_id !== "" &&
    isset($_SESSION["products"][$delete_product_id])
  ) {
    unset($_SESSION["products"][$delete_product_id]);

    header("Location: index.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Estoque</title>

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      min-height: 100dvh;
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      color: #222;
      padding: 2rem;
    }

    main {
      width: 100%;
      max-width: 600px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    section {
      background: white;
      padding: 1rem;
      border-radius: 8px;
      border: 1px solid #ddd;
    }

    header {
      margin-bottom: 1rem;
    }

    h1 {
      font-size: 1.4rem;
    }

    form {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    input {
      padding: 0.6rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }

    button {
      padding: 0.6rem 0.9rem;
      border: none;
      border-radius: 6px;
      background: #222;
      color: white;
      cursor: pointer;
      font-size: 1rem;
    }

    button:hover {
      background: #444;
    }

    ul {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    li {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 0.5rem;
      padding: 0.7rem;
      background: #f8f8f8;
      border: 1px solid #ddd;
      border-radius: 6px;
    }

    .product-info {
      display: flex;
      gap: 1rem;
    }

    .hidden {
      display: none;
    }
  </style>
</head>

<body>

  <main>
    <section id="add-section">
      <header>
        <h1>Cadastre Produtos</h1>
      </header>

      <form method="post">
        <input type="text" name="product_name" id="product_name" placeholder="Nome do produto">
        <input type="number" name="product_qtd" id="product_qtd" placeholder="Quantidade">
        <button type="submit">Adicionar</button>
      </form>
    </section>

    <section id="list-section">
      <header>
        <h1>Listagem de produtos</h1>
      </header>

      <ul>
        <?php foreach ($_SESSION["products"] as $id => $product): ?>
          <li>
            <div class="product-info">
              <span><?= htmlspecialchars($product["name"], ENT_QUOTES) ?></span>
              <span>Qtd: <?= htmlspecialchars($product["qtd"], ENT_QUOTES) ?></span>
            </div>

            <div>
              <button
                type="button"
                class="edit-btn"
                data-id="<?= htmlspecialchars($id, ENT_QUOTES) ?>"
                data-name="<?= htmlspecialchars($product["name"], ENT_QUOTES) ?>"
                data-qtd="<?= htmlspecialchars($product["qtd"], ENT_QUOTES) ?>">
                Editar
              </button>

              <button
                type="button"
                class="delete-btn"
                data-id="<?= htmlspecialchars($id, ENT_QUOTES) ?>"
                data-name="<?= htmlspecialchars($product["name"], ENT_QUOTES) ?>">
                Apagar
              </button>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </section>

    <section id="edit-section" class="hidden">
      <header>
        <h1>Editar produto</h1>
      </header>

      <form method="post">
        <input type="hidden" name="edit_product_id" id="edit_product_id">
        <input type="text" name="edit_product_name" id="edit_product_name">
        <input type="number" name="edit_product_qtd" id="edit_product_qtd">
        <button type="submit">Salvar</button>
      </form>
    </section>
  </main>

  <script>
    const editBtns = document.querySelectorAll(".edit-btn");
    const editSection = document.getElementById("edit-section");

    const editProductId = document.getElementById("edit_product_id");
    const editProductName = document.getElementById("edit_product_name");
    const editProductQtd = document.getElementById("edit_product_qtd");

    const deleteBtn = document.querySelectorAll(".delete-btn");

    deleteBtn.forEach((btn) => {
      btn.addEventListener("click", () => {
        const form = document.createElement("form");
        form.method = "POST";

        const input = document.createElement("input");
        input.type = "hidden";
        input.name = "delete_product_id";
        input.value = btn.dataset.id;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
      })
    });

    editBtns.forEach((btn) => {
      btn.addEventListener("click", () => {
        editProductId.value = btn.dataset.id;
        editProductName.value = btn.dataset.name;
        editProductQtd.value = btn.dataset.qtd;

        editSection.classList.remove("hidden");
      });
    });
  </script>

</body>

</html>