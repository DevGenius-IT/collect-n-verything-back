# Installation 💾

## Table of Contents 📚

- [Install dependencies](#install-dependencies-) 📦
- [Environment Configuration](#environment-configuration-) 🛠️
- [Database Setup](#database-setup-) 🗃️
- [Run Migrations](#run-migrations-) 🚀
- [Run Seeders](#run-seeders-) 🌱
- [Run the Application](#run-the-application-) 🚀
- [Testing](#testing-) 🧪

## Install dependencies 📦

```bash
composer install
```

```bash
Installing dependencies from lock file (including require-dev)
Verifying lock file contents can be installed on current platform.

Package operations: 108 installs, 0 updates, 0 removals

78 packages you are using are looking for funding.
```

## Environment Configuration 🛠️

Create a `.env` file by copying the `.env.example` file:

```bash
cp .env.example .env
```

replace the fileds values `<something>` with your own values.

## Database Setup 🗃️

Create a new database from Docker:

```bash
docker-compose up -d
```

See results [-> HERE](https://app.warp.dev/block/x9ZWHGApzL2r6imTe5Evp1)

The actual [`docker-compose.yml`](https://github.com/DevGenius-IT/collect-n-verything-back/blob/main/docker-compose.yml) provides a MySQL database and an Adminer container for database management.

### Default Container Configuration

| Container     | Hostname         | Port | Username | Password | Database   |
| ------------- | ---------------- | ---- | -------- | -------- | ---------- |
| MySQL         | db               | 3306 | root     | root     | laravel-cv |
| Adminer       | adminer          | 8080 | root     | root     |            |

> [!NOTE]
> If want to use your own database, you can read the [Laravel documentation](https://laravel.com/docs/11.x/database#configuration) to configure your database.

> [!TIP]
> I currently use [TablePlus](https://tableplus.com/) to manage my databases.
> You can also use [MySQL Workbench](https://www.mysql.com/products/workbench/).

## Run Migrations 🚀

```bash
php artisan migrate
```

See results [-> HERE](https://app.warp.dev/block/xrWhqqo4YjntdMpr9gFKpo)

## Run Seeders 🌱

```bash
php artisan db:seed
```

See results [-> HERE](https://app.warp.dev/block/fTGFxmUOZLOY4NpsoBTxAp)

> [!NOTE]
> The migration step can be run the default seeders that come with Laravel. If you want to run the seeders, you can run the following command:
>
> ```bash
> php artisan migrate:fresh --seed
> ```
>
> **-- or --**
>
> ```bash
> php artisan migrate --seed
> ```
>
> See results [-> HERE](https://app.warp.dev/block/fTGFxmUOZLOY4NpsoBTxAp)

## Run the Application 🚀

```bash
php artisan serve
```

See results [-> HERE](https://app.warp.dev/block/TYXu9pD37iAHoY9GiT6bZt)

## Testing 🧪

```bash
php artisan test
```

See results [-> HERE](https://app.warp.dev/block/1BKVVC7IXMZUXcVOulsUht)

See more details on the [Testing](https://github.com/DevGenius-IT/collect-n-verything-back/blob/main/docs/TESTS.md) documentation page.

## Contributing 🤝

Please read the [contributing guide](https://github.com/DevGenius-IT/collect-n-verything-back/blob/main/CONTRIBUTING.md).

## Project 📂

We have organized our work into a single project to streamline development and ensure clarity. You can follow the progress and contribute through the link below:

- [API - Collect & Verything Project](https://github.com/orgs/DevGenius-IT/projects/2)

---

<p align="center">
	Copyright &copy; 2024-present <a href="https://github.com/DevGenius-IT" target="_blank">@DevGenius-IT</a>
</p>

<p align="center">
	<a href="https://github.com/DevGenius-IT/collect-n-verything-back/blob/main/LICENSE.md"><img src="https://img.shields.io/static/v1.svg?style=for-the-badge&label=License&message=MIT&logoColor=d9e0ee&colorA=363a4f&colorB=b7bdf8"/></a>
</p>
