# Changelog

## [1.4.0](https://github.com/EcomDev/mysql-to-jsonl/compare/v1.3.0...v1.4.0) (2025-01-26)


### Features

* cleanup of table data is now an external service ([d0053dc](https://github.com/EcomDev/mysql-to-jsonl/commit/d0053dc39c9fad9bc14554effbba75e110071201))


### Bug Fixes

* add bigger delta for table row count ([2ba2a88](https://github.com/EcomDev/mysql-to-jsonl/commit/2ba2a885b0a022ea42460cc68d7c825c5b47cc0a))


### Miscellaneous Chores

* **bin:** auto-update version string in binary ([ddbfee5](https://github.com/EcomDev/mysql-to-jsonl/commit/ddbfee5f1e94662be5d2f4dd76d5b950a9d2f6bf))
* **build:** hopefully fixed phar release upload ([915c19c](https://github.com/EcomDev/mysql-to-jsonl/commit/915c19c828e19d16b06707c80555910a51912a62))
* **docs:** add an example of configuration JSON ([9a2c138](https://github.com/EcomDev/mysql-to-jsonl/commit/9a2c138091c0c525c34fd54e3ad98a833afdf126))

## [1.3.0](https://github.com/EcomDev/mysql-to-jsonl/compare/v1.2.0...v1.3.0) (2025-01-22)


### Features

* **build:** added Phar version to release pipeline ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **command:** added `mysql2jsonl export` command ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **command:** added `mysql2jsonl import` command ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `batchSize` setting ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `concurrency` settings ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `importMode` setting of `truncate` and `update` ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `includeTables` and `excludeTables` rules ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** created JSONSchema for configuration files ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* mysql2json import/export tool ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))


### Miscellaneous Chores

* **docs:** added README with basic info ([96b37c6](https://github.com/EcomDev/mysql-to-jsonl/commit/96b37c621ff02611d7bae9558f2e622e78de7824))
* **main:** release 1.0.0 ([0df49cb](https://github.com/EcomDev/mysql-to-jsonl/commit/0df49cb34f8f0455e39ab02fe9bb6c423801fd01))
* **main:** release 1.1.0 ([33b7236](https://github.com/EcomDev/mysql-to-jsonl/commit/33b723643f8eb77e32e51d752cc1c4ce37a391bb))
* **main:** release 1.2.0 ([04cb37c](https://github.com/EcomDev/mysql-to-jsonl/commit/04cb37c5a0b882c4808ee7ec6d3cf81c45c5db30))
* **main:** release 1.2.0  ([9a6f68b](https://github.com/EcomDev/mysql-to-jsonl/commit/9a6f68b27f0b07626ac94b6967556328cddacc3d))

## [1.2.0](https://github.com/EcomDev/mysql-to-jsonl/compare/v1.1.0...v1.2.0) (2025-01-22)


### Features

* **build:** added Phar version to release pipeline ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **command:** added `mysql2jsonl export` command ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **command:** added `mysql2jsonl import` command ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `batchSize` setting ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `concurrency` settings ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `importMode` setting of `truncate` and `update` ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `includeTables` and `excludeTables` rules ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** created JSONSchema for configuration files ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* mysql2json import/export tool ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))


### Miscellaneous Chores

* **docs:** added README with basic info ([96b37c6](https://github.com/EcomDev/mysql-to-jsonl/commit/96b37c621ff02611d7bae9558f2e622e78de7824))
* **main:** release 1.0.0 ([0df49cb](https://github.com/EcomDev/mysql-to-jsonl/commit/0df49cb34f8f0455e39ab02fe9bb6c423801fd01))
* **main:** release 1.1.0 ([33b7236](https://github.com/EcomDev/mysql-to-jsonl/commit/33b723643f8eb77e32e51d752cc1c4ce37a391bb))

## [1.1.0](https://github.com/EcomDev/mysql-to-jsonl/compare/v1.0.0...v1.1.0) (2025-01-22)


### Features

* **build:** added Phar version to release pipeline ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **command:** added `mysql2jsonl export` command ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **command:** added `mysql2jsonl import` command ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `batchSize` setting ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `concurrency` settings ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `importMode` setting of `truncate` and `update` ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `includeTables` and `excludeTables` rules ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** created JSONSchema for configuration files ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* mysql2json import/export tool ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))


### Miscellaneous Chores

* **docs:** added README with basic info ([96b37c6](https://github.com/EcomDev/mysql-to-jsonl/commit/96b37c621ff02611d7bae9558f2e622e78de7824))
* **main:** release 1.0.0 ([0df49cb](https://github.com/EcomDev/mysql-to-jsonl/commit/0df49cb34f8f0455e39ab02fe9bb6c423801fd01))

## 1.0.0 (2025-01-22)


### Features

* **build:** added Phar version to release pipeline ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **command:** added `mysql2jsonl export` command ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **command:** added `mysql2jsonl import` command ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `batchSize` setting ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `concurrency` settings ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `importMode` setting of `truncate` and `update` ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** added `includeTables` and `excludeTables` rules ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* **config:** created JSONSchema for configuration files ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
* mysql2json import/export tool ([127bda3](https://github.com/EcomDev/mysql-to-jsonl/commit/127bda3933a6c5be40eac89a5b2524d3ac7ebaab))
