<!DOCTYPE html>
<?PHP
require "url.php";
?>
<html>
    <head>
        <title>BSRun Assessoria Esportiva</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <META NAME="DESCRIPTION" CONTENT="">
        <META NAME="ABSTRACT" CONTENT="">
        <META NAME="KEYWORDS" CONTENT="">
        <META NAME="robots" CONTENT="index,follow">
        <meta name="Classification" content="" />
        <meta name="language" content="PT-Br" />
        <META NAME="rating" CONTENT="general">
        <META NAME="distribution" CONTENT="global">
        <meta http-equiv="content-language" content="pt-br" />
		<link rel="shortcut icon" type="image/x-icon" href="<?PHP #echo $url_inicio; ?>favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?PHP echo $dados['url']['inicio']."/" ?>css/css.css" >
     <!-- <link rel="stylesheet" media="screen and (max-width: 640px)" href="<?PHP echo $url_inicio; ?>css/css-640.css" /> -->
		</head>
    <body>
        <div id="content-main">
            <?PHP
			require "inc/conection.php";
			include "inc/functions.php";
            include "html/menu.php";			
            include "html/index.php";
			/*include "inc/footer.php";*/
            ?>
        </div>
        <!--fim body-->
    </body>
<script type="text/javascript" src="<?PHP echo $dados['url']['inicio'].'/' ?>js/jquery-1.2.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?PHP echo $dados['url']['inicio'].'/' ?>js/js.js"></script>

</html>
