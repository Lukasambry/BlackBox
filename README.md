# BlackBox

BlackBox is a web application built with Symfony and PHP that allows users to participate in a secret-sharing game. Users can submit secrets, vote on them, and view the results.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Features

- User authentication and authorization
- Secret submission and voting
- Real-time secret-sharing and voting results
- Admin panel for managing users, rooms, and secrets

## Installation

### Prerequisites

- Docker
- Docker Compose
- PHP 8.2
- Composer
- Symfony CLI
- Make 

### Steps

1. #### Clone the repository:
    ```sh
    git clone https://github.com/Lukasambry/BlackBox.git
    cd BlackBox
    ```
   
2. #### Create a `.env` file:
    ```sh
    cp .env.example .env 
   ```

3.  #### Add your OpenAI API key to the `.env` file:
    ```sh
    OPENAI_API_KEY=your-api-key
    ```

4. #### Initialize the project:
    ```sh
    make init
    ```
   * This command will build the Docker containers, 
   * Install the dependencies, 
   * Set up the database,
   * Migrate the database schema,
   * Load the fixtures,
   * Start the server,
   * And start the messenger worker.

5. #### Access the application at `http://localhost:8000`.

6. #### Access the mailer at `http://localhost:8025`.

7. #### There is 3 users created by default:
    - Admin: 
      - Email: admin@test.com
      - Password: admin
    - User:
      - Email: user@test.com 
      - Password: user
    - Banned User:
      - Email: banned@test.com
      - Password: banned


## Usage

### Running Tests

To run the tests, use the following command:
```sh
make test
```
### Running Lint

To run the linter, use the following command:
```sh
make lint
```
 * This command will list all the errors and warnings. If you want, you can fix them by running the following command:
 * ```sh
   make lint-fix
   ```
    * This command will fix all the errors and warnings that can be fixed automatically. The rest is up to you.

### Clear the logs

To clear the logs, use the following command:
```sh
php bin/console app:clean-log
```
 * This command will delete all the logs older than 7 days.

## Contributing

Contributions are what make the open-source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

## License

Distributed under the MIT License. See `LICENSE` for more information.

## Authors 

- [Lukas Ambry](https://github.com/Lukasambry/)
- [Florian Defay](https://github.com/florddev/)
- [Thami Marzak](https://github.com/ThamiEngineering)