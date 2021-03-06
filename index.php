<?php
	/********************************************************************/	
	/*   Importaçao dos arquivos necessarios para a exeucuçao do script 
             e definicao das configuracoes iniciais                         */

	include 'Item.php';
	include 'Feed.php';
	include 'RSS2.php';
	require_once('conexao.php');
	date_default_timezone_set('UTC');
	use \FeedWriter\RSS2;

	/********************************************************************/	


	/********************************************************************/	
	/* Parametros configuraveis. Voce DEVE alterar esses parametros de 
           acordo com suas configuracoes.                                   */
	/* ======          VOCE DEVE ALTERAR ESSES VALORES           ====== */
	
	$nomeDoSite       = "NOME_DO_SEU_SITE";
	$linkDoSite       = "LINK_DO_SEU_SITE"; 
	$descricaoDoSite  = "DESCRICAO_DO_SEU_SITE";

	/* As variaveis abaixo estao relaciondas com o seu banco de dados 
           o valor delas deve estar condizente com suas tabelas            */

	$titulo           = "titulo"; //nome da coluna que armazena o titulo da noticia
	$corpoDaNoticia   = "corpo_noticia"; //nome da coluna que armazena o corpo da noticia
	$dataDePublicacao = "data_publicacao" ; //nome da coluna que armazena a data de publicacao da noticia
	$autor            = "autor";  //nome da coluna que armazena o nome do autor
	$link             = "link"; //nome da coluna que aramazena o link da noticia
	
	/********************************************************************/

	$feed = new RSS2;	
	$feed->setTitle($nomeDoSite);
	$feed->setLink($linkDoSite);
	$feed->setDescription($descricaoDoSite);
	$feed->setChannelElement('language', 'pt-BR');
	$feed->addGenerator();
	
	$db = Conexao::getInstance();
	$tabela = Conexao::getTabela('TB_NOTICIAS');

	/* É importante atentar a esse comando */
	$consulta = $db->query("SELECT * FROM `$tabela` ORDER BY $tabela.$dataDePublicacao DESC LIMIT 0, 20");

	while($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		$newItem = $feed->createNewItem();
		$newItem->setTitle($linha[$titulo]);
		$newItem->setLink($linha[$link]);
		$newItem->setDescription($linha[$corpoDaNoticia]);
		$newItem->setDate($linha[$dataDePublicacao]);
		$newItem->setAuthor($linha[$autor], "autor@autor.com.br");
		$feed->addItem($newItem);
	}

	$myFeed = $feed->generateFeed();
	echo $myFeed;
?>
