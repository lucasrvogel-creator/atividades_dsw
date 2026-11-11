# Linktree — Programação Web I (UNIDAVI)

Atividade em PHP puro e MySQL, sem bibliotecas externas.

## Arquivos

- `index.php`: página pública, lista de perfis e links.
- `admin.php`: login, inclusão, listagem, alteração e exclusão de links.
- `criar_admin.php`: cadastro do primeiro administrador (apagar depois de usar).
- `sair.php`: encerra o login.
- `conexao.php`: configura a conexão local com o MySQL (não é enviado ao GitHub).
- `conexao.exemplo.php`: modelo de conexão para quem clonar o repositório.
- `banco.sql`: cria o banco e as tabelas.
- `estilo.css`: aparência das páginas.

## Como executar no XAMPP

1. Coloque a pasta `lkt` em `C:\xampp\htdocs\lkt`.
2. Inicie **Apache** e **MySQL** no XAMPP.
3. Acesse `http://localhost/phpmyadmin`, abra a aba **SQL**, cole o conteúdo de `banco.sql` e execute. Também é possível usar a opção **Importar**.
4. Confira `conexao.php`: no XAMPP padrão, usuário `root` e senha vazia. Se clonou do GitHub, copie `conexao.exemplo.php` para `conexao.php` e ajuste a conexão.
5. Abra `http://localhost/lkt/criar_admin.php` e crie um usuário e senha (mínimo 8 caracteres).
6. **Exclua o arquivo `criar_admin.php` após criar o administrador**, inclusive antes de publicar o projeto.
7. Acesse `http://localhost/lkt/admin.php`, faça login e cadastre links.
8. Para ver o perfil, abra `http://localhost/lkt?a=fulano` (apelido cadastrado no painel).

**Exemplo de cadastro:**

- URL: `https://github.com/seuusuario`
- Nome: `Meu GitHub`
- Apelido: `fulano`

A URL é a chave primária da tabela, conforme o enunciado. Portanto, a mesma URL não pode ser cadastrada duas vezes, mesmo em perfis diferentes.

## Partes da atividade

- **Parte 1:** banco de dados, listagem de links e inclusão.
- **Parte 2:** exclusão e alteração.
- **Parte 3:** autenticação para impedir alterações por visitantes.

## Enviar para o GitHub

Na pasta `lkt`, execute:

```bash
git init
git add .
git commit -m "Atividade Linktree completa"
git branch -M main
git remote add origin https://github.com/SEU_USUARIO/SEU_REPOSITORIO.git
git push -u origin main
```

Crie antes um repositório **vazio** no GitHub. O `.gitignore` evita que o arquivo `conexao.php` com as credenciais locais seja publicado.

Se o Git já estiver inicializado na pasta, não é necessário repetir `git init` ou `git remote add origin`.
