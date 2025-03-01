# Usage 🔧

## Table of Contents 📚

- [API Documentation](#api-documentation-) 📖
- [API Endpoints](#api-endpoints-) 📡
		- [Authentication](#authentication-) 🔐
		- [Admin](#admin-) 🛡️
				- [Users](#users-) 👤
				- [Roles](#roles-) 🎭
- [Postman](#postman-) 📬

## API Documentation 📖

The API documentation is available at the following link:

- [API Documentation](https://documenter.getpostman.com/view/22240109/2sAYBREDeP)

## API Endpoints 📡

### Authentication 🔐

| Name           | Method  | Path                             | Parameters                                                                        | Headers                         | Description                     |
| -------------- | ------- | -------------------------------- | --------------------------------------------------------------------------------- | ------------------------------- | ------------------------------- |
| SignUp         | 🟡 POST | `/auth/signup`                   | `firstname`, `lastname`, `username`, `email`, `password`, `password_confirmation` |                                 | Register a new user             |
| SignIn         | 🟡 POST | `/auth/signin`                   | `email`, `password`                                                               |                                 | Login a user                    |
| SignOut        | 🟢 GET  | `/auth/signout`                  |                                                                                   | `Authorization: Bearer {token}` | Logout a user                   |
| Verify         | 🟢 GET  | `/auth/verify`                   |                                                                                   | `Authorization: Bearer {token}` | Verify and refresh user's token |
| ForgotPassword | 🟡 POST | `/auth/forgot-password/{method}` | `identifier`                                                                      |                                 | Send a password reset email     |
| ResetPassword  | 🟡 POST | `/auth/reset-password/{token}`   | `password`, `password_confirmation`                                               |                                 | Reset a user's password         |

### Admin 🛡️

#### Users 👤

| Name      | Method    | Path                     | Parameters                                                                                                                                                                         | Headers  | Description            |
| --------- | --------- | ------------------------ | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | -------- | ---------------------- |
| Index     | 🟢 GET    | `/admin/users`           | `limit`, `page`, `order`, `orderBy`, `trash`                                                                                                                                       | `fields` | Get all users          |
| Show      | 🟢 GET    | `/admin/users/{id}`      |                                                                                                                                                                                    | `fields` | Get a user by ID       |
| Store     | 🟡 POST   | `/admin/users`           | `firstname`, `lastname`, `username`, `email`, `password`, `password_confirmation`, `roles`, `phone_number `, `has_newsletter`, `address_id`      |          | Create a new user      |
| Update    | 🔵 PUT    | `/admin/users/{id}`      | `firstname`, `lastname`, `username`, `email`, `roles`, `password`, `phone_number`, `has_newsletter`, `address_id`                          |          | Update a user by ID    |
| Destroy   | 🔴 DELETE | `/admin/users`           | `ids`, `force`                                                                                                                                                                     |          | Delete multiple users  |
| Restore   | 🟣 PATCH  | `/admin/users/restore`   | `ids`                                                                                                                                                                              |          | Restore multiple users |
| Duplicate | 🟣 PATCH  | `/admin/users/duplicate` | [`duplicate_from` and user fields]                                                                                                                                                 |          | Duplicate a user       |

#### Roles 🎭

| Name    | Method    | Path                   | Parameters                                   | Headers  | Description            |
| ------- | --------- | ---------------------- | -------------------------------------------- | -------- | ---------------------- |
| Index   | 🟢 GET    | `/admin/roles`         | `limit`, `page`, `order`, `orderBy`, `trash` | `fields` | Get all roles          |
| Show    | 🟢 GET    | `/admin/roles/{id}`    |                                              | `fields` | Get a role by ID       |

## Postman 📬

[![Go to Collection](https://run.pstmn.io/button.svg)](https://github.com/DevGenius-IT/collect-n-verything-back/blob/main/docs/postman-workspace)

You can see the API documentation in Postman by clicking on the button above.

> [!TIP]
> You can also import the collection and environement directly ->
>
> HERE: [Collect & Verything API Collection](https://github.com/DevGenius-IT/collect-n-verything-back/blob/main/docs/postman-workspace)

## Contributing 🤝

Please read
the [contributing guide](https://github.com/DevGenius-IT/collect-n-verything-back/blob/main/CONTRIBUTING.md).

## Project 📂

We have organized our work into a single project to streamline development and ensure clarity. You can follow the
progress and contribute through the link below:

- [API - Collect & Verything Project](https://github.com/orgs/DevGenius-IT/projects/2)

---

<p align="center">
	Copyright &copy; 2024-present <a href="https://github.com/DevGenius-IT" target="_blank">@DevGenius-IT</a>
</p>

<p align="center">
	<a href="https://github.com/DevGenius-IT/collect-n-verything-back/blob/main/LICENSE.md"><img src="https://img.shields.io/static/v1.svg?style=for-the-badge&label=License&message=MIT&logoColor=d9e0ee&colorA=363a4f&colorB=b7bdf8"/></a>
</p>
