# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Skeleton for [Be Framework](https://be-framework.github.io/) applications. Namespace `Be\Skeleton\` is intended to be replaced with the app's own namespace.

For end-to-end guidance (project setup → design workflow → dev loop → debugging) see the [`be-skills`](https://github.com/be-framework/be-skills) Claude Code plugin (`be` and `be-semantic` skills).

## Commands

```bash
composer dev                    # MODULE=dev (default) — writes var/log/*.json, prints greeting
composer app                    # MODULE=app — production-style, no log
composer stree                  # @dev + render latest log as semantic tree
composer stree:full             # Same, verbose

# Direct invocation (BEAR.Sunday-style URI: <input>?<query>)
php bin/app.php                                          # default → "Hello World" with log
php bin/app.php 'hello?name=Alice'                       # custom name
MODULE=app php bin/app.php 'hello?name=Alice'            # production-style
php bin/app.php 'order?customerId=42&items[]=P1001'      # different Input + multi args

vendor/bin/phpunit              # All tests
vendor/bin/phpunit --filter testHello tests/HelloTest.php   # Single test
```

No lint/cs config is checked in. Global rule (`composer cs-fix`, `composer test`) still applies before commits/pushes.

## Architecture

Be Framework expresses an app as a **metamorphic pipeline**: an `Input` object declares what it will "become" via `#[Be([TargetClass::class])]`; `Be\Framework\Becoming` walks this chain, validating semantic variables at each step and injecting dependencies until a terminal (`Final\*`) object is produced.

Directory layout maps to roles in that pipeline:

- `src/Input/` — entry DTOs with `#[Be(...)]` declaring the next stage.
- `src/Final/` — terminal objects. Constructor params marked `#[Input]` come from the previous stage's public properties; `#[Inject]` params come from Ray.Di bindings.
- `src/Semantic/` — **semantic variable validators**. `BeModule` is installed with this namespace (`AppModule::configure`). For every constructor parameter name encountered during metamorphosis, the framework looks up a class of that name (e.g. `$name` → `Semantic\Name`) and calls its `#[Validate]` method. Missing validators raise a framework notice — `Semantic\Being` exists as a no-op because `$being` is a framework-level branching convention, not an app-specific variable.
- `src/Reason/` — injectable services referenced by `Final\*` via `#[Inject]` (bound in `AppModule`).
- `src/Exception/` — domain exceptions thrown from validators. Prefer specific exceptions (e.g. `EmptyNameException`) over generic `\*Exception`.
- `src/Module/` — Ray.Di modules. `AppModule` installs `BeModule` + app bindings; `DevModule` installs `AppModule` and rebinds `BecomingInterface` to `DevBecoming`, which wraps `Becoming` and writes a semantic log on every invocation (consumed by `stree`).
- `src/Becoming/DevBecoming.php` — the dev-mode wrapper; logs to `var/log/` via `Koriym\SemanticLogger\DevLogger`.
- `bin/app.php` — single universal entry. The CLI argument is parsed as a BEAR.Sunday-style URI (`<input>?<key>=<value>&...`), so `'hello?name=Alice'` resolves to `new HelloInput(name: 'Alice')`. The `MODULE` env var selects which Module class to instantiate (`MODULE=dev` → `DevModule`, default `dev`); production deployments must set `MODULE=app` (or another module) explicitly. `parse_url` + `parse_str` handle multi-args and arrays naturally, mirroring HTTP query semantics — the same syntax could one day route via a `be://` URI scheme from BEAR.Sunday.

The Module choice is the only configuration: every Module is just a Ray.Di module composition. `AppModule` is the production wiring; `DevModule` installs `AppModule` then rebinds `BecomingInterface` to `DevBecoming` (the wrapper that writes a semantic log to `var/log/<timestamp>.json` on every invocation, consumed by `stree`). Adding a new mode (test, staging, ...) is a matter of dropping `XxxModule` into `src/Module/` and invoking with `MODULE=xxx`.

When adding a new stage: create the next class (usually in `Final/` if terminal, otherwise an intermediate with its own `#[Be(...)]`), add a `Semantic\<VarName>` validator for each new constructor parameter name that isn't already registered, and bind any `#[Inject]` services in `AppModule`.

## Conventions specific to this repo

- PHP classes are `final readonly` where the framework pattern allows (Input, Final, DTOs).
- Tests construct `Injector(new AppModule())` + `new Becoming($injector, 'Be\Skeleton\Semantic')` directly — do not mock the framework; swap modules instead (see `DevModule` for the pattern).
- `var/log/*` is git-ignored except `.gitkeep`; `stree` reads the newest JSON there.
