# CLAUDE.md

Project-specific guidance for Claude Code in this repository.

## Project

Template for [Be Framework](https://be-framework.github.io/) applications. Namespace `Be\App\` is the app namespace used by the generated project.

For Be Framework methodology (project setup, design workflow, patterns, debugging) see the [`be-skills`](https://github.com/be-framework/be-skills) Claude Code plugin (`be` and `be-semantic` skills). This file covers only what is specific to **this** skeleton.

## Commands

```bash
composer dev                    # semantic log only — writes var/log/<timestamp>.json
composer profile                # semantic profiling — writes var/log/<timestamp>.json
composer stree                  # render the latest log as a semantic tree
composer stree:full             # semantic full tree with full props and close.profile details

# Direct invocation: `bin/be.php` takes one BEAR.Sunday-style URI argument
#   <input>?<key>=<value>&...
php bin/be.php                                          # default → 'hello?name=World'
php bin/be.php 'hello?name=Alice'
php bin/be.php 'order?customerId=42&items[]=P1001'

vendor/bin/phpunit
```

## Skeleton-specific wiring

- **`bin/be.php`** — single universal entry. Parses the CLI argument with `parse_url` + `parse_str`, maps the path to `Be\App\Input\<Ucfirst>Input` and spreads the query as named constructor args. Module is resolved via the `MODULE` env var (default `dev`) → `Be\App\Module\<Ucfirst>Module`. The same URI shape mirrors what a future `be://` scheme could route from BEAR.Sunday.
- **`src/Module/DevModule.php`** — installs `AppModule` then rebinds `BecomingInterface` to `DevBecoming`. Adding a new mode is a matter of dropping `XxxModule` into `src/Module/` and invoking with `MODULE=xxx`.
- **`src/Becoming/DevBecoming.php`** — wraps `Becoming` and writes a semantic log to `var/log/<timestamp>.json` from a `finally` block, so failed pipelines are captured too (consumed by `composer stree`).
- **`var/log/*`** is git-ignored except `.gitkeep`; `stree` reads the newest JSON there.

## Tests

Tests construct `Injector(new AppModule())` then `getInstance(Becoming::class)` (or build `new Becoming($injector, 'Be\App\Semantic')` directly when minimal wiring is the point). Don't mock the framework — swap modules instead, the way `DevModule` does for the dev loop.
