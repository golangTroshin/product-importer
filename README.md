# 📦 CSV Product Feed Importer

A PHP console application built with Symfony Console and Docker, designed to import structured product data (currently from `.csv`) into a MySQL database. Supports row range selection and an extensible parser architecture for future formats (e.g. XML, JSON).

---

## ✨ Features

- Import data from `.csv` files into a `products` MySQL table
- Specify row range using `--start` and `--end`
- File type is auto-detected from extension
- Exception handling for file and DB errors
- Built using:
  - Symfony Console
  - League\Csv
  - Doctrine DBAL
  - PHP-DI for dependency injection
- Ready-to-run in Docker
- Fully tested with PHPUnit

---

## 🛠 Prerequisites

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- (Optional) [DBeaver](https://dbeaver.io/) or another DB client to browse the database

---

## 🚀 Quick Start

### 1. Unzip the archive

```bash
unzip csv-importer.zip
cd csv-importer
````

---

### 2. Start the containers

```bash
docker-compose up --build -d
```

This starts:

* PHP 8.3 CLI container
* MySQL 8.0 container with database `csv_feed`

---

### 3. Run Import

To import a CSV file with optional row range:

```bash
docker-compose run --rm php import:file --path=feed.csv --start=1 --end=4
```

* `--start`: optional, defaults to 0
* `--end`: optional, will import until EOF

---

## 🧪 Run Tests

Execute PHPUnit test suite:

```bash
docker-compose run --rm --entrypoint="" php vendor/bin/phpunit tests
```

---

## 📦 Dependency Injection

The project uses **[PHP-DI](https://php-di.org/)** for clean and testable service injection. The console app is bootstrapped through:

```php
require 'bootstrap/bootstrap.php';
```

This creates a DI container and injects all services (`ImportService`, `ParserFactory`, etc.) into your commands.

---

## 🏗 Extending the Parser

The parser logic is abstracted via `ParserInterface`. To support new formats:

1. Implement a new class (e.g. `XmlParser`)
2. Add it to the `ParserFactory::createFromExtension()` method

This keeps the `ImportService` clean and extensible.

---

## 🗃 Database Schema

Table used: `products`

```sql
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gtin VARCHAR(255),
    language VARCHAR(10),
    title VARCHAR(255),
    picture TEXT,
    description TEXT,
    price DECIMAL(10,2),
    stock INT
);
```

---

## 🧭 Database Access (via DBeaver)

* **Host**: `localhost`
* **Port**: `3307` (check your local override)
* **User**: `root`
* **Password**: `secret`
* **Database**: `csv_feed`

☝ You may need to enable *Public Key Retrieval* for MySQL 8 in your client.

---

## 📁 File Structure (Simplified)

```
.
├── bin/
│   └── console           # Entry point for the CLI
├── bootstrap/
│   └── bootstrap.php     # Loads container & console
├── src/
│   ├── Command/
│   ├── Parser/
│   ├── Service/
│   └── ...
├── tests/
│   ├── fixtures/
│   └── ...
├── docker/
├── docker-compose.yml
├── Dockerfile
└── .env
```
