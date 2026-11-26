
<?php
session_start();
require_once "ligacao.php";

// Processar edição ou eliminação
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Só utilizadores autenticados podem alterar anúncios
    if (!isset($_SESSION["id_utilizador"])) {
        header("Location: login.php");
        exit;
    }

    $id_utilizador = $_SESSION["id_utilizador"];
    $id_servico = filter_input(
        INPUT_POST,
        "id_servico",
        FILTER_VALIDATE_INT
    );

    $acao = $_POST["acao"] ?? "";

    if ($id_servico) {

        // EDITAR ANÚNCIO
        if ($acao === "editar") {

            $titulo = trim($_POST["titulo"] ?? "");
            $descricao = trim($_POST["descricao"] ?? "");
            $categoria = trim($_POST["categoria"] ?? "");
            $localizacao = trim($_POST["localizacao"] ?? "");
            $data_servico = $_POST["data_servico"] ?? "";

            if (
                $titulo !== "" &&
                $descricao !== "" &&
                $categoria !== "" &&
                $localizacao !== ""
            ) {

                $sql = "UPDATE servicos
                        SET titulo = :titulo,
                            descricao = :descricao,
                            categoria = :categoria,
                            localizacao = :localizacao,
                            data_servico = :data_servico
                        WHERE id_servico = :id_servico
                        AND id_utilizador = :id_utilizador";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    ":titulo" => $titulo,
                    ":descricao" => $descricao,
                    ":categoria" => $categoria,
                    ":localizacao" => $localizacao,
                    ":data_servico" => $data_servico !== ""
                        ? $data_servico
                        : null,
                    ":id_servico" => $id_servico,
                    ":id_utilizador" => $id_utilizador
                ]);
            }
        }

        // APAGAR ANÚNCIO
        if ($acao === "apagar") {

            $sql = "DELETE FROM servicos
                    WHERE id_servico = :id_servico
                    AND id_utilizador = :id_utilizador";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ":id_servico" => $id_servico,
                ":id_utilizador" => $id_utilizador
            ]);
        }
    }

    // Atualizar a página depois da operação
    header("Location: servicos.php");
    exit;
}

// Consultar anúncios
$sql = "SELECT * FROM servicos
        ORDER BY data_publicacao DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>ServiGo</title>
  <link rel="stylesheet" href="style.css"> <!--para ligar 
  o index ao style (pagina onde está salvo o css)-->
</head>

<body>
  <header>
  <a class="logo" href="index.php">
  <img src="images/logo.png" alt="Logo ServiGo">
  </a>

  <form>
    <input type="text" placeholder="Pesquisar serviços">
  </form>

  <div>
    <a class="botao" href="servicos.html">Serviços</a>
    <a class="botao" href="login.php">Login</a>
    <a class="botao" href="publicar_anuncio.php">Publicar anúncio</a>
  </div>
</header>

  <main>
    <section>
      <h1>Onde a ServiGo está disponível?</h1>
    </section>

    <main class="servicos">
  <h2>Serviços por localização</h2>


<h2>Serviços disponíveis</h2>

<?php if (count($servicos) > 0): ?>

    <?php foreach ($servicos as $servico): ?>

        <article class="servico">
            <h3>
                <?php echo htmlspecialchars($servico["titulo"]); ?>
            </h3>

            <p>
                <?php echo htmlspecialchars($servico["descricao"]); ?>
            </p>

            <p>
                Categoria:
                <?php echo htmlspecialchars($servico["categoria"]); ?>
            </p>

            <p>
                Localização:
                <?php echo htmlspecialchars($servico["localizacao"]); ?>
            </p>


<?php if (
    isset($_SESSION["id_utilizador"]) &&
    $_SESSION["id_utilizador"] == $servico["id_utilizador"]
): ?>

    <details>
        <summary>Editar anúncio</summary>

        <form method="POST" action="servicos.php">

            <input type="hidden" name="acao" value="editar">

            <input
                type="hidden"
                name="id_servico"
                value="<?php echo $servico["id_servico"]; ?>"
            >

            <label>Título</label>
            <input
                type="text"
                name="titulo"
                value="<?php echo htmlspecialchars($servico["titulo"]); ?>"
                required
            >

            <label>Descrição</label>
            <textarea name="descricao" required><?php
                echo htmlspecialchars($servico["descricao"]);
            ?></textarea>

            <label>Categoria</label>
            <select name="categoria" required>
                <option value="jardinagem">Jardinagem</option>
                <option value="limpeza">Limpeza</option>
                <option value="reparacoes">Reparações</option>
                <option value="pintura">Pintura</option>
            </select>

            <label>Localização</label>
            <input
                type="text"
                name="localizacao"
                value="<?php echo htmlspecialchars($servico["localizacao"]); ?>"
                required
            >

            <label>Data do serviço</label>
            <input
                type="date"
                name="data_servico"
                value="<?php echo htmlspecialchars($servico["data_servico"] ?? ""); ?>"
            >

            <button type="submit">Guardar alterações</button>

        </form>
    </details>

    <form
        method="POST"
        action="servicos.php"
        onsubmit="return confirm('Tens a certeza que queres apagar este anúncio?');"
    >
        <input type="hidden" name="acao" value="apagar">

        <input
            type="hidden"
            name="id_servico"
            value="<?php echo $servico["id_servico"]; ?>"
        >

        <button type="submit">Apagar anúncio</button>
    </form>

<?php endif; ?>

        </article>

    <?php endforeach; ?>

<?php else: ?>

    <p>Não existem serviços publicados.</p>

<?php endif; ?>


  <p>Escolhe uma localidade para ver no mapa:</p>

  <ul>
    <li><a target="mapa" href="https://www.openstreetmap.org/export/embed.html?bbox=-8.65%2C41.10%2C-8.57%2C41.15&layer=mapnik&marker=41.1239%2C-8.6118">Vila Nova de Gaia</a></li>
    <li><a target="mapa" href="https://www.openstreetmap.org/export/embed.html?bbox=-8.66%2C41.13%2C-8.58%2C41.17&layer=mapnik&marker=41.1496%2C-8.6109">Porto</a></li>
    <li><a target="mapa" href="https://www.openstreetmap.org/export/embed.html?bbox=-8.73%2C41.16%2C-8.65%2C41.20&layer=mapnik&marker=41.1821%2C-8.6891">Matosinhos</a></li>
    <li><a target="mapa" href="https://www.openstreetmap.org/export/embed.html?bbox=-8.59%2C40.90%2C-8.51%2C40.95&layer=mapnik&marker=40.9267%2C-8.5477">Santa Maria da Feira</a></li>
  </ul>

  <iframe
    name="mapa"
    class="mapa"
    title="Mapa de serviços por localização"
    src="https://www.openstreetmap.org/export/embed.html?bbox=-8.65%2C41.10%2C-8.57%2C41.15&layer=mapnik&marker=41.1239%2C-8.6118">
  </iframe>
</main>

  <footer>
    <p>2026 ServiGo</p>
    <a href="">Sobre nós</a>
  </footer>
</body>
</html>