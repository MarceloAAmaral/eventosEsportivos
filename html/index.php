<?PHP
$layout = "";
include "inc/classes/classe_estrututa_main.php";
$estrutura_main = new estruturaLayoutMain;

if (!is_null($get)) {
	
	foreach($get as $key => $dado){		
		if(!empty($dado)){			
			if($key=='filtros'){				
				$dados['url'][$key] = explode('/',$dado);			
			}else{
				$dados['url'][$key] = $dado;		
			}
		}
	}
} 
/*
elseif($key=='sec'){
	# se parametro URL conter secao
	//$estrutura_main->setSecao(array('sec'=>$dado));
}
*/
/*else {
        $dados['url']['estrutura']['sec'] = "home" ;		
}*/
//SEGURANÇA
//$dados['filtros']['atleta_id'] = '';


$estrutura_main->setDados($dados); 

$layout .= $estrutura_main->inicio();

echo $layout;