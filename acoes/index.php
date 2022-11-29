<?PHP 
echo "<!DOCTYPE html>";
header('Content-type: text/html; charset=UTF-8');
echo "
<head>
  <link rel='stylesheet' type='text/css' href='css/loading.css'>
</head>
<head>";
$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
print_r($post);
if(!empty($post)){
	set_time_limit(0);
	require "../inc/functions.php";
	$aviso = aviso('db',"Por gentileza,<br> aguarde...");
	echo $aviso; 
 	include "../inc/conection.php";
	include "../url.php";
	include "../inc/classes/classe_db.php";
		
	$retornoId = array();	
	$form_name = $post['form'];
	if($dados_form = consultaDadosTabela('form','',array('form'=>$form_name),'')){
	$tabelas = 	explode(',',$dados_form[0]['tabela_form']);
	}
	$Db = new Db;			
	$Db->Form = $form_name;
	$Db->Campos = array();
			
	foreach($tabelas as $tabela){
		$Db->Tabela = $tabela;
		foreach($post['filtro'] as $param => $dado){
			$Db->Campos[$tabela][$param] = $dado;
		}
		if(count($retornoId)>0){
			foreach($retornoId as $campo => $dado){
				$Db->Campos[$tabela][$campo]=$dado;
			}
		}
		if(isset($post[$tabela])){			
			foreach($post[$tabela] as $ordem => $dados){
				foreach($dados as $id => $campos){
					if($id =='0'){
						$id = 'null';
						$Db->Acao = 'incluir';			
					}else{
						$Db->Id = $id;
						$Db->Acao = 'editar';
					}
					foreach($campos as $campo => $dado){
						$Db->Campos[$tabela][$ordem][$campo]=$dado;
					}
				}	
			}
		$retornoId[$tabela.'_id'] = $idHref = $Db->grava_dados();					
		}else{
		$retornoId[$tabela.'_id'] =	$Db->grava_{$this->Tabela}();
		}		
		
		if(isset($idHref) AND $tabela == 'inscritos'){			
			$post['href'] = $post['href'].$idHref;
		}
	}	
		
	header("Location:{$post['href']}"); 				
				
	
	/*
	$form_name = $dados['form_name'];
if ($form_name === 'inscrever'){
	
	$classDb->Tabela = "atleta";
	$colunas = consultaColumns($classDb->Tabela);
	$classDb->Dados = $dados;
	if($id = $classDb->Insert()){
		$dados[$classDb->Tabela.'_id']=$id;
		$classDb->Tabela = "inscritos";
		
		
	$classDb->Dados = $dados;
	if($id = $classDb->Insert()){
		require "classes/classe_email.php";
			$envia_email = new Email;
			$assunto = "Inscricao de {$dados['nome']}, id $id";			
			$texto = "Nome:".$dados['nome']."<br>";
			$texto .= "Inscricao id: $id<br>";
			if($envia_email -> smtp_envio('contato@maissport.com.br',$assunto,$texto)){
				header("Location:{$url_inicio}eventos/inscricao/pagamento/$id");
			}
	} 
}
}
if($form_name === 'finalizar')){
	if($dados['inscritos_id']){
	$classDb = new Db;
	$classDb->Tabela = "movimento";	
	$arr = consultaDadosTabela('evento,atleta,perc,inscricao,inscritos','',array('inscritos_id'=>$dados['inscritos_id']),'');	
	foreach($arr[0] as $key => $dado){
		if(!isset($dados[$key])){
		$dados[$key]=$dado;
		}
	}
	
	
	$dados['movimento_origem_titulo'] = consultaDadoCampo('movimento_origem','movimento_origem_titulo',array('movimento_origem_id'=>$dados['movimento_origem_id']));
	$classDb->Dados = $dados;
if($arr = consultaDadosTabela($classDb->Tabela,'movimento_id',array('inscritos_id'=>$dados['inscritos_id']),'')){
	$classDb->Id = $arr[0]['movimento_id'];
	$mov_id = $classDb->Update();
	}else{
		$mov_id = $classDb->Insert();	
	};
	if($mov_id){
		$dados['movimento_id'] =$mov_id;
		if($dados['movimento_origem_id'] === '2' OR $dados['movimento_origem_id'] === '3'){
			 $dados['bin']= substr($dados['n_cartao'],0,6);
			  include 'pagseguro.php';
		}else{
			require "classes/classe_email.php";
			$envia_email = new Email;
			$assunto = "Inscricao de {$dados['nome']}, id {$dados['inscritos_id']}";
			$texto = "Evento:".$dados['evento_titulo']."<br>";
			$texto .= "Nome:".$dados['nome']."<br>";
			$texto .= "Id:".$dados['inscritos_id']."<br>";
			if($envia_email -> smtp_envio('contato@maissport.com.br',$assunto,$texto)){
			 header("Location:{$url_inicio}eventos/inscricao/confirmacao/{$dados['inscritos_id']}");
			}
		}
	}
	} 
}
if($form_name === 'enviar'){
	require "classes/classe_email.php";
	$envia_email = new Email;
	$assunto = "{$dados['assunto']}";
	$texto = "Nome:".$dados['nome']."<br>";
	$texto .= "Email:".$dados['email']."<br>";
	$texto .= "Telefone:".$dados['telefone']."<br>";
	$texto .= "Mensagem:".$dados['mensagem']."<br>";
		if($envia_email -> smtp_envio('contato@maissport.com.br',$assunto,$texto)){
		 header("Location:{$url_inicio}");
		}
		*/
}


