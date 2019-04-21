# CQRS homework
This repository contains an example implementation of domain rules validation in CQRS application.

##### Bussines requirements
```
Company:
  - domain  (unique)
  - user limit
  - user counter
  
User:
  - name
  - email (unique)
  - phone (optional)
  
Actions:
  - create company 
  - create user:
      - allow only if company domain exists
      - send SMS confirmation if phone provided
      - increase user counter
```

## Installation

```bash
composer install

bin/console doctrine:schema:create
bin/console server:run
```

## Example usage

Use `request.http ` file with example requests.