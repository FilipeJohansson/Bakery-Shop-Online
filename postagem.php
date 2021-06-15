<?php
	include("conexao.php");

	$login_cookie = $_COOKIE['bd7c7af80d955889417bc6598183d301L'];

	if(!isset($login_cookie) || isset($_POST['sair'])){
		setcookie("bd7c7af80d955889417bc6598183d301L",'');
		header("Location: login.php");
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<div class="row">
			<br>
			<div class="col-md-1">
				
			</div>
			<div class="col-md-10">
				<center>
					<?php
						if(isset($_POST['enviar'])) {
							if(!isset($login_cookie)){
								setcookie("bd7c7af80d955889417bc6598183d301L",'');
								header("Location: login.php");
							} else {
								if ($_FILES["file"]["error"] > 0) {
									echo "Nenhuma imagem selecionada.";
								} else {
									$n = rand(0, 10000);
									$img = $n."_".$_FILES["file"]["name"];

									move_uploaded_file($_FILES["file"]["tmp_name"], "imagens/Galeria/".$img);

									$query = $pdo->prepare("INSERT INTO imagens (nome,first) VALUES ('$img','0')");
									$data = $query->execute();
									
									if($data) {
										echo "Upload com sucesso.";
									} else {
										echo "Erro.";
									}
								}
							}
						}

						if(isset($_POST['foto']) && isset($_POST['excluir'])) {
							if(!isset($login_cookie)){
								setcookie("bd7c7af80d955889417bc6598183d301L",'');
								header("Location: login.php");
							} else {
								$query = $pdo->prepare("DELETE FROM `imagens` WHERE `imagens`.`nome` = 	'".$_POST['foto']."'");
								$data = $query->execute();

								if($data) {
									echo "Foto ".$_POST['foto']." excluida.";
								} else {
									echo "Erro.";
								}
							}
						}

						if (isset($_POST['foto']) && isset($_POST['enviarPrincipal'])) {
							if(!isset($login_cookie)){
								setcookie("bd7c7af80d955889417bc6598183d301L",'');
								header("Location: login.php");
							} else {
							    $query = $pdo->prepare("UPDATE `imagens` SET `first` = '0'");
							    $data = $query->execute();

							    $query = $pdo->prepare("UPDATE `imagens` SET `first` = '1' WHERE `imagens`.`nome` = '".$_POST['foto']."'");
							    $data = $query->execute();

								if($data) {
									echo "Imagem principal atualizada para: " . $_POST['foto'];
								} else {
									echo "Erro.";
								}
							}
						}
					?>
				</center>
			</div>
			<div class="col-md-1">
				<form method="POST">
					<button class='btn btn-default' style='cursor: pointer;' name='sair'>
						<span class='glyphicon glyphicon-off'></span> Sair
					</button>
				</form>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="flex-row row">
			<form method="POST" enctype="multipart/form-data">
				<center>
					<label for="file-input">
						<input class="btn btn-default" type="file" name="file">
					</label>
					<input class="btn btn-submit" type="submit" name="enviar" value="Enviar foto">
				</center>
			</form>
			<form method="POST">
				<center>
					<div class="row">
						<input class='btn btn-submit' type='submit' name='enviarPrincipal' value='Atualizar foto principal'>
						<input class="btn btn-submit" type="submit" name='excluir' value="Excluir fotos">
					</div>
				</center>
				<br>
				<?php
					$fotoPrincipal = $pdo->prepare("SELECT * FROM imagens WHERE first=1");
					$fotoPrincipal->execute();

					while($principal = $fotoPrincipal->fetch(PDO::FETCH_ASSOC)) {
						echo "
						<div class='col-xs-6 col-sm-4 col-lg-3'>
							<div class='thumbnail'>
								<center><p><b>Imagem principal:</b></p></center>
								<img src='./imagens/Galeria/".$principal['nome']."'>
							</div>
						</div>
						";
					}

					$fotos = $pdo->prepare("SELECT * FROM imagens WHERE first=0 ORDER BY ID DESC");
					$fotos->execute();

					while($foto = $fotos->fetch(PDO::FETCH_ASSOC)) {
						echo "
						<div class='col-xs-6 col-sm-4 col-lg-3'>
							<div class='thumbnail'>
								<center><input type='radio' name='foto' value='".$foto['nome']."'></center>
								<img src='./imagens/Galeria/".$foto['nome']."'>
								
							</div>
						</div>";
					}
				?>
			</form>
		</div>
	</div>
</body>
</html>