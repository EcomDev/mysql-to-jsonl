# 🚀 Blazingly fast **mysql2jsonl** 🚀

## Purpose:
The `mysql2jsonl` tool provides an efficient way to export data from a MySQL database to JSONL (JSON Lines) files and to import data back into a database from these files. 
It is highly configurable and optimized for performance with concurrency and batching in mind!


## **Key Features**:
- Export and Import Commands
    - `mysql2jsonl export`: Exports MySQL database table data into JSONL files.
    - `mysql2jsonl import`: Imports JSONL files back into the MySQL database tables.

- Complex rules for excluding/including tables into the export
- No compromise on performance!
- JSONSchema for configuration validation, no more guessing when editing config file.

## Configuration file example

If you want to export / import data from mysql server reachable via `db` hostname 
and exclude tables that match indexation patterns, you configuration file will look like this:
```json
{
  "$schema": "https://raw.githubusercontent.com/EcomDev/mysql-to-jsonl/main/schema.json",
  "config": {
    "connection": {
      "host": "db",
      "database": "magento",
      "user": "magento",
      "password": "magento"
    },
    "excludeTables": [
      {
        "endsWith": "_cl"
      },
      {
        "contains": "_tmp_"
      },
      {
        "contains": "_index_"
      },
      {
        "endsWith": "_index"
      },
      {
        "endsWith": "_replica"
      },
      {
        "regexp": "/^inventory_stock_\\d+$/"
      }
    ],
    "concurrency": 12
  }
}
```

Notice the `$schema` in as the first element? Specifying it like this in your config files 
allows your favorite IDE's to auto-complete and validate configuration properties.

## Installation

### Phar (Recommended)

Download pre-bundled application from [Releases](https://github.com/EcomDev/mysql-to-jsonl/releases)

### Composer

```bash
composer require ecomdev/mysql2jsonl
```


## 📜 License

This project is licensed under the MIT License.

See the [LICENSE](LICENSE) file for more details.
