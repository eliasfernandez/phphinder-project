# PHPhinder Project


[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)  

---


## What is it?  
[PHPhinder](https://github.com/eliasfernandez/phphinder) is an open-source, lightweight, and modular search engine designed for PHP applications. It provides powerful search capabilities with a focus on simplicity, speed, and extensibility.

The [PHPhinder bundle](https://github.com/eliasfernandez/phphinder-bundle) connects PHPhinder with Symfony to improve the searchability of Doctrine entities. 

Finally this project is a working implementation of the search engine in a real Symfony project. Is an example on how you can use the Search Engine. 

---

## Installation  

1. Download the current code

```bash
git clone https://github.com/eliasfernandez/phphinder-project.git
```

2. Install the packages via composer

```bash
composer install
```

3. Configure the storage

Under `config/packages/phphinder.yaml` configure the storage type (`dbal` or `json`) and the connection string:

```yaml
parameters:
  phphinder.storage: 'dbal'
  phphinder.name: 'pgsql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=16&charset=utf8'
  phphinder.auto_sync: true
```

4. Load some books:

```bash
docker compose up -d
bin/console app:load-books
```

5. Start the server. Install the [symfony-cli](https://symfony.com/download) if you didn't install it before.

```bash
symfony serve
```


6. Go to the search url http://localhost:8000/search

7. Enjoy! 
