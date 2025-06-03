# Rveiculos

Rveiculos é um sistema web desenvolvido para gerenciar veículos, utilizando tecnologias modernas para oferecer uma interface intuitiva e funcionalidades robustas.


## 📸 Apresentação Visual do Projeto

### 🖼️ Interface Inicial
![Interface Inicial](./v1.png)
Esta imagem mostra a interface inicial do sistema, com destaque para o menu principal e as opções de navegação. É a tela que dá boas-vindas ao usuário e apresenta as funcionalidades disponíveis de forma organizada e intuitiva.

---

### 📋 Tela de Listagem de Itens
![Listagem de Itens](./v2.png)
Exibe a visualização da lista de elementos (como arquivos, registros ou dados) já inseridos no sistema. Cada item é apresentado em forma de tabela ou grade, com botões de ação ao lado (como editar, remover, visualizar), permitindo uma navegação funcional e fluida.

---

### 📝 Tela de Inserção ou Cadastro
![Tela de Cadastro](./v3.png)
Mostra o formulário de entrada de dados, onde o usuário pode inserir novos registros. A tela contém campos bem definidos, labels claras e botões de confirmação, garantindo uma experiência de uso objetiva e amigável.

---

### 🔍 Tela de Visualização Detalhada
![Visualização Detalhada](./v4.png)
Apresenta uma visão detalhada de um item selecionado, com todas as suas informações e possíveis ações extras (como exportar, compartilhar ou modificar). Ideal para uma inspeção completa dos dados.


## 🚀 Tecnologias Utilizadas

- Vue.js
- Laravel Blade
- PHP
- CSS
- JavaScript
- HTML

## 📁 Estrutura do Projeto

- `frontend/`: Contém os arquivos relacionados à interface do usuário.
- `sail/`: Scripts e configurações para o ambiente de desenvolvimento com Laravel Sail.
- `relatorio.sql`: Script SQL para geração de relatórios.
- `terminal.txt`: Logs e comandos utilizados durante o desenvolvimento.

## 📦 Instalação

```bash
# Clone o repositório
git clone https://github.com/MartinesEmanuel/Rveiculos.git

# Acesse o diretório do projeto
cd Rveiculos

# Instale as dependências do backend
composer install

# Instale as dependências do frontend
npm install

# Configure o .env com suas variáveis (copie de .env.example)
cp .env.example .env

# Gere a chave da aplicação Laravel
php artisan key:generate

# Rode as migrações e seeds
php artisan migrate --seed

# Inicie o servidor
php artisan serve

