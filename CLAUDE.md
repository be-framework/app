# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Skeleton for [Be Framework](https://be-framework.github.io/) applications. Namespace `Be\Skeleton\` is intended to be replaced with the app's own namespace.

## Commands

```bash
php bin/app.php                 # Run the app (production path via AppModule)
composer smoke                  # Run DevModule path (writes var/log/*.json)
composer stree                  # Smoke + render latest log as semantic tree
composer stree:full             # Same, verbose
composer stree:html             # Render to becoming.html
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
- `bin/app.php` — production entry. Installs `AppModule` and instantiates `Becoming` directly, passing the Semantic namespace as a constructor argument. Catches `SemanticVariableException` and prints a localized (`ja`) message. Produces user-visible output (the greeting).
- `bin/smoke.php` — dev entry used by `composer smoke`/`stree`. Installs `DevModule` and resolves `BecomingInterface` through DI, so `DevBecoming` runs instead of raw `Becoming` and a semantic log is written to `var/log/`. No stdout output, no try/catch — the log (consumed by `stree`) is the artifact.

The two entries look asymmetric on purpose: `app.php` demonstrates the minimal manual wiring a framework user needs; `smoke.php` shows the DI-resolved variant that module swapping relies on. Both ultimately invoke the same metamorphosis; the only real difference is which Module is installed.

When adding a new stage: create the next class (usually in `Final/` if terminal, otherwise an intermediate with its own `#[Be(...)]`), add a `Semantic\<VarName>` validator for each new constructor parameter name that isn't already registered, and bind any `#[Inject]` services in `AppModule`.

## Conventions specific to this repo

- PHP classes are `final readonly` where the framework pattern allows (Input, Final, DTOs).
- Tests construct `Injector(new AppModule())` + `new Becoming($injector, 'Be\Skeleton\Semantic')` directly — do not mock the framework; swap modules instead (see `DevModule` for the pattern).
- `var/log/*` is git-ignored except `.gitkeep`; `stree` reads the newest JSON there.
