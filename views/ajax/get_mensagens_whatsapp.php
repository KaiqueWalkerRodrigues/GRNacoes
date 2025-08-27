<?php 
    require '../../const.php';

    $pdo = Conexao::conexao();

    // ====== CONFIG ======
    const UPLOAD_BASE_URL = '/GRNacoes/resources/anexos/chats/';

    // ====== INPUTS ======
    $id_conversa = (int)($_GET['id_conversa'] ?? 0);
    $id_usuario  = (int)($_GET['id_usuario']  ?? 0);
    $id_avatar   = $_GET['id_avatar'] ?? '';
    $id_avatar_destinatario = $_GET['id_avatar_destinatario'] ?? '';

    // ====== QUERY (mensagens com ou sem anexo) ======
    // Alterado para buscar todas as mensagens e fazer LEFT JOIN com anexos
    $sql = $pdo->prepare("
        SELECT 
            m.*,
            a.id_anexo,
            a.nome_original,
            a.arquivo_sistema,
            DATE_FORMAT(m.created_at, '%H:%i') AS hora_envio,
            DATE_FORMAT(m.created_at, '%d/%m/%Y') AS data_envio,
            CASE 
                WHEN ml.id_mensagem IS NOT NULL THEN 'lida'
                ELSE 'nao_lida'
            END AS status_leitura
        FROM mensagens m
        INNER JOIN conversas_mensagens cm 
            ON cm.id_mensagem = m.id_mensagem
        -- status de leitura por OUTROS usuários
        LEFT JOIN mensagens_lidas ml 
            ON ml.id_mensagem = m.id_mensagem
           AND ml.id_usuario != :id_usuario
        -- Pega anexos, se existirem
        LEFT JOIN mensagens_anexos a
            ON a.id_mensagem = m.id_mensagem
        WHERE cm.id_conversa = :id_conversa
          AND m.deleted_at IS NULL
        ORDER BY m.created_at ASC, a.id_anexo ASC
    ");

    $sql->bindParam(':id_conversa', $id_conversa, PDO::PARAM_INT);
    $sql->bindParam(':id_usuario',  $id_usuario,  PDO::PARAM_INT);
    $sql->execute();

    // ====== Funções utilitárias ======
    function buildFileUrl(?string $arquivoSistema): string {
        $arquivoSistema = (string)$arquivoSistema;
        if (preg_match('~^(https?://|/)~i', $arquivoSistema)) {
            return $arquivoSistema;
        }
        return rtrim(UPLOAD_BASE_URL, '/').'/'.ltrim($arquivoSistema, '/');
    }

    function extLower(string $nome): string {
        $ext = strtolower(pathinfo($nome, PATHINFO_EXTENSION));
        return $ext === 'jpeg' ? 'jpg' : $ext;
    }

    function isImage(string $ext): bool {
        return in_array($ext, ['jpg','png','gif','webp','svg'], true);
    }

    function isVideo(string $ext): bool {
        return in_array($ext, ['mp4','webm','ogg','ogv','m4v','mov'], true);
    }

    function isAudio(string $ext): bool {
        return in_array($ext, ['mp3','wav','ogg','oga','m4a'], true);
    }

    // NOVO: Função para obter ícone do arquivo com base na extensão
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


    // ====== RENDER ======
    if($sql->rowCount() > 0) {
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

        foreach($mensagens_agrupadas as $value){
            $data_envio = $value['data_envio'];
            $hora_envio = $value['hora_envio'];
            
            // Lógica de data (Hoje/Ontem)
            $data_hoje  = date('d/m/Y');
            $data_ontem = date('d/m/Y', strtotime('-1 day'));
            if ($data_envio === $data_hoje) $data_display = 'Hoje';
            elseif ($data_envio === $data_ontem) $data_display = 'Ontem';
            else $data_display = $data_envio;

            if($data_display !== $dia_anterior){
                echo '<div class="date-separator"><span>'.htmlspecialchars($data_display).'</span></div>';
                $dia_anterior = $data_display;
            }

            $messageType = ($id_usuario === (int)$value['id_usuario']) ? 'sent' : 'received';
            
            $icone_leitura = '';
            if ($messageType === 'sent') {
                 $icone_leitura = ($value['status_leitura'] === 'lida')
                    ? '<i class="fa-solid fa-check" style="color: #53bdeb;"></i>' // azul
                    : '<i class="fa-solid fa-check" style="color: #92a3ab;"></i>'; // cinza
            }
            
            echo '<div class="message-container '.$messageType.'" data-id-msg="'.(int)$value['id_mensagem'].'">';
            echo '  <div class="message-bubble '.$messageType.'">';
            
            // Renderiza anexos
            if (!empty($value['anexos'])) {
                echo '<div class="message-media-grid" style="display:flex; flex-direction:column; gap:8px;">';
                foreach ($value['anexos'] as $anexo) {
                    $nome_original   = $anexo['nome_original'] ?? '';
                    $arquivo_sistema = $anexo['arquivo_sistema'] ?? '';
                    $urlArquivo      = buildFileUrl($arquivo_sistema);
                    $ext             = extLower($nome_original);
                    $preview         = '';

                    if (isImage($ext)) {
                        // ALTERADO: Adicionado class e data attributes para o lightbox
                        $preview = '
                            <a href="'.htmlspecialchars($urlArquivo).'" class="media-thumb image-lightbox" 
                            data-fancybox="chat-gallery"
                            data-caption="'.htmlspecialchars($nome_original).'"
                            style="display: block; position: relative;">
                                <img src="'.htmlspecialchars($urlArquivo).'" alt="'.htmlspecialchars($nome_original).'" style="max-width: 320px; max-height: 240px; border-radius: 10px; display:block;">
                            </a>';
                    }elseif (isVideo($ext)) {
                        $preview = '
                            <div class="video-container" style="max-width: 320px; max-height: 240px; border-radius: 10px; overflow: hidden;">
                                <video controls style="width:100%; height:100%; display:block;">
                                    <source src="'.htmlspecialchars($urlArquivo).'" type="video/'.($ext === 'mov' ? 'quicktime' : $ext).'">
                                    Seu navegador não suporta a reprodução de vídeo.
                                </video>
                            </div>';
                    } elseif (isAudio($ext)) {
                        $preview = '
                            <audio controls style="width: 280px; display:block;">
                                <source src="'.htmlspecialchars($urlArquivo).'">
                                Seu navegador não suporta a reprodução de áudio.
                            </audio>';
                    } else {
                        // ALTERADO: Lógica para ícones e links de arquivos
                        $iconClass = getFileIconClass($ext);
                        $isPdf = ($ext === 'pdf');
                        
                        // PDFs abrem em nova aba, outros apenas baixam pelo link "Baixar"
                        $linkTag = $isPdf 
                            ? '<a href="'.htmlspecialchars($urlArquivo).'" target="_blank" rel="noopener noreferrer" class="file-chip"'
                            : '<div class="file-chip"'; // Usa div se não for link clicável

                        $endLinkTag = $isPdf ? '</a>' : '</div>';

                        $preview = '
                            '.$linkTag.' style="display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:8px; background:#f0f2f5; text-decoration:none;">
                                <i class="'.$iconClass.'" style="font-size: 24px;"></i>
                                <span style="color:#111b21;">'.htmlspecialchars($nome_original).'</span>
                            '.$endLinkTag;
                    }

                    // Botão de download sempre usa 'nome_original'
                    $download = '
                        <a href="'.htmlspecialchars($urlArquivo).'" download="'.htmlspecialchars($nome_original).'"
                           class="download-link" style="font-size:12px; color:#667781; text-decoration:none; margin-top:6px; display:inline-flex; align-items:center; gap:6px;">
                            <i class="fa-solid fa-download"></i> Baixar
                        </a>';

                    echo '<div class="media-item" style="display:flex; flex-direction:column; gap:6px;">'.$preview.$download.'</div>';
                }
                echo '</div>'; // Fim .message-media-grid
            }

            // Renderiza texto da mensagem
            if (!empty($value['mensagem'])) {
                echo '<div class="message-text" style="padding-top:'.(!empty($value['anexos']) ? '8px' : '0').';">'.nl2br(htmlspecialchars($value['mensagem'])).'</div>';
            }
            
            echo '      <div class="message-time">';
            echo '          <span>'.htmlspecialchars($hora_envio).'</span>';
            if($messageType === 'sent'){
                echo '      <span class="message-status" id="status-'.(int)$value['id_mensagem'].'">'.$icone_leitura.'</span>';
            }
            echo '      </div>';
            echo '  </div>';
            echo '</div>';
        }

        // Lógica para marcar mensagens como lidas...
        $sql_lidas = $pdo->prepare("
            INSERT INTO mensagens_lidas (id_usuario, id_mensagem, lida_em)
            SELECT :id_usuario, m.id_mensagem, NOW()
            FROM mensagens m
            INNER JOIN conversas_mensagens cm ON cm.id_mensagem = m.id_mensagem
            LEFT JOIN mensagens_lidas ml ON ml.id_mensagem = m.id_mensagem AND ml.id_usuario = :id_usuario
            WHERE cm.id_conversa = :id_conversa AND ml.id_mensagem IS NULL AND m.id_usuario != :id_usuario
        ");
        $sql_lidas->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $sql_lidas->bindParam(':id_conversa', $id_conversa, PDO::PARAM_INT);
        $sql_lidas->execute();

    } else {
        echo '<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #667781; text-align: center; padding: 40px;">';
        echo '    <i class="fas fa-comments" style="font-size: 60px; margin-bottom: 20px; opacity: 0.3;"></i>';
        echo '    <p>Nenhuma mensagem ainda. Envie a primeira!</p>';
        echo '</div>';
    }
?>