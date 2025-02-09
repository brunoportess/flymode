
<div style="display: flex; justify-content: center;">
<img loading="lazy" width="70" src="https://www.onfly.com.br/wp-content/uploads/2024/07/onfly-logo-azul-01-768x307-1.webp" />
</div>

# Onfly Mode

Projeto de API em Laravel para gerencimanto de ordens de voo

## Configuração de ambiente
Para executar localmente a aplicação, primeiramente certifique-se de possuir as ferramentas necessárias
- Ambiente Windows com WSL2 ativo ou algum OS Linux
  > OBS: Em caso de uso do WSL2, para maior performance, recomendo acessar a distribuição e realizar a instalação como ambiente Linux e evitar o uso do "Docker Desktop" devido ao seu alto consumo de hardware
- Instalar o Git [Clique aqui](https://git-scm.com/downloads)
- Instalar o Docker [Clique aqui](https://docs.docker.com/engine/install)



## Instalação

Primeiramente, após ter o git instalado, realize o clone do repositório

```
  git clone https://github.com/brunoportess/flymode.git
```

_OBS: Caso não tenha tornado o usuário docker como admin, é possível que seja necessário que você utilize o "sudo" no início dos comandos_

Em seguida, possuindo o docker instalado e configurado corretamente, acesse a pasta do projeto e "suba" a aplicação
```bash
  cd flymode
  docker compose up --build
``` 
_O parâmetro "--build" é utilizado para recriar as imagens e não aproveitar o cache_  

Após gerar os containers e o processo de build finalizar, será preciso iniciar os containers com os comandos abaixo
```bash
  docker start laravel_nginx
  docker start laravel_app
  docker start laravel_mariadb  
  ```  

Agora vamos acessar o container e executar os comandos base para gerar algumas informações na base de dados  
Acesse o container com o comando abaixo
```bash
  docker exec -it laravel_app bash
  ```
Agora vamos executar as migrations para criar nossas tabelas
  ```bash
  php artisan migrate
  ```
Em seguida, vamos executar as seeds para gerar alguns dados de demonstração
  ```bash
  php artisan db:seed
```


## Acessando a aplicação
Url para acessar a aplicação no browser ou utilizar as requisições
```
  http://localhost:8000
``` 
Dados acessar a base de dados
```
  HOST: localhost
  PORTA: 3307
  USUARIO: onfly
  SENHA: onfly
``` 
## Executando os testes unitários
Primeiramente, acesse o container para que possa executar os comandos da aplicação
```bash
  docker exec -it laravel_app bash
```

Para executar os testes unitários, execute o comando a seguir

```bash
  php artisan test --filter FlightOrderControllerTest
```
> A opção --filter especifica o teste a ser executado


## Rotas da API

Para acessar a documentação de rotas é necessário que você possua o postman instalado  
Para instalar o Postman  [Clique aqui](https://www.postman.com/downloads)  
_A Collection possui variáveis configuradas para base URL das requisições e script na requisição de login para automaticamente inserir o token nas requisições_

### Documentação da API
[Clique aqui](https://github.com/brunoportess/flymode/blob/main/Onfly.postman_collection.json) para acessar a collection do postman e fazer a importação

## Como usar
- Primeiro utilize o endpoint para registrar um novo usuário com EMAIL VÁLIDO
- Em seguida, faça a autenticação com este usuário gerado
- Depois crie uma ordem.  
_Recomendo informar as datas não próximas a data atual devido as regras contidas no backend para bloquear cancelamento em cim a da hora de uma ordem aprovada_
- Agora para poder alterar o status da ordem é necessário utilizar um usuário diferente. Para isso, faça a autenticação com o email "ceo@onfly.com" e senha "onfly"  
_Este email e senha foi gerado ao executar o comando de db:seed na etapa de instalação/configuração. Caso queira criar e utilizar um novo usuário também irá funcionar_
- Utilize o endpoint de "Atualizar Status Ordem" para alterar o status da nova ordem para "aprovado" ou "cancelado"  
_Assim que a ordem receber atualização de status, um email de notificação de alteração do status será enviado para o email do usuário que criou a ordem_
## Observações técnicas

### Controller de busca com filtros _SearchFlightOrderController_

Controller gerada para responsabilidade única pensando em criação de microsserviço próprio juntamente com o uso de dados em cache para alta performance em resposta de utilização para relatórios ou fins similares

### Controller de atualização de status de ordem _StatusFlightOrderController_
Assim como a controller anterior para otimização de usabilidade, a controller de atualização de status poderia ter uma finalidade similar para evitar sobrecarga de atualização da base de dados e trabalhar em conjunto com serviço de fila e pacotes para balancear a carga na base de dados

### Validações de dados
Foi utilizado a criação de Request's customizados para validação dos dados recebidos pelos endpoints

### Tratativa de regras para atualização de status
Na camada service "FlightOrderService" no método "changeStatusValidate" foi feito implementações de checagem para que ao aprovar ou cancelar uma ordem seja verificado situações de diferença de tempo para aprovação e ordem de atualização de status, por exemplo, rejeitar a aprovação de uma ordem cancelada

