# Changelog

## [1.4.1](https://github.com/EcomDev/mysql-to-jsonl/compare/1.4.1...1.4.1) (2025-01-27)


### Miscellaneous Chores

* release 1.4.1 ([d37f024](https://github.com/EcomDev/mysql-to-jsonl/commit/d37f0248df8fe8ac13afbdee2cc6bf61a88e3580))

## [1.4.1](https://github.com/EcomDev/mysql-to-jsonl/compare/v1.4.0...1.4.1) (2025-01-27)


### Bug Fixes

* release artifact upload process ([4b78594](https://github.com/EcomDev/mysql-to-jsonl/commit/4b785944c569186066c11d5a3c6cf24d6ca6075d))
* **release-please:** remove release type from arguments ([8e0dc32](https://github.com/EcomDev/mysql-to-jsonl/commit/8e0dc321c3b4fc40d3649e0889dbe8d26c2b5d86))


### Miscellaneous Chores

* add composer cache to style checks ([b52862e](https://github.com/EcomDev/mysql-to-jsonl/commit/b52862e0dcc70014a20d3225651be41f0b2ad57a))
* add debuging info ([cea3458](https://github.com/EcomDev/mysql-to-jsonl/commit/cea3458c248b0850d6213de2881d5c4ef6f8021c))
* create artifact during php package build ([40067c8](https://github.com/EcomDev/mysql-to-jsonl/commit/40067c8a33c8732d6b2d488488a9a70b39261e87))
* move release output to release-please job ([6c39f64](https://github.com/EcomDev/mysql-to-jsonl/commit/6c39f647a17cf561185e31b4e2998824a5312829))
* **phar:** add bz2 to build ([c75f318](https://github.com/EcomDev/mysql-to-jsonl/commit/c75f3183c234af52880a4cd8dff874227a25d672))
* **phar:** optimize size of artifact build ([8f8a2c9](https://github.com/EcomDev/mysql-to-jsonl/commit/8f8a2c9dbd8b4651926f9f9d14ba7f076eb6bb7c))
* proper version string in app binary ([97a3752](https://github.com/EcomDev/mysql-to-jsonl/commit/97a375246e3f1a0dcb3adfcc8e91f2f4d31a79c4))
* **tests:** add composer cache for build process ([b940f93](https://github.com/EcomDev/mysql-to-jsonl/commit/b940f935e233a72879e3eb39f313324f3d779ad5))

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
