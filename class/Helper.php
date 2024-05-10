<?php

/**
 * Classe com metodos estáticos
 */
class Helper{

  /**
   * Sobe Arquivo
   * @param  file  $arquivo    - Pode ser uma imagem ou qualquer outro
   *                             tipo de arquivo
   * @param  string $diretorio - Caminho da pasta onde o arquivo
   *                             será armazenado
   * @return string || false     - nome do arquivo
   */
public static function sobeArquivo($arquivo,$diretorio = '../imagens/'){
    $arquivo = $arquivo;
    // pegar apenas o nome original do arquivo
    $nome_arquivo = $arquivo['name'];
      // verificar se algum arquivo foi enviado
      if(trim($nome_arquivo)!= '') {
          // pegar a extensao do arquivo         
          $extensao = explode('.', $nome_arquivo);
          // gerar nome         
          $novo_nome = date('YmdHis').rand(0,1000).'.'.end($extensao);         

          // montar o destino onde o arquivo será armazenado        
          $destino = $diretorio.$novo_nome;                  
          $ok = move_uploaded_file($arquivo['tmp_name'],$destino);
          // verificar se o upload foi realizado
          if($ok) {
            return $novo_nome;            
          } else {
            return false;
          }

      } else {
        return false;
      }
  }
  
    /**
     * =======================================
     *  CONTROLE DE ACESSO
     * =======================================
     */

     /**
      * Verifica se existe a 
      * variavel de sessão logado
      *
      * @return bool
      */      
     public static function logado()
     {
      session_start();
       if(!isset($_SESSION['logado']) ){
        header('location:'.URL.'/login?falha');
       }
     }

     /**
      * Criptografa um valor
      *
      * 05/05/2022
      * @param string $valor
      * @return string
      */
     public static function criptografar(string $valor)
     {
       //Um valor qualquer para ser usado como
       //chave na criptografia
       $salt = 'Jot@'; 

       //Retorna o valor recebido comp parâmetro,
      //usando a função CRYPT e o SALT
       return crypt($valor, $salt);
     }

     public static function data($data = null)
     {
      $data_atual = new DateTime(date('d-m-Y H:i'));
      $data = new DateTime($data);
  
      // Resgata diferença entre as datas
      $d = date_diff($data_atual, $data);
      if($d->i < 1 and $d->h == 0 and $d->d == 0 and $d->m == 0 and $d->y == 0){
        print("Agora mesmo");
      }elseif($d->h == 0 and $d->d == 0 and $d->m == 0 and $d->y == 0){
        print("há ".$d->format('%I')." minuto(s)");
      }elseif($d->h > 0 and $d->d == 0 and $d->m == 0 and $d->y == 0){
        print("há ".$d->format('%h')." hora(s)");
      }elseif($d->d > 0 and $d->m == 0 and $d->y == 0){
        print("há ".$d->format('%d')." dia(s)");
      }elseif($d->m > 0 and $d->y == 0){
        print("há ".$d->format('%m')." mes(es)");
      }elseif($d->y > 0){
        print("há ".$d->format('%y')." ano(s)");
      }
     }

     //Função que mostra o nome da empresa
     public static function mostrar_empresa(int $id_empresa){
        switch ($id_empresa) {
          case 1:
              $empresa = 'Clínica Parque';
            break;
          case 2:
              $empresa = 'Ótica Matriz';
            break;
          case 3:
              $empresa = 'Clínica Mauá';
            break;
          case 4:
              $empresa = 'Ótica Prestigio';
            break;
          case 5:
              $empresa = 'Clínica Jardim';
            break;
          case 6:
              $empresa = 'Ótica Daily';
            break;
          default:
              $empresa = 'Erro';
            break;
        }
        return $empresa;
     }

     public static function traduzirMes($ingles) {
      $months = array(
          "Jan" => "Jan",
          "Feb" => "Fev",
          "Mar" => "Mar",
          "Apr" => "Abr",
          "May" => "Mai",
          "Jun" => "Jun",
          "Jul" => "Jul",
          "Aug" => "Ago",
          "Sep" => "Set",
          "Oct" => "Out",
          "Nov" => "Nov",
          "Dec" => "Dez"
      );

      return $months[$ingles];
    } 
    public static function statusProjeto($status){
      switch($status){
          case 1:
            return "Em Análise";
          break;
          case 2:
            return "Em Andamento";
          break;
          case 3:
            return "Concluido";
          break;
          case 4:
            return "Cancelado";
          break;
          case 5:
            return "Recusado";
          break;
      }
    }

}

?>