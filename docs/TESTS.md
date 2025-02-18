# Tests 🧪

## Table of Contents 📚

- [Environment Configuration](#environment-configuration-) 🛠️
- [Database Setup](#database-setup-) 🗃️
- [Usage](#usage-) 🔧
  - [Running Tests](#running-tests-) 🧪
  
## Environment Configuration 🛠️

You can add environment variables to the `.env.testing` file to configure the testing environment.
  
```bash
cp .env.example .env.testing
```

## Database Setup 🗃️

<details>
<summary>From Adminer Container</summary>

1. Access the Adminer container at [http://localhost:8080](http://localhost:8080).
2. Use the following credentials:
   - System: `MySQL`
   - Server: `db`
   - Username: `root`
   - Password: `root`
   - Database: `laravel-fp`
> Or use your own database configuration specified in the `.env.testing` file and the `docker-compose.yml` file.
3. Create a new database named `laravel-fp-test`.
  - interclassification: `utf8mb4_0900_ai_ci`

</details>

<details>
<summary>From Docker Container</summary>

The following file `docker-compose.testing.yml` is used to configure the testing environment.

Start the Docker containers with the following command:
```bash
docker-compose --env-file .env.testing -f docker-compose.testing.yml up -d
```

Replace the environment variables `BD_PORT` and `DB_DATABASE` in the `.env.testing` file with the value `3307`.

```bash
DB_PORT=3307
DB_DATABASE=laravel-fp-test
```

</details>

## Usage 🔧

### Running Tests 🧪

```bash
php artisan test
```
See results [-> HERE](https://app.warp.dev/block/1BKVVC7IXMZUXcVOulsUht)