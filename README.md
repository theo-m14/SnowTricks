# SnowTricks

## Description

SnowTricks is a Symfony project developed as part of the OpenClassrooms training program. It is a website dedicated to showcasing and discussing snowboard tricks. Users can explore various tricks, their descriptions, and engage in discussions with other snowboard enthusiasts.

## Installation

To get started with SnowTricks, follow these simple steps:

### Prerequisites

- Composer is required for managing the project's dependencies.

## Version used on devlopment

- Php 8.1
- Yarn 1.22.19
- Node 18.17.1
- MySQL 8.0.31

### Installation Steps

1. **Clone the repository:**

    ```bash
    git clone https://github.com/theo-m14/SnowTricks.git
    ```

2. **Navigate to the project directory:**

    ```bash
    cd SnowTricks
    ```

3. **Install dependencies:**

    ```bash
    composer install
    ```

4. **Configure the database:**

    - Update the database URL in the `.env` file.
      
    - Update the MAILER_DSN in order to send mail

5. **Create the database schema:**

    ```bash
    php bin/console doctrine:schema:create
    ```

6. **Load initial data (optional):**

    Import on your database the file .sql located in folder 'initial_data'

7. **Run the Symfony development server:**

    ```bash
    php bin/console server:run
    ```

8. **Compile assets**
    
    ```bash
    yarn watch
    ```

9. **Access the application in your browser:**

    Open [http://127.0.0.1:8000](http://127.0.0.1:8000) in your preferred browser.
