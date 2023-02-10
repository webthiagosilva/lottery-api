## Lottery API
#

* uma api simples em laravel que simula uma loteria:

### execução:
    1 renomear .env.example para .env
    2 execute na raiz do projeto "docker compose up"
    3 depois do build execute docker compose exec app composer install
    
    atenção para serviços rodando na porta 8000 e 3306 pois a api usa estas portas

### rotas:
    POST -> http://localhost:8000/api/create-ticket
        {
            "name": "Usuario Sortudo",
            "numbers": [1,2,3,4,5,60]
        }

    GET -> http://localhost:8000/api/ticket/{ticket}
        {
            "data": {
                "ticket_code": "0e7d8e64-3562-4686-8f69-181b9916b524",
                "name": "Usuario Sortudo",
                "your_numbers": [1, 2, 3, 4, 5, 60],
                "machine_numbers": [3, 12, 21, 34, 50, 52],
                "winner": false,
                "message": "You lost!"
            }
        }

### para executar os testes:
    docker compose exec app php ./vendor/bin/phpunit
