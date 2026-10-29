<?php
session_start();

require_once 'ligacao.php';

$erroLogin = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {

        $erroLogin = "Preenche todos os campos.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $erroLogin = "Introduz um endereço de e-mail válido.";

    } else {

    //Procura o utilizador na base de dados com base no e-mail fornecido
        $sql = "SELECT id_utilizador, nome, email, palavra_passe
                FROM utilizadores
                WHERE email = :email";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(["email" => $email]);

        $utilizadorEncontrado = $stmt->fetch(PDO::FETCH_ASSOC);

        //Verifica a palavra-passe e cria sessao
        if (
            $utilizadorEncontrado &&
            password_verify(
                $password,
                $utilizadorEncontrado["palavra_passe"]
            )
        ) {

            session_regenerate_id(true);

            $_SESSION["id_utilizador"] =
                $utilizadorEncontrado["id_utilizador"];

            $_SESSION["nome"] =
                $utilizadorEncontrado["nome"];
 
            header("Location: perfil.php");
            exit;

        } else {

            $erroLogin = "E-mail ou palavra-passe incorretos.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sessão | ServiGo</title>

    <link rel="stylesheet" href="loginstyle.css">
</head>

<body>

    <main class="login-caixa">

        <a class="logo" href="index.html">
            <img src="images/logo.png" alt="ServiGo">
        </a>

        <h1>Damos-lhe as boas-vindas</h1>

        <?php if ($erroLogin !== ""): ?>
            <p class="erro-login">
                <?= htmlspecialchars($erroLogin, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>

        <form id="formLogin" action="login.php" method="POST">

            <div class="campo" id="campoEmail">

                <label for="email">Endereço de e-mail*</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Introduza o seu e-mail"
                    autocomplete="email"
                    value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    required
                >

            </div>

            <div class="campo" id="campoPassword">

                <label for="password">Palavra-passe*</label>

                <div class="campo-password">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Introduza uma palavra-passe"
                        autocomplete="current-password"
                        required
                    >

                </div>

            </div>

            <a class="link-ajuda" href="#">
                Precisa de ajuda com a palavra-passe?
            </a>

            <button type="submit" class="botao-principal">
                Iniciar sessão
            </button>

        </form>

        <div class="separador">
            <span>OU</span>
        </div>

        <a class="botao-secundario" href="registo.php">
            Em alternativa, inscreva-se
        </a>

    </main>

    <a class="botao-ajuda" href="#">
        ? Ajuda
    </a>

</body>

</html>
