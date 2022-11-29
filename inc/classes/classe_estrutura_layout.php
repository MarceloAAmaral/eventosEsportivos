<?PHP
class Estrutura_layout{
	var $Sub_id;
	var $dados;
	var $dado;
	var $dadosMetodos;
	var $dados_parametros;
	public $grupos;
	var $Subgrupos;
	public $params;
	public $parametros;
	public $tag;
	public $tagFilha;
	public $layout;
	public $mtd;
	public $subsecao;
	public $sec_cont;
	
	
public function __construct(){
	#classe Metodos
	include "classe_metodos.php";		
	$this->mtd = new Metodos;
	$this->mtd->setDados($this->getDados());
	
}	
public function setDados($d){
		$this->dados = $d;
	}
public function getDados(){
		return $this->dados;
	}
public function setSub($a){
   	$this->subsecao = $a['sub'];		
	#alvo				
		if(!empty($a['alvo'])){						
			$this->dados['url']['href'] = $this->dados['url']['href']."/".$a['alvo'];	
			$param_url_alvo=consultaDadosTabela('sub','param_url',array('sub'=>$a['alvo']),'');
			$this->dados['url']['alvo']['param_url']=explode(',',$param_url_alvo[0]['param_url']);		
		}else{
			 $this->dados['url']['href'] = $this->dados['url']['href']."/".$a['sub'];	
		}
}
public function getSub(){	
	 return $this->subsecao;
}
public function setDadosSub($a){
	$this->dados['sub'] = $a;
}
public function getDadosSub(){
	return $this->dados['sub'];
}
public function estruturaSub(){	

	$layout = '';	
	$dados = $this->getDadosSub();
	$layout .= "<div id='sub-{$dados['sub']}'";
#estilos
	if(!empty($dados['classes'])){
		$layout .= " class='{$dados['classes']}'";
	}
	$layout .= ">";
#param_url
	if(isset($this->dados['url']['filtros']) AND !empty($dados['param_url'])){
		$param_url = explode(',',$dados['param_url']);
		foreach($param_url as $ord => $param){
			$this->dados['filtros'][$param] = $this->dados['url']['filtros'][$ord];
		}	
	}
		if(!empty($dados['sec_cont_id'])){			
			$tabela ='sec_cont';
			$campos ='';
			$filtros = array('ativo'=>'1','sec_cont_id'=>$dados['sec_cont_id']);
			$order = '';
			if($retorno = consultaDadosTabela($tabela,$campos,$filtros,$order)){
				for ($a=0;$a<count($retorno);$a++) {				
					$this->setDadosSecCont($retorno[$a]);
					$this->setSecCont($retorno[$a]);
					$layout .= $this->estruturaSecCont();
				}			
			}				
		}
	$layout .= "</div>";				
			/*	
				if(!empty($dados['sec_pai'])){
					$sec_pai = consultaDadosTabela('sec','sec',array('sec_id'=>$dados['sec_pai'],''),'');
					$this->dados['url']['href'] = $this->dados['url']['href'].'/'.$sec_pai[0]['sec'];
				}				
				$this->dados['filtros']['sub_id'] = $dados['sub_id'];
	
			

			*/		
	return $layout;		
}
public function setDadosSecCont($a){
	$this->dados['sec_cont'] = $a;
}
public function getDadosSecCont(){
	return $this->dados['sec_cont'];
}
public function setSecCont($a){	
	$this->sec_cont = $a['sec_cont'];
	#alvo				
		if(!empty($a['alvo'])){						
			//$this->dados['url']['href'] = $this->dados['url']['href']."/".$a['alvo'];	
			//$param_url_alvo=consultaDadosTabela('sub','param_url',array('sub'=>$a['alvo']),'');
			//$this->dados['url']['alvo']['param_url']=explode(',',$a['param_url']);		
		}else{
			 $this->dados['url']['href'] = $this->dados['url']['href']."/".$a['sub'];	
		}
}
public function getSecCont(){	
	return $this->sec_cont;
}
public function estruturaSecCont(){
    $layout = "";	
	$dados = $this->getDadosSecCont();			
	$sec_cont = $dados['sec_cont'];	
	$layout .= "<div id='sec_cont_{$sec_cont}' ";
	if(!empty($dados['classes'])){
		$layout .=" class='{$dados['classes']}'";	
	}
	$layout .= ">";	
	#header
		if(isset($dados['sec_cont_header'])){
			$layout .= "<div class='sec_cont_header'>{$dados['sec_cont_header']}</div>";
		}
		#grupos de parametros			
			$layout .= "<div class='container-items'>"; 
			$this->setGrupos($dados['sec_cont_grupo_id']);			
			#metodos
			if(!empty($dados['metodos'])){
			$this->mtd->setDados($this->getDados());
				$arr_metodos = explode(',',$dados['metodos']);
				foreach($arr_metodos as $metodo){ 
					if($return_dados=$this->mtd->$metodo()){
						for($b=0;$b<count($return_dados);$b++){							
							$this->setDadosMetodo($return_dados[$b]);
							$layout .= $this->estrutura_layout();
						}
					}	
				}				
			}else{
			    $layout .= $this->estrutura_layout();
			}
			$layout .= "</div>"; 	
	return $layout;
}
public function estrutura_layout(){
	$layout='';
# a estrutura de dados tem uma tag container, como é o caso de form
	if(isset($this->Dados['container'])){
		$layout .= "<".$this->Dados['container']['tag'];
		foreach($this->Dados['container']['parametros'] as $param=>$dado){
			$layout.= " $param='$dado'";
		}
	}
	$layout .= $this->estrutura_grupos();
#fecha o container e trata suas especificidades	
	if(isset($this->Dados['container'])){
		if($this->Dados['container']['tag'] == 'form'){
			$layout .= " <input type='submit' name='acoes' value='{$this->Dados['container']['botao']}'>";
		}
		
		$layout .= "</".$this->Dados['container']['tag'].">";		
	}	
	return $layout;		
}
function estrutura_grupos(){
	$layout = "<div class='item'>";	
	if($grupos = $this->getGrupos()){
		for($a=0;$a<count($grupos);$a++){
			$dados = $grupos[$a];	
			
			$layout .= "<div id='grupo_{$dados['grupo']}'";
			if(!empty($dados['classes'])){
				$layout .= " class='{$dados['classes']}' ";
			}
			$layout .=">";
			if(!empty($dados['grupo_titulo'])){
			$layout .= "<h3>{$dados['grupo_titulo']}</h3>";
			}
			if(!empty($dados['descr'])){
			$layout .= "<article class='descr'>{$dados['descr']}</article>";
			}
		
	# acaso o grupo tenha uma requisição de dados		
			if(!empty($dados['grupo_filtros'])){
				
				/*	
				if(!empty($this->Dados['dados'][$dados['grupo_filtros']])){
					$tabela = $dados['grupo_tabela'];
					$campos = '';
					$filtros = array($dados['grupo_filtros']=>$this->Dados['dados'][$dados['grupo_filtros']]);
					$order = '';
					$retorno = consultaDadosTabela($tabela,$campos,$filtros,$order);
					for($b=0;$b<count($retorno);$b++){
						$this->Dados['dados'] = $retorno[$b];
						$layout .=	$this->estrutura_param($dados['params']);	
					}
	#subgrupos 
					if(isset($dados['subgrupos'])){
						//$layout .= $this->estrutura_subgrupo($dados['subgrupos']);
					}
				}
				*/
			}else{
				$this->setParams($dados['params_id']);				
				$layout .=	$this->estrutura_param();
	#subgrupos 
				if(isset($dados['subgrupos'])){
					//$layout .= $this->estrutura_subgrupo($dados['subgrupos']);
				}
			}			
			$layout .= "</div>";
		}
	}
	$layout .= "</div>";
	return $layout;	
}
public function getGrupos(){
	return $this->grupos;
}
public function setGrupos($dados){	
	if(!empty($dados)){
		$tabela = 'sec_cont_grupo';
		$campos = '';
		$filtros = array("sec_cont_grupo_id"=>$dados);
		$order = array("sec_cont_grupo_id"=>$dados);
		if($retorno = consultaDadosTabela($tabela,$campos,$filtros,$order)){
			$this->grupos = $retorno;
		}				
	}
}
public function setParams($dados){
	if(!empty($dados)){	
		$tabela = 'param';
		$campos = '';
		$filtros = array('param_id'=>$dados);
		$order = array('param_id'=>$dados);
			if($retorno = consultaDadosTabela($tabela,$campos,$filtros,$order)){
				$this->params = $retorno;
			}
	}
}
public function getParams(){
	return $this->params;
}
function estrutura_param(){	
	$layout ="";
	if($params = $this->getParams()){
		for($f=0;$f<count($params);$f++){
			$dados_param= $params[$f];
			$this->setDadosParametro($dados_param);			
			$param = $dados_param['param'];
			$this->setParam($param);
			//$this->setTagFilha($dados_param['tag_filha']);
			
			//$this->setDado();
			$layout .= "<div data-param='$param'";
				if(!empty($dados_param['classes'])){ 
					$layout .= " class='{$dados_param['classes']}'";
				}
				$layout .= ">";
				if(!empty($dados_param["titulo"]) AND $dados_param['html'] !== 'img'){
					$layout .= "<span>{$dados_param["titulo"]}</span>";		
				}
				$this->setTag($dados_param['html']);
				$layout .= $this->estruturaTag();
#dúvidas na execução do trecho abaixo 
/*				
				if($param == 'tabela'){
					$param = $this->Dados['dados'][$param];
					$dados_param = consultaDadosTabela('param','',array('param'=>$param),'');
					$dados = $dados_param[0];						
				}
#clone
				if(!empty($dados_param['clone'])){
					$param = $dados_param['clone'];
				}
*/
#Dado = define como dado(se existir)será criado e apresentado: pela tag HTML ou por input 
				
	/*			
#obter dados a nível de tag									
				//$dados_param['param'] = $dados_param['tabela'];	
				if(!empty($dados_param['campo'])){
					$tabela = $dados_param['tabela'];
					$campos = $dados_param['campo'];
					$filtros = $this->Dados['filtros'];
					if(!empty($dados_param['filtro'])){
						$filtros[$dados_param['filtro']] = $this->Dados['dados'][$dados_param['filtro']];
					}
					
					$order = '';		
					if($dados = consultaDadosTabela($tabela,$campos,$filtros,$order)){
						
						if($this->getTagFilha()){
							$this->setDadosParametro($dados);
							$this->estruturaTag();														
						}else{
							for($a=0;$a<count($dados);$a++){
								$this->setDadosParametro($dados[$a]);
								$layout .= $this->estruturaTag($dados_param['tag'],'');
							}
						$this->dados_parametro = $dados_param['dados'];
						$layout .= $this->estruturaTag($dados_param['tag'],$dados_param['tagfilha']);
					}
					}
#estruturaTag
					
				}else{
#estruturaTag
					$layout .= $this->estruturaTag();
				}				
				*/	
				$layout .= "</div>";
		}
	}

	return $layout;
}
function estruturaTag(){
	$layout = "";
	$layout .= "<".$this->getTag()." ";
	$this->setParametros();
	$layout .= $this->estruturaParametros();
	$layout .=">";
	$this->setDado();
	$layout .= $this->getDado();
	$layout .= "</".$this->getTag().">";
	/*
	if($this->getTagFilha()){
		$this->setTag = $this->getTagFilha();
		if($dados = getDadosParametro()){
			for($a=0;$a<$dados;$a++){	
				foreach($dados[$a] as $valores){
					$this->setDadosParametro($valores);
					$this->setParametros();
					$layout .= estruturaTag();
				}
			}
		}else{
			$layout .= $this->estruturaTag();
		}
	}*/
		/*		
#data-icon	
		if(!empty($dados_param['data-icon'])){
			if(!empty($dados_param['tabela']) AND isset($dados_param['dados'][$dados_param['tabela']])){
			$layout .= " data-icon='{$this->Dados['dados'][$dados_param['tabela']]}'";
			}else{
			$layout .= " data-icon='{$dados_param['param']}'";
			}
		}
		
#data-param
		if(!empty($dados_param['data-param'])){
			$layout .= " data-{$dados_param['tabela']}='{$this->Dados['dados'][$dados_param['tabela']]}'";
		}		
		$layout .= ">";
*/
	
/*
# fim do laço da tag
	if($tag != 'input'){
		if(!empty($this->dado)){
		$layout .= "{$this->dado}";
		}
		$layout .= "</$tag>";
	}
	*/
#label
/*	
	if($dados['label']){
		$layout .= "<label for='{$dados['dado']}'>{$dados['dado'][$dados['tabela'].'_titulo']}</label>";	
	}	
	*/
	return $layout;
}
public function setDado(){
	$this->dado = "";
	$param = $this->getParam();
	if($dadosMetodo = $this->getDadosMetodo()){
		$dadosParam = $this->getDadosParametro();
		if(isset($dadosMetodo[$param]) AND $param !="src"){
			$this->dado = mascaraDados($dadosParam['mascara'],$dadosMetodo[$param]);						
		}	
	}	
	if(!empty($dadosParam['ancora'])){
		$this->dado = $dadosParam['ancora'];
	}
	
}
public function getDado(){
	return $this->dado;
}
function setParametros() {
    $layout = "";
	$tag = $this->getTag();
	# três fontes de dados:
	
	$dados = $this->getDados();
	$dadosMetodo = $this->getDadosMetodo();
	$dadosParam = $this->getDadosParametro();	
    $array = array();
		switch ($tag) {
			case "a":
				$array['href'] = $dados['url']['href'];
				if(isset($dados['url']['alvo']['param_url'])){					
					foreach($dados['url']['alvo']['param_url'] as $param_alvo){
						$array['href']=$array['href']."/".$dadosMetodo[$param_alvo];
					}
				}				
				$array['target'] = $dadosParam['target'];				
				break;
			case "img":
				$array['src'] = $dados['url']['inicio'].'/img/'.$dadosMetodo['src'];
				$array['title'] = $dadosMetodo['title'];
				$array['alt'] = $dadosMetodo['alt'];
				break;
			case "input":			
				$array['name'] = $dadosParam['param'];
				$array['type'] = $dadosParam['type'];
				$array['placeholder'] = $dadosParam['placeholder'];
				 if(isset($dadosMetodo[$dadosParam['param']])){
					$array['value'] = $dadosMetodo[$dadosParam['param']];
					}
				$array['required'] = $dadosParam['required'];
				$array['checked'] = $dadosParam['checked'];
				$array['min'] = $dadosParam['min'];
				$array['max'] = $dadosParam['max'];
				$array['autocomplete'] = $dadosParam['autocomplete'];
				$array['disabled'] = $dadosParam['disabled'];
				$array['maxlength'] = $dadosParam['maxlength'];				
				 if(isset($dadosMetodo[$dadosParam['param'].'_id'])){
					$array['id'] = $dadosMetodo[$dadosParam['param'].'_id'];
					}
				break;
			case "button":
				$array['type'] = $dadosParam['type'];
				break;
			case "iframe":
				$array['src'] ='';
				$array['frameborder'] = '';
				break;
			case "select":
				$array['name'] = $dadosParam['tabela_param'];
				if(isset($dadosMetodo["{$dadosParam['tabela_param']}_id"])){
				$array['name'] = $dadosMetodo[$dadosParam['tabela_param'].'_id'];
				}else{
					$array['name'] =$dadosParam['tabela_param']."[0]";
				}
				$array['required'] = $dadosParam['required'];
				break;
			case "option":
				$array['value'] = $dadosMetodo[$dadosParam['param']."_id"];
				$array['id'] =  $dadosMetodo[$dadosParam['param']."_id"];
				break;
			case "textarea":
				$array['name'] = $dadosMetodo[$dadosParam['param']];
				$array['placeholder'] = $dadosParam['placeholder'];
				$array['required'] = $dadosParam['required'] ;
				$array['dado'] = $dadosMetodo[$dadosParam['param']];
				break;
			case "form":
			    $array = $this->getParametrosForm();
				/*$array['parametros']['action'] = $this->Dados['dados']['action'];
				$array['parametros']['method'] = $this->Dados['dados']['method'];
				$array['parametros']['enctype'] = $this->Dados['dados']['enctype'];*/
				break;
		}
		$this->parametros = $array;
}
public function estruturaParametros(){	
	$layout ="";
	$dados = $this->getParametros();	
	foreach($dados as $key => $param_dado){		
		if(!empty($param_dado)){
			$layout .=  "{$key}='".$param_dado."'";	
		}			
	}
	return $layout;
}
public function getParametros(){
	return $this->parametros;
}
public function layout($l){
	$this->layout .= $l;
}
function consultaSubgrupos($subgrupos_id){
		 $dados_subgrupos = consultaDadosTabela('sec_cont_subgrupo','',array("sec_cont_subgrupo_id"=>$subgrupos_id),'');
				for($c=0;$c<count($dados_subgrupos);$c++){
					if(!empty($dados_subgrupos[$c]['params_id'])){
					 $dados_subgrupos[$c]['params'] = consultaDadosTabela('param','',array('param_id'=>$dados_subgrupos[$c]['params_id']),array('param_id'=>$dados_subgrupos[$c]['params_id']));		
					}
				}
				return $dados_subgrupos;
}
function estrutura_subgrupo($dados_subgrupos){
		$layout = "";
		for($a=0;$a<count($dados_subgrupos);$a++){
			$dados = $dados_subgrupos[$a];
			$layout .="<div id='subgrupo_{$dados['subgrupo']}'";
			if(!empty($dados['classes'])){
				$layout .=" class='{$dados['classes']}' ";
			}
			$layout .=">";
			if(!empty($dados['subgrupo_filtros'])){
			if(!empty($this->Dados['dados'][$dados['subgrupo_filtros']])){
				$tabela = $dados['subgrupo_tabela'];
				$campos = '';
				$filtros = array($dados['subgrupo_filtros']=>$this->Dados['dados'][$dados['subgrupo_filtros']]);
				$order = '';
				$retorno = consultaDadosTabela($tabela,$campos,$filtros,$order);
				for($b=0;$b<count($retorno);$b++){
					$this->Dados['dados'] = $retorno[$b];
					$layout .=	$this->estrutura_param($dados['params']);	
				}
			}
		}else{
			$layout .=	$this->estrutura_param($dados['params']);
		}
			$layout .= "</div>";
		}
	return  $layout;
}
function estruturaTagOption($dados_param){
#estruturaTag option
	$dados_param['tag']='option';
	$layout .= "<{$dados_param['tag']} value=''>{$dados_param['titulo']}</{$dados_param['tag']}>";
	$qtde = count($dados_param['dados']);
	unset($dados_param['dados']);
	for($a=0;$a<$qtde;$a++){	
		foreach($dados_param[$a] as $param_dado => $dado){
			$dados_param['dado'] = $dado;
			$layout .= $this->estruturaTag($dados_param);
		}
	}
	return $layout;
}
public function ola(){
	return "ola mundo";
}
function getParametrosForm(){
	$tabela = 'form';
	$campos = '';
	$filtros = array('sec_cont_id'=>$this->Dados['filtros']['sec_cont_id']);
	 if($dados_tabela = consultaDadosTabela($tabela,$campos,$filtros,$order)){		
		 if($arr[0]['alvo']=='acoes'){
			$dados_tabela[0]['action']="{$this->Dados['url']['inicio']}/{$this->Dados['url']['estrutura']['sec']}/acoes/{$arr[0]['form']}";	 
			
		 }elseif(isset($this->Dados['url']['href'])){
			$dados_tabela[0]['action']= $this->Dados['url']['href']; 
			
		 }
		 if(isset($this->Dados['url']['href'])){
			 $dados_tabela[0]['botao'] = 'avançar';
		 }
				 
		  return  $dados_tabela[0];
	 }	
		
			
}
public function getDadosMetodo(){
	if(isset($this->dados['dados'])){
		return $this->dados['dados'];	
	}
	
}
public function setDadosMetodo($a){
	$this->dados['dados'] = $a;
}
public function setTag($tag){
	$this->tag = $tag;	
	/*if(isset($this->Dados[$this->getParam()])){
		if(isset($this->Dados['acao']) AND $this->Dados['acao'] != 'editar'){
			$this->tag = $html;	
		}
	}*/		
}
public function getTag(){
	return $this->tag;
}
public function setTagFilha($t){	
	$this->tagFilha = $t;		
}

public function getTagFilha(){
	if(!empty($this->tagFilha)){
	return $this->tagFilha;
	}
}
public function setParam($p){
	$this->param = $p;
}
public function getParam(){
	return $this->param;
}


public function setDadosParametro($d){
	$this->dados_parametro = $d;
}
public function getDadosParametro(){
	return $this->dados_parametro;
}
}
