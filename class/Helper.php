<?php

/**
 * Classe com metodos estáticos
 */
class Helper{

  //Função para Subir arquivo para o Sistema
  // public static function sobeArquivo($arquivo,$diretorio = '../resources/img/'){
  //   $arquivo = $arquivo;
  //   // pegar apenas o nome original do arquivo
  //   $nome_arquivo = $arquivo['name'];
  //     // verificar se algum arquivo foi enviado
  //     if(trim($nome_arquivo)!= '') {
  //         // pegar a extensao do arquivo         
  //         $extensao = explode('.', $nome_arquivo);
  //         // gerar nome         
  //         $novo_nome = date('YmdHis').rand(0,1000).'.'.end($extensao);         

  //         // montar o destino onde o arquivo será armazenado        
  //         $destino = $diretorio.$novo_nome;                  
  //         $ok = move_uploaded_file($arquivo['tmp_name'],$destino);
  //         // verificar se o upload foi realizado
  //         if($ok) {
  //           return $novo_nome;            
  //         } else {
  //           return false;
  //         }

  //     } else {
  //       return false;
  //     }
  // }
      
  //Encurta o nome completo de um Usuário
  public static function encurtarNome($nomeCompleto){
      $nomes = explode(' ', $nomeCompleto);
      $primeiroNome = $nomes[0];
      $primeiroSobrenome = isset($nomes[1]) ? $nomes[1] : ''; // Verifica se existe um sobrenome

      return $primeiroNome . ' ' . $primeiroSobrenome;
  }

  //Criptografar um valor
  public static function criptografar(string $valor){
    //Um valor qualquer para ser usado como
    //chave na criptografia
    $salt = 'Jot@'; 

    //Retorna o valor recebido comp parâmetro,
    //usando a função CRYPT e o SALT
    return crypt($valor, $salt);
  }

  //Formata a data e acordo com a diferença de agora
  public static function data($data = null){
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

  //Traduz o Mês do Inglês
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

  //Status de um Chamado
  public static function statusChamado($status){
    switch($status){
        case 1:
          return "<b class='badge badge-dark badge-pill'>Em Análise</b>";
        break;
        case 2:
          return "<b class='badge badge-secondary badge-pill'>Em Andamento</b>";
        break;
        case 3:
          return "<b class='badge badge-success badge-pill'>Concluído</b>";
        break;
        case 4:
          return "<b class='badge badge-danger badge-pill'>Cancelado</b>";
        break;
        case 5:
          return "<b class='badge badge-dark badge-pill'>Recusado</b>";
        break;
    }
  }

  //Status de um Chamado com Html
  public static function TextoStatusChamado($status){
    switch($status){
        case 1:
          return "Em Análise";
        break;
        case 2:
          return "Em Andamento";
        break;
        case 3:
          return "Concluído";
        break;
        case 4:
          return "Cancelado";
        break;
        case 5:
          return "Recusado";
        break;
    }
  }

  //Urgência de um Chamado
  public static function Urgencia($urgencia){
    switch($urgencia){
      case 1:
        return "<b style='color:#008000;'>Baixa</b>";
      break;
      case 2:
        return "<b style='color:#FFA500;'>Média</b>";
      break;
      case 3:
        return "<b style='color:#FF4500;'>Alta</b>";
      break;
      case 4:
        return "<b style='color:#FF0000;'>Urgente</b>";
      break;
    }
  }

  //Urgência de um Chamado com Html
  public static function TextoUrgencia($urgencia){
    switch($urgencia){
      case 1:
        return "Baixa";
      break;
      case 2:
        return "Média";
      break;
      case 3:
        return "Alta";
      break;
      case 4:
        return "Urgente";
      break;
    }
  }

  //Converter a data para o Padrão d/m/Y
  public static function converterData(string $data_sql): string{
      $data = DateTime::createFromFormat('Y-m-d', $data_sql);
      if ($data) {
          return $data->format('d/m/Y');
      } else {
          return $data_sql;
      }
  }

  //Converter a data para o Padrão d/m/Y ou d/m/Y H:i
  public static function formatarData($data) {
      // Verifica se a data não é nula ou vazia
      if (!empty($data)) {
          $data_formatada = new DateTime($data);
          
          // Verifica se a data contém horário
          if (strpos($data, ' ') !== false) { 
              // Se a data contém horário, formata com data e horário
              return $data_formatada->format('d/m/Y H:i');
          } else {
              // Se não contém horário, formata apenas a data
              return $data_formatada->format('d/m/Y');
          }
      }
      return ''; // Retorna string vazia se a data for nula ou vazia
  }
  
  //Converter a data para o Padrão d/m/Y ou d/m/Y H:i
  public static function formatarDataSemHorario($data) {
      // Verifica se a data não é nula ou vazia
      if (!empty($data)) {
          $data_formatada = new DateTime($data);
              return $data_formatada->format('d/m/Y');
      }
      return ''; // Retorna string vazia se a data for nula ou vazia
  }

  //Converte o Horario para o padrão H:i
  public static function formatarHorario($horario) {
      // Verifica se o horario não é nula ou vazia
      if (!empty($horario)) {
          $horario_formatada = new DateTime($horario);
            return $horario_formatada->format('H:i');
      }
      return ''; // Retorna string vazia se a horario for nula ou vazia
  }

  //Status de uma Captação
  public static function captado($tipo) {
    switch($tipo){
      case 1:
        return "<b class='badge badge-success badge-pill'>Sim</b>";
      break;
      case 0:
        return "<b class='badge badge-danger badge-pill'>Não</b>";
      break;
      case 2:
        return "<b class='badge badge-primary badge-pill'>Lente de Contato - Sim</b>";
      break;
      case 3:
        return "<b class='badge badge-primary badge-pill'>Lente de Contato Não</b>";
      break;
      case 4:
        return "<b class='badge badge-warning badge-pill'>Garantia</b>";
      break;
    }
  }

  //Retorna primeiro nome Maisculo
  public static function primeiroNomeMaisculo($string) {
    // Divide a string em palavras
    $palavras = explode(' ', $string);

    // Retorna a primeira palavra em letras maiúsculas
    return strtoupper($palavras[0]);
  }

  //Motivos de Não Captação
  public static function Motivo($motivo){
    switch($motivo){
      case 1:
        return 'Pressa';
      break;
      case 2:
        return 'Já Tem Ótica';
      break;
      case 3:
        return 'Não mudou Grau';
      break;
      case 4:
        return 'Não passou no Balção';
      break;
    }
  }

  public static function statusParcelaContrato($status){
    switch($status){
      case 0:
        return 'Pendente';
      break;
      case 1:
        return 'Pago';
      break;
      case 2:
        return 'Atrasado';
      break;

    }
  }
  
}

?>