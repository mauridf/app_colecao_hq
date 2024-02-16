## Sobre Coleção HQ

Sistema feito em PHP com LARAVEL composto de uma API e um FrontEnd feito em Vue.js para cadastrar informações e gerenciar coleção de HQs (pode ser adptado para coleção de livros, músicas, etc...).
O Sistema faz os seguintes Cadastros:

- Editora (As editoras de HQs - DC, Marvel, Vertigo, etc...)
- Personagem (Os personagens/equipes - Batman, Vingadores, etc...)
- Tipo de Série (Tipo de Série de HQ - Mensal, One Shot, Graphic Novel, etc...)
- Status (Status da HQ - Em andamento, Finalizada, Cancelada, etc...)
- HQ (As HQs propiamente ditas, as informações mais relevantes)
- Ainda existem duas tabelas auxiliares HQ_Editora e HQ_Personagem para comtemplar o cadastro do tipo Many to Many.

Com todas as informações cadastradas é possível gerar relatórios com informações a respeito da coleção.
  
## Tecnologias Utilizadas

- **BANCO DE DADOS: MySql
- **BACKEND: PHP
- **FRAMEWORK: LARAVEL
- **FRONTEND: Vue.js | Vuex

## Para executar o sistema

- Após baixar (clonar) o fonte é necessário instalar o laravel (usei a versão 3.2.1)
- Fazer as devidas configurações no arquivo env (apontar para o seu localhost e o nome da base q vc criou)
- Executar o comando migrate para gerar todas as tabelas
- Executar o comando php artisan serve para rodar o Backend
- Executar a intalação do pacote JWT
- Executar o comando php artisan serve para rodar o Backend.
- Instalar o pacote UI com o comando composer require laravel/ui:^3.2.1
- Gerar o esqueleto do fontend do projeto em Vue.js com o comando php artisan ui vue --auth
- Baixar as dependencias do frontend com o comando npm install
- Produzir o Bundle do fontend com o comando npm run dev (se ao finalizar n compilar e pedir para executar algum comando execute e depois repita o comando novamente até a compilação ocorrer)
- Executar o npm install vuex@3.6.2 para instalar o Vuex para gerenciamento de store do Vue.js
- Com o Back e o Front em execução é só acessar o link informado.
