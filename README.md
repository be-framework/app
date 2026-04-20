# Be Framework Skeleton

Project skeleton for [Be Framework](https://be-framework.github.io/).

## Getting Started

```bash
composer create-project be-framework/skeleton MyProject
cd MyProject
```

## Usage

```bash
composer dev      # run with semantic logging → var/log/<timestamp>.json
composer stree    # render the latest log as a tree
composer app      # production-style run (no log)

# direct invocation (BEAR.Sunday-style URI)
php bin/app.php 'hello?name=Alice'
MODULE=app php bin/app.php 'order?customerId=42&items[]=P1001'
```

See `CLAUDE.md` for the dev loop, `MODULE` env var, and the URI invocation convention.

## Directory layout

The skeleton ships ten `src/<dir>/` slots — three are intentionally empty until you need them. Each row links to the matching chapter of the [Be Framework manual](https://be-framework.github.io/manuals/1.0/en/).

| dir | role | manual |
|---|---|---|
| `src/Input/`      | Pipeline entry. Declares `#[Be([Target::class])]`.                                  | [Input Classes](https://be-framework.github.io/manuals/1.0/en/02-input-classes.html) |
| `src/Final/`      | Terminus. Receives `#[Input]` data + `#[Inject]` services.                          | [Final Objects](https://be-framework.github.io/manuals/1.0/en/04-final-objects.html) |
| `src/Semantic/`   | Semantic variables (validators). Class name = parameter name (camelCase).           | [Semantic Variables](https://be-framework.github.io/manuals/1.0/en/06-semantic-variables.html) |
| `src/Exception/`  | Semantic-validation exceptions with `#[Message]` for i18n.                          | [Error Handling](https://be-framework.github.io/manuals/1.0/en/09-error-handling.html) |
| `src/Reason/`     | "What makes existence possible" — Entity, Media (Command/Query), policies, guards.  | [Reason Layer](https://be-framework.github.io/manuals/1.0/en/08-reason-layer.html) |
| `src/Module/`     | Ray.Di modules. `MODULE=<name>` env switches the active module.                     | (skeleton-specific — see `CLAUDE.md`) |
| `src/Becoming/`   | Framework wiring layer. Not user code.                                              | [Becoming](https://be-framework.github.io/manuals/1.0/en/04a-becoming.html) |
| `src/Being/`      | *(empty)* Branching intermediate with `$being` discriminator + `#[Be([FinalA, ...])]`. | [Being Classes](https://be-framework.github.io/manuals/1.0/en/03-being-classes.html) |
| `src/LogContext/` | *(empty)* Semantic-log event classes attached to `Been`.                            | [Semantic Logging](https://be-framework.github.io/manuals/1.0/en/10-semantic-logging.html) |
| `src/Moment/`     | *(empty)* Diamond parts — `implements MomentInterface`, `be()` realizes potential.  | [Metamorphosis Patterns](https://be-framework.github.io/manuals/1.0/en/05-metamorphosis-patterns.html) |

## Namespace

Change `Be\Skeleton` to your own namespace using AI tools or find-and-replace.

## Learn More

- [Be Framework Documentation](https://be-framework.github.io/)
- [be-skills](https://github.com/be-framework/be-skills) — Claude Code skills for building Be Framework apps end-to-end (project setup, design workflow, dev loop, debugging).
- [be-patterns](https://github.com/be-framework/be-patterns) — eight runnable pattern demos (Linear, Diamond, Branching, Cascade Diamond, Complex Convergence, …).

---

[日本語版はこちら](./README.ja.md)
