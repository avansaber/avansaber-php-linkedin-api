# Contributing

Thanks for your interest in contributing! Please:

- Open an issue to discuss significant changes before submitting a PR.
- Write tests for new features or bugfixes.
- Follow PSR-12 coding standards.
- Keep public APIs typed and documented.
- Update `README.md` and `CHANGELOG.md` as needed.

## Local setup

- PHP 8.1+
- Composer

Install deps and run tests:

```bash
composer install
composer test
```

Static analysis:

```bash
vendor/bin/phpstan analyse -c phpstan.neon.dist
```

## Code of Conduct

All contributors are expected to uphold a respectful and welcoming environment.
