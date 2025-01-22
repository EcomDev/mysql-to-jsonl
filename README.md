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


## Installation

### Composer

```bash
composer require ecomdev/mysql2jsonl
```

### Phar

Download pre-bundled application from [Releases](https://github.com/EcomDev/mysql-to-jsonl/releases)


## 📜 License

This project is licensed under the MIT License.

See the [LICENSE](LICENSE) file for more details.
