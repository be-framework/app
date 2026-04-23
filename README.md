# Be Framework App

Project template for [Be Framework](https://be-framework.github.io/).

## Getting Started

```bash
composer create-project be-framework/skeleton MyProject --stability dev
cd MyProject
```

## Usage

```bash
composer dev      # semantic log only → var/log/<timestamp>.json
composer profile  # semantic profiling → var/log/<timestamp>.json
composer stree    # render the latest log as a tree
composer stree:full # render the latest log as a semantic full tree

# direct invocation (BEAR.Sunday-style URI)
php bin/be.php
php bin/be.php 'hello?name=Alice'
php bin/be.php 'order?customerId=42&items[]=P1001'
```

Run `composer dev` or `composer profile` before `composer stree` if you need a fresh log. See `CLAUDE.md` for the dev loop, `MODULE` env var, and the URI invocation convention.

## Namespace

The generated app uses the `Be\App` namespace by default. You can rename it to your own namespace with AI tools or find-and-replace.

## Learn More

- [Be Framework Documentation](https://be-framework.github.io/)
- [Directory Layout](https://be-framework.github.io/manuals/1.0/en/convention/directory-layout.html) — what each `src/<dir>/` slot is for.
- [be-skills](https://github.com/be-framework/be-skills) — Claude Code skills for building Be Framework apps end-to-end (project setup, design workflow, dev loop, debugging).
- [be-patterns](https://github.com/be-framework/be-patterns) — eight runnable pattern demos (Linear, Diamond, Branching, Cascade Diamond, Complex Convergence, …).
