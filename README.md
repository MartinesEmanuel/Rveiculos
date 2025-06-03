# Rveiculos

Rveiculos é um sistema web desenvolvido para gerenciar veículos, utilizando tecnologias modernas para oferecer uma interface intuitiva e funcionalidades robustas.

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

