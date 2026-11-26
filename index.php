<?php
require_once 'ligacao.php';

// consulta a tabela de servicos
$stmt_servicos = $pdo->query("SELECT id_servico, titulo, descricao, categoria, localizacao FROM servicos WHERE estado = 'aberto'");
$servicos = $stmt_servicos->fetchAll(PDO::FETCH_ASSOC);

// consulta a tabela de profissionais
$stmt_perfis = $pdo->query("SELECT id_perfil, descricao, competencias FROM perfis_profissionais");
$perfis_profissionais = $stmt_perfis->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8">
  <title>ServiGo</title>
  <link rel="stylesheet" href="style.css">
  <link rel="icon" type="image/png" href="images/tab.png">
  <link rel="stylesheet" href="anunciosStyle.css">
</head>

<body>
  <header>
  <a class="logo" href="index.php">
  <img src="images/logo.png" alt="Logo ServiGo">
  </a>

  <div class="acoes">
    <a class="botao" href="servicos.php">Serviços</a>
    <a class="botao" href="login.php">Login</a>
    <a class="botao" href="publicar_anuncio.php">Publicar anúncio</a>
    <a class="botao" href="perfil.php" >Perfil</a>
  </div>
</header>

    <main>
    <section class="home">
      <h1 class="hero">Encontra ajuda e serviços de confiança para tarefas de casa</h1>
      <form class="pesquisa-hero">
        <input type="text" placeholder="O que precisas?">
        <button type="submit"> Pesquisar</button>
      </form>

      <nav class="acoes acoes-hero">
        <a class="botao" href="">Montagem</a>
        <a class="botao" href="">Instalações</a>
        <a class="botao" href="">Reparações</a>
        <a class="botao" href="" >Em destaque</a>
      </nav>
    </section>

    <section class="servicos-destaque">
      <h2>Precisa de um profissional?</h2>
      <p>Encontra especialistas!</p>

      <div class="grelha">
       <?php foreach ($perfis_profissionais as $profissional): ?>
          <div class="cartao">
            <h3> <?= $profissional['competencias'] ?>   </h3>
            <p>  <?=$profissional['descricao']?>. </p>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

<section class="servicos-destaque">
      <h2>És profissional?</h2>
      <p>Encontra novos trabalhos e apresenta as tuas propostas!</p>

      <div class="grelha">
        <?php foreach ($servicos as $servico): ?>
          <div class="cartao">
            <h3> <?= $servico['titulo'] ?>   </h3>
            <p>  <?=$servico['descricao']?>. </p>
          </div>
        <?php endforeach; ?>

      </div>
    </section>


  </main>

  <footer>
    <p>2026 ServiGo</p>
    <a href="sobreNos.html">Sobre nós</a>
  </footer>
</body>
</html>