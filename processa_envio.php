<?php

	require "./libs/PHPMailer/Exception.php";
	require "./libs/PHPMailer/OAuth.php";
	require "./libs/PHPMailer/PHPMailer.php";
	require "./libs/PHPMailer/POP3.php";
	require "./libs/PHPMailer/SMTP.php";

	use  PHPMailer\PHPMailer\PHPMailer ;
	use  PHPMailer\PHPMailer\SMTP ;
	use  PHPMailer\PHPMailer\Exception ;

	class Mensagem {

		private $destino = null;
		private $assunto = null;
		private $mensagem = null;
		public $status = array('codigo_status' => null, 'descricao_status' => '');

		public function __get($value){
			return $this->$value;
		}

		public function __set($value, $newValue){
			$this->$value = $newValue;

		}

		public function MensagemValida(){
			if (empty($this->destino) || empty($this->assunto) || empty($this->mensagem)) {
				return false;
			} else {
				return true;
			}
			
		}
	}

	$mensagem = new Mensagem();
	$mensagem->destino = $_POST['destino'];
	$mensagem->assunto = $_POST['assunto'];
	$mensagem->mensagem = $_POST['mensagem'];

	if(!$mensagem->MensagemValida()){
		echo "mensagem não é válida";
		header('Location: index.php?campo=erro');
	}

	$mail = new PHPMailer(true);

	try {
	    //Server settings
	    $mail->SMTPDebug = false;                      
	    $mail->isSMTP();                               
	    $mail->Host       = 'smtp.gmail.com';
	    $mail->SMTPAuth   = true;
	    $mail->Username   = EMAIL;
	    $mail->Password   = EMAIL_SENHA;
	    $mail->SMTPSecure = 'tls'; 
	    $mail->Port       = 587;                                    

	    //Recipients
	    $mail->setFrom(EMAIL, EMAIL_NOME);
	    $mail->addAddress($mensagem->destino);
	    
	    // Content
	    $mail->isHTML(true);
	    $mail->Subject = $mensagem->assunto;
	    $mail->Body    = $mensagem->mensagem;
	    $mail->AltBody = 'É necessário usar um client que suporte HMLT para ter acesso total ao conteúdo dessa mensagem';

	    $mail->send();

	    $mensagem->status['codigo_status'] = 1;
	    $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso';
	    
	}catch (Exception $e) {
		$mensagem->status['codigo_status'] = 2;
	    $mensagem->status['descricao_status'] = 'E-mail não enviado, tente novamnete. Detalhes do erro: ' . $mail->ErrorInfo;
	}


?>
<html>
<head>
	<meta charset="utf-8" />
    <title>App Mail Send</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    	
</head>
<body>

	<div class="container">  

			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
	</div>
	<div class="row">
		<div class="row-md-12">
			<? if ($mensagem->status['codigo_status'] == 1) {?>

				<div class="container">
					<h1 class="display-4 text-success">Sucesso</h1>
					<p><? $mensagem->status['descricao_status'] ?></p>
					<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
				</div>

			<? }?>

			<? if ($mensagem->status['codigo_status'] == 2) {?>

				<div class="container">
					<h1 class="display-4 text-danger">Falha!</h1>
					<p><? $mensagem->status['descricao_status'] ?></p>
					<a href="index.php" class="btn btn-danger btn-lg mt-5 text-white">Voltar</a>
				</div>

			<? }?>
		</div>
	</div>
	
</body>
</html>