# [GRNacoes - Sistema Administrativo Clínica de Olhos Nações e Ótica +Visão]()

Sistema desenvolvido para a [Clínica de Olhos Nações](https://www.google.com/search?q=clinica+de+olhos+nações) e [Ótica +Visão](https://www.google.com/search?q=otica+%2Bvisao), por [Kaique](https://github.com/KaiqueWalkerRodrigues/).

---

## 🚀 Como Utilizar/Testar

Após baixar os arquivos, siga os passos abaixo:

### 1. Instalando WampServer (ou XAMPP)
Baixe o [WampServer](https://sourceforge.net/projects/wampserver/files/WampServer%203/WampServer%203.0.0/wampserver3.3.7_x64.exe/download) e prossiga com todas as etapas de instalação até finalizar e inicializar o servidor.

### 2. Movendo o projeto
Mova a pasta do projeto `GRNacoes/` para o diretório: `\wamp64\www\`

### 3. Importando o Backup
No seu banco de dados de escolha (MySQL, PhpMyAdmin, MariaDB, ...), importe o arquivo de backup: `backup.sql`

### 4. Configurando o acesso ao banco de dados
No diretório `class/` existe o arquivo: `Conexao_example.php`

Edite-o conforme sua configuração de banco.  
Se estiver utilizando MySQL/PhpMyAdmin (porta **3306**) com banco de dados chamado `GRNacoes`, a configuração padrão já deve funcionar.  

Após configurar, salve o arquivo renomeado como: `Conexao.php`

### 5. Acessando com usuário de teste
No navegador, acesse: `http://localhost/GRNacoes`

Use as credenciais de teste para login:

- **Usuário:** `teste`  
- **Senha:** `123456`

---