# Marketplace API

Marketplace API é uma plataforma que conecta prestadores de serviços e clientes, permitindo que profissionais estejam disponíveis num clique para serem contratados, conectados a clientes e empresas em qualquer parte do mundo. Para gestão do negócio, a plataforma disponibiliza um conjunto de serviços complementares e programa de engajamento.

### BACKEND

- [PHP](https://www.php.net/)
- [LARAVEL](https://laravel.com/)
- [NODE JS](https://nodejs.org/en)
- [RABBITMQ](https://www.rabbitmq.com/)

## Executando o projeto

Abaixo seguem as instruções para você executar o projeto na sua máquina.

Comece clonando o repositório e instalando suas dependências:

```

git clone <https://git@github.com:KHS-develop/marketplace-api.git>

cd <api-marketplace>

# O gerenciador de pacotes principal é o composer, e sugerimos que utilize o mesmo
composer install

# Env set up
cp .env-example .env

# Laravel APP Key
php artisan key:generate

# set your docker configuration
```

## Fazendo commit

Para fazer um commit, ele deve sempre começar com uma dessas labels para indicar o tipo de mudança, e a mensagem de commit de facto (Capitalizada) depois dos dois pontos.

- **chore**: _refatoração_ Uma mudança que não corrige um bug nem adiciona uma funcionalidade;
- **ci**: Uma mudança relacionada à integração contínua;
- **docs**: Uma mudança ou correção na documentação;
- **feat**: Uma nova funcionalidade;
- **fix**: Uma correção de bug;
- **test**: Uma mudança relacionada a testes.-
