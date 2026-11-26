
<?php
session_start();
require_once "ligacao.php";

// Confirmar se o utilizador tem sessão iniciada
if (!isset($_SESSION["id_utilizador"])) {
    header("Location: login.php");
    exit;
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titulo = trim($_POST["titulo"] ?? "");
    $descricao = trim($_POST["descricao"] ?? "");
    $categoria = trim($_POST["categoria"] ?? "");
    $localizacao = trim($_POST["localizacao"] ?? "");
    $data_servico = $_POST["data_servico"] ?? "";

    if (
        $titulo === "" ||
        $descricao === "" ||
        $categoria === "" ||
        $localizacao === ""
    ) {
        $mensagem = "Preenche todos os campos obrigatórios.";
    } else {
        try {
            $sql = "INSERT INTO servicos
                    (id_utilizador, titulo, descricao, categoria,
                     localizacao, data_servico)
                    VALUES
                    (:id_utilizador, :titulo, :descricao, :categoria,
                     :localizacao, :data_servico)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ":id_utilizador" => $_SESSION["id_utilizador"],
                ":titulo" => $titulo,
                ":descricao" => $descricao,
                ":categoria" => $categoria,
                ":localizacao" => $localizacao,
                ":data_servico" => $data_servico !== ""
                    ? $data_servico
                    : null
            ]);

            header("Location: servicos.php");
            exit;

        } catch (PDOException $e) {
            $mensagem = "Erro ao publicar o anúncio.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Publicar anúncio - ServiGo</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <a class="logo" href="index.php">
        <img src="images/logo.png" alt="Logo ServiGo">
    </a>

    <nav>
        <a class="botao" href="index.php">Início</a>
        <a class="botao" href="servicos.php">Serviços</a>
        <a class="botao" href="login.php">Login</a>
    </nav>
</header>

<main>
    <section>
        <h1>Publicar anúncio</h1>
        <p>
            Preenche os dados do serviço que precisas
            e encontra profissionais disponíveis.
        </p>
    </section>

    <section>
        <?php if ($mensagem !== ""): ?>
            <p><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>

        <form method="POST" action="publicar_anuncio.php">

            <label for="titulo">Título do serviço</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" required></textarea>

            <label for="categoria">Categoria</label>
            <select id="categoria" name="categoria" required>
                <option value="">Seleciona uma categoria</option>
                <option value="jardinagem">Jardinagem</option>
                <option value="limpeza">Limpeza</option>
                <option value="reparacoes">Reparações</option>
                <option value="pintura">Pintura</option>
            </select>

            <label for="localizacao">Localização</label>
            <input type="text" id="localizacao" name="localizacao" required>

            <label for="data_servico">Data do serviço (opcional)</label>
            <input type="date" id="data_servico" name="data_servico">

            <button type="submit">Publicar anúncio</button>

        </form>
    </section>
</main>

<footer>
    <p>© 2026 ServiGo</p>
</footer>

</body>
</html>
