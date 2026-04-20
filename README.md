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

## Namespace

Change `Be\Skeleton` to your own namespace using AI tools or find-and-replace.

## Learn More

- [Be Framework Documentation](https://be-framework.github.io/)
- [be-skills](https://github.com/be-framework/be-skills) — Claude Code skills for building Be Framework apps end-to-end (project setup, design workflow, dev loop, debugging).
- [be-patterns](https://github.com/be-framework/be-patterns) — eight runnable pattern demos (Linear, Diamond, Branching, Cascade Diamond, Complex Convergence, …).
