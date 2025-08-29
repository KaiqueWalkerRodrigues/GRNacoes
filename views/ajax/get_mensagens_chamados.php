<?php 
   session_start();
   require '../../const.php';

   $pdo = Conexao::conexao();

   $id_chamado = $_GET['id_chamado'];
   $id_usuario = $_GET['id_usuario']; // Usuário logado
   $id_avatar = $_GET['id_avatar']; // (permanece, mas não será usado no render)
   $id_destinatario_avatar = $_GET['id_destinatario_avatar']; // (permanece, mas não será usado no render)

   $Chamado = new Chamado();
   $Usuario = new Usuario();

   // Obtem informações do chamado
   $chamado = $Chamado->mostrar($id_chamado);

   // ID do usuário que criou o chamado
   $id_usuario_dono_chamado = $chamado->id_usuario;

   // Setor do usuário logado
   $setor_logado = $_SESSION['id_setor'];

   // Consultar as mensagens + avatar do remetente + anexos
   $sql = $pdo->prepare("
       SELECT 
           m.*,
           a.id_anexo,
           a.nome_original,
           a.arquivo_sistema,
           DATE_FORMAT(m.created_at, '%H:%i')   AS hora_envio, 
           DATE_FORMAT(m.created_at, '%d/%m/%Y') AS data_envio,
           u.id_avatar AS avatar_remetente
       FROM mensagens m
       INNER JOIN chamados_mensagens cm ON cm.id_mensagem = m.id_mensagem
       INNER JOIN usuarios u ON u.id_usuario = m.id_usuario
       LEFT JOIN mensagens_anexos a ON a.id_mensagem = m.id_mensagem
       WHERE cm.id_chamado = :id_chamado AND m.deleted_at IS NULL
       ORDER BY m.created_at ASC, a.id_anexo ASC
   ");
   $sql->bindParam(':id_chamado', $id_chamado, PDO::PARAM_INT);
   $sql->execute();

   // Funções utilitárias
   function buildFileUrl(?string $arquivoSistema): string {
       $arquivoSistema = (string)$arquivoSistema;
       if (preg_match('~^(https?://|/)~i', $arquivoSistema)) {
           return $arquivoSistema;
       }
       return '/GRNacoes/resources/anexos/chamados/' . ltrim($arquivoSistema, '/');
   }

   function extLower(string $nome): string {
       $ext = strtolower(pathinfo($nome, PATHINFO_EXTENSION));
       return $ext === 'jpeg' ? 'jpg' : $ext;
   }

   function isImage(string $ext): bool {
       return in_array($ext, ['jpg', 'png', 'gif', 'webp', 'svg'], true);
   }

   function getFileIconClass(string $ext): string {
       switch ($ext) {
           case 'pdf':
               return 'fa-solid fa-file-pdf text-danger';
           case 'doc':
           case 'docx':
               return 'fa-solid fa-file-word text-primary';
           case 'xls':
           case 'xlsx':
           case 'csv':
               return 'fa-solid fa-file-excel text-success';
           case 'ppt':
           case 'pptx':
               return 'fa-solid fa-file-powerpoint text-warning';
           case 'zip':
           case 'rar':
           case '7z':
               return 'fa-solid fa-file-archive text-secondary';
           default:
               return 'fa-solid fa-paperclip';
       }
   }

   if ($sql->rowCount() > 0) {
       $resultados = $sql->fetchAll(PDO::FETCH_ASSOC);

       // Agrupa anexos pela mensagem
       $mensagens_agrupadas = [];
       foreach ($resultados as $row) {
           $mid = $row['id_mensagem'];
           if (!isset($mensagens_agrupadas[$mid])) {
               $mensagens_agrupadas[$mid] = $row;
               $mensagens_agrupadas[$mid]['anexos'] = [];
           }
           if ($row['id_anexo']) {
               $mensagens_agrupadas[$mid]['anexos'][] = [
                   'id_anexo' => $row['id_anexo'],
                   'nome_original' => $row['nome_original'],
                   'arquivo_sistema' => $row['arquivo_sistema']
               ];
           }
       }

       $dia_anterior = '';

       foreach ($mensagens_agrupadas as $value) {
    $data_envio = $value['data_envio'];
    $hora_envio = $value['hora_envio'];

    $data_hoje = date('d/m/Y');
    $data_ontem = date('d/m/Y', strtotime('-1 day'));

    if ($data_envio == $data_hoje) {
        $data_display = 'Hoje';
    } elseif ($data_envio == $data_ontem) {
        $data_display = 'Ontem';
    } else {
        $data_display = $data_envio;
    }

    if ($data_display != $dia_anterior) {
        echo '<div style="text-align: center; margin: 10px 0; color: #000000;">'.$data_display.'</div>';
        $dia_anterior = $data_display;
    }

    $id_usuario_remetente = $value['id_usuario'];

    $mandante = $Usuario->mostrar($id_usuario_remetente);
    $nomeCompleto = $mandante->nome;
    $partesNome = explode(' ', trim($nomeCompleto));
    $primeiroNome = $partesNome[0] ?? '';
    $ultimoNome = count($partesNome) > 1 ? array_pop($partesNome) : '';

    $is_usuario_logado = $id_usuario_remetente == $id_usuario;
    if($is_usuario_logado == true){
        $class = 'eu';
    }elseif($id_usuario_remetente != $id_usuario_dono_chamado){
        $class = 'eu';
    }else{
        $class = 'outro';
    }
    $avatar_remetente = (int)($value['avatar_remetente'] ?? 1);

    // ==== CONTEÚDO DO BALÃO (texto + anexos) ====
    $conteudoBalao  = nl2br(htmlspecialchars($value['mensagem']));

    // Se houver anexos, renderize DENTRO do balão
    if (!empty($value['anexos'])) {
        $conteudoBalao .= '<div class="message-media-grid" style="display:flex; flex-wrap:wrap; gap:8px; margin-top:8px;">';
        foreach ($value['anexos'] as $anexo) {
            $nome_original   = $anexo['nome_original'] ?? '';
            $arquivo_sistema = $anexo['arquivo_sistema'] ?? '';
            $urlArquivo      = buildFileUrl($arquivo_sistema);
            $ext             = extLower($nome_original);

            if (isImage($ext)) {
                // Imagem dentro do balão
                $conteudoBalao .= '<a href="'.htmlspecialchars($urlArquivo).'" data-fancybox="gallery" data-caption="'.htmlspecialchars($nome_original).'" style="display:inline-block;">
                     <img src="'.htmlspecialchars($urlArquivo).'" alt="'.htmlspecialchars($nome_original).'" style="max-width: 320px; margin-bottom: 5px; max-height: 240px; border-radius: 10px; display:block;">
                </a>';
            } else {
                // Arquivo não-imagem dentro do balão
                $iconClass = getFileIconClass($ext);
                $conteudoBalao .= '<div class="card p-2" style="width:180px; text-align:center; margin-bottom: 5px;">
                    <i class="'.$iconClass.'" style="font-size:24px;"></i>
                    <p style="font-size:12px; margin-top:8px; word-break:break-word;">'.htmlspecialchars($nome_original).'</p>
                    <a href="'.htmlspecialchars($urlArquivo).'" download="'.htmlspecialchars($nome_original).'" class="btn btn-sm btn-primary">Baixar</a>
                </div>';
            }
        }
        $conteudoBalao .= '</div>';
    }

    // HTML final da mensagem (agora o $conteudoBalao já inclui anexos)
    echo '
    <div class="icon-container row ' . $class . '">
        ' . ($class == 'eu' ? '
            <div class="col-auto">
                <div class="card p-2 mb-1 position-relative" style="overflow:hidden; max-width: 300px;">
                    <span style="word-wrap: break-word;">' . $conteudoBalao . '</span>
                    <span class="hora-enviada" style="font-size:10px; display:block; text-align:right;">
                        ' . $primeiroNome . ' ' . $ultimoNome . ' - ' . $hora_envio . '
                    </span>
                </div>
            </div>
            <div class="col-auto">
                <img class="btn-icon btn-md mb-2" src="' . URL_RESOURCES . '/assets/img/avatars/' . $avatar_remetente . '.png" alt="">
            </div>
        ' : '
            <div class="col-auto">
                <img class="btn-icon btn-md mb-2" src="' . URL_RESOURCES . '/assets/img/avatars/' . $avatar_remetente . '.png" alt="">
            </div>
            <div class="col-auto">
                <div class="card p-2 mb-2 position-relative" style="overflow:hidden; max-width: 300px;">
                    <span style="word-wrap: break-word;">' . $conteudoBalao . '</span>
                    <span class="hora-enviada" style="font-size:10px; display:block; text-align:right; color: grey;">
                        ' . $primeiroNome . ' ' . $ultimoNome . ' - ' . $hora_envio . '
                    </span>
                </div>
            </div>
        ') . '
    </div>';
}

   }
?>
