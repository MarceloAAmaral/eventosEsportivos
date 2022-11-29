<?PHP
//global $dados;
$dados = array();
$protocolo = "http://";
$dominio= $_SERVER['HTTP_HOST'];
$cliente = "bs";
$request=$_SERVER['REQUEST_URI'];
$dados['table']['cliente']= $cliente;
$dados['url']['request'] = $request;
$dados['url']['dominio'] = $dominio;
$dados['url']['inicio'] = $protocolo.$dominio.'/'.$cliente;
$dados['url']['href'] = $dados['url']['inicio'];
$dados['post'] = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$get = filter_input_array(INPUT_GET, FILTER_DEFAULT);

