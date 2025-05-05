# Customer Sync Backend

Este é o backend do projeto **Customer Sync**, desenvolvido com o framework Laravel e configurado para rodar em um ambiente Docker.

---

## 📦 Pré-requisitos

Certifique-se de ter os seguintes softwares instalados em sua máquina:

- [Git](https://git-scm.com/)
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

---

## 🚀 Como configurar e rodar o projeto

### 1. Clone o repositório

Use o comando abaixo para clonar o repositório em sua máquina local:

```bash
git clone https://github.com/gilberto-dev-silva/customer-sync-backend.git
```
### 2. Acesse o projeto
```bash
cd customer-sync-backend
```

### 2. Execute os seguintes comandos
```bash
docker-compose build
docker-compose up
```
### 3. Acesse o projeto
Após os contêineres estarem rodando, o backend estará disponível em:
```bash
http://localhost:8000
```
## 📚 Rotas da API

### Endpoints disponíveis

| Método  | Rota                  | Descrição                          |
|---------|-----------------------|------------------------------------|
| GET     | `/api/customers`      | Lista todos os clientes.          |
| POST    | `/api/customers`      | Cria um novo cliente.             |
| GET     | `/api/customers/{id}` | Retorna os detalhes de um cliente.|
| PUT     | `/api/customers/{id}` | Atualiza os dados de um cliente.  |
| DELETE  | `/api/customers/{id}` | Remove um cliente.                |
