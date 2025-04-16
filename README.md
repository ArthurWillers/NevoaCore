# NévoaCore

**NévoaCore** é o núcleo de uma aplicação modular projetada para servir como base para diversos módulos complementares. Ele fornece funcionalidades essenciais de gerenciamento de usuários, como registro, login, recuperação de senha e exclusão de conta, funcionando como a fundação sobre a qual outras funcionalidades mais complexas serão construídas.

---

## 🔌 Modularidade

O projeto é pensado de forma extensível: os módulos adicionais poderão ser integrados facilmente ao núcleo, mantendo a separação de responsabilidades e facilitando a manutenção e evolução da aplicação.

---

## 📌 Funcionalidades Básicas (Core)

- Registro de usuários  
- Login  
- Recuperação de senha via e-mail  
- Exclusão de conta  
- Tela de administração para gerenciamento de usuários  

---

## ⚙️ Instalação

Siga os passos abaixo para configurar e executar o projeto:

1. **Clone o repositório**:
   ```bash
   git clone https://github.com/ArthurWillers/NevoaCore.git
   cd NevoaCore
   ```

2. **Instale as dependências do Composer**  
   Certifique-se de ter o Composer instalado. Em seguida, execute:
   ```bash
   composer install
   ```

3. **Configure o banco de dados**:

   - Crie um banco de dados chamado `NevoaCore` no seu servidor MySQL.
   - Importe o arquivo de esquema localizado em `config/schema.sql`.

4. **Configure o arquivo `.env`**:

   - Renomeie o arquivo `config/.env.example` para `config/.env`.
   - Preencha as variáveis de ambiente com as credenciais do seu servidor SMTP para envio de e-mails.

5. **Configure o servidor web**:

   - Certifique-se de que o servidor web (como Apache ou Nginx) esteja configurado para apontar para o diretório raiz do projeto.
   - Habilite o suporte a PHP no servidor.

6. **Permissões**:

   - Certifique-se de que o diretório `vendor/` e outros arquivos necessários tenham as permissões corretas para leitura e execução.

7. **Acesse a aplicação**:

   - Abra o navegador e acesse:  
     ```
     http://localhost/NevoaCore
     ```
     ou o domínio configurado.

---

## 🧰 Tecnologias Utilizadas

- PHP 8.3+  
- MySQL/MariaDB  
- Bootstrap 5  
- PHPMailer  
- Dotenv  

---

## 🤝 Contribuição

Contribuições são bem-vindas!  
Sinta-se à vontade para abrir *issues* ou enviar *pull requests*.

---

## 📄 Licença

Este projeto está licenciado sob a **GNU General Public License v3.0**.

