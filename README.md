# YourSurprise Shopping Laravel Application

A Laravel 13 application with a SQLite database and OpenAI-powered frontend integration, built with Vite and Tailwind CSS.

---

## Requirements

- PHP 8.3+
- Composer
- Node.js 18+ and npm
- SQLite (bundled with PHP on most systems)

---

## Installation & Setup

### 1. Clone the repository

```bash
git clone <repository-url>
cd <project-folder>
```

### 2. Run the setup script

The project includes a one-command setup that installs all dependencies, copies the environment file, generates an app key, runs migrations, and builds frontend assets:

```bash
composer run setup
```

This is equivalent to running the following steps manually:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate 
```

### 3. Configure your environment

Open the generated `.env` file and update any values as needed. The most important ones for local development:

```env
APP_NAME=Laravel
APP_ENV=local
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=null (needs to be the absolute path to the .db)
OPENAI_API_KEY=null (needs to be the key of the openAi API key)
```

> See the [Database section](#database-connection) below for connecting the provided `.db` file instead of the default.
> See the [OpenAI integration](#openai-integration) below for connecting the OpenAI API to the frontend.

---

## Database Connection

The project uses **SQLite** as its database driver. A pre-populated database file (`yoursurprise_test.db`) is provided.

### Using the provided `.db` file

**Step 1** — Copy the database file into the `database/` directory:

```bash
cp yoursurprise_test.db database/yoursurprise_test.db
```

**Step 2** — Update your `.env` file to point to this file using an absolute path:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/your/project/database/yoursurprise_test.db
```

Replace `/absolute/path/to/your/project/` with the actual path to your project root. On Linux/macOS you can get this by running `pwd` inside the project folder.

**Step 3** — Clear the config cache so Laravel picks up the change:

```bash
php artisan config:clear
```

**Step 4** — Verify the connection:

```bash
php artisan tinker
> DB::connection()->getPdo();
```

If the connection is successful, you will see a `PDO` object returned without errors.

> **Note:** No migrations need to be run against the provided `.db` file — it already contains the schema and seed data.

---

## Running the Application


To compile frontend assets for production:

```bash
php artisan serve
```

The application will be available at [http://127.0.0.1:8000/](http://127.0.0.1:8000/).


### Order ID lookup

The main interface accepts an **Order ID** as input. Enter a numeric order ID (eg. 1) into the input field and submit the form. The application will look up the corresponding order record from the SQLite database.

### OpenAI integration

When an order is retrieved, the application sends the order data to the **OpenAI API** to generate an AI-powered response or summary based on the order details.

To enable this feature, add your OpenAI API key to the `.env` file:

```env
OPENAI_API_KEY=sk-...
```

> You can obtain an API key from [https://platform.openai.com/api-keys](https://platform.openai.com/api-keys).

After adding the key, clear the config cache:

```bash
php artisan config:clear
```

The integration will then be active automatically when an order ID is submitted through the frontend.

---


## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 13, PHP 8.3 |
| Database | SQLite |
| Frontend build | Vite 8, Tailwind CSS 4 |
| AI integration | OpenAI API |
| Auth scaffolding | Laravel Sanctum |
