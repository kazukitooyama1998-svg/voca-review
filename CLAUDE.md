# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project status

This is a **fresh Laravel 13 skeleton** — only the default `users`/`cache`/`jobs` migrations and the stock `welcome` view exist so far. The actual application (vocabulary/grammar CRUD, review mode, study log) has not been built yet. The full requirements spec lives in Japanese at the bottom of `README.md` (below the standard Laravel boilerplate) — **read it before implementing any feature**, since it is the source of truth for data model, routes, and UI, not the code.

### What VocaReview is

A single-user (no auth/login) English self-study app replacing a spreadsheet, for managing and reviewing vocabulary/phrases and grammar points. Everything lives on one top page: header (app name + study stats), search/filter area, registration form, list table, pagination, footer.

Per the spec (README.md §5–7), the eventual data model is three resources, each with plain RESTful CRUD:

- **Vocabulary/phrase** (単語・フレーズ): word/phrase, part of speech (dropdown: Noun/Verb/Adjective/Adverb/Pronoun/Preposition/Conjunction/Interjection), meaning, English example sentence, Japanese example sentence, learned/not-learned flag.
- **Grammar** (文法): name, explanation, English example, Japanese example, learned/not-learned flag.
- **Study log** (学習記録): date, review count — written once 100 items have been reviewed in a day; drives "today's review count / streak days / total reviews / last study date" stats shown in the header.

List views need search, sort, pagination, edit, delete, and a filter by learned status (all / learned / not learned). Design direction: blue main color, green accent, cream background, dark-gray text, generous whitespace, minimal JS.

## Commands

Standard Laravel/Composer/npm workflow — no custom build tooling.

```bash
# Install
composer install
npm install

# Local dev (serves app + queue listener + log tailer + vite, all at once)
composer run dev

# Run the full test suite (clears config cache first)
composer run test
# equivalent to:
php artisan test

# Run a single test file / method
php artisan test tests/Feature/ExampleTest.php
php artisan test --filter=test_method_name

# Lint / format (Laravel Pint)
vendor/bin/pint          # fix
vendor/bin/pint --test   # check only

# Frontend build
npm run dev      # vite dev server
npm run build     # production build

# Migrations
php artisan migrate
php artisan make:migration create_xxx_table
php artisan make:model Xxx -mfc   # model + migration + factory + controller
```

## Architecture notes

- **Stack**: Laravel 13 + Blade + Tailwind CSS v4 (via `@tailwindcss/vite`) + Vite. JavaScript is meant to be kept minimal per the spec — prefer server-rendered Blade/Eloquent over a JS framework or API layer.
- **Database**: app runs on **MySQL** (`.env`: `DB_CONNECTION=mysql`, db name `voca-review`), but the **test suite runs on in-memory SQLite** (`phpunit.xml` overrides `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`). Migrations must stay MySQL/SQLite-compatible.
- **No auth**: the spec explicitly excludes login/registration. Don't add Breeze/Fortify/Jetstream or gate routes behind auth middleware.
- **Routing**: build resources with Laravel's standard RESTful `Route::resource` conventions (per README §6) — one resource each for vocabulary/phrase, grammar, and study log.
- Controllers/Models don't exist yet beyond the default `App\Models\User` — when adding the vocabulary/grammar/study-log features, create the Migration → Model → Controller → routes → Blade views in that order, matching the dev schedule in README §12.

---

## Development Guidelines

This project is also for learning Laravel.

When implementing features:

- Explain why the implementation is chosen.
- Follow Laravel best practices and conventions.
- Prefer simple and beginner-friendly implementations.
- Avoid unnecessary packages unless explicitly requested.
- Keep controllers as thin as possible.
- Use Eloquent ORM.
- Use Blade components when appropriate.
- Reuse existing code instead of duplicating it.
- Do not modify unrelated files unless necessary.
- Explain any new Laravel feature used.

---

## UI Guidelines

The application should have a simple and clean interface focused on studying.

### Design Preferences

- Blue as the primary color.
- Green as the accent color.
- Cream background.
- Dark gray text.
- Plenty of whitespace.
- Rounded cards and inputs.
- Responsive layout for desktop and mobile.
- All features should be accessible from a single top page.

---

## Development Workflow

Unless otherwise instructed, implement features in the following order:

1. Migration
2. Model
3. Factory (if needed)
4. Controller
5. Route
6. Blade View
7. Validation
8. Styling
9. Testing (if applicable)

Explain each step briefly before generating code.

---

## Coding Style

- Use meaningful variable and method names.
- Keep methods short and readable.
- Follow Laravel naming conventions.
- Use RESTful Resource Controllers.
- Use Form Request validation when validation becomes complex.
- Avoid duplicated code.
- Prefer Eloquent relationships over raw SQL whenever appropriate.
- Add comments only when they improve readability.

---

## Teaching Mode

This repository is also a learning project.

When generating code:

- Explain Laravel concepts before writing code.
- Explain why the chosen implementation is recommended.
- Mention Laravel best practices.
- Prefer beginner-friendly implementations.
- Avoid overly complex design patterns unless explicitly requested.
- Assume the developer has beginner-to-intermediate Laravel knowledge.

---

## Git Commit Rules

Use Conventional Commits.

Examples:

feat: add vocabulary registration

feat: add grammar CRUD

fix: correct review counter

refactor: simplify vocabulary controller

style: improve mobile layout

docs: update README

---

## Response Style

When generating code:

- Explain which files were added or modified.
- Explain the purpose of each file.
- Generate complete code whenever possible.
- Avoid placeholder implementations.
- Keep explanations concise and educational.

---

## Project-specific Rules

This application follows the requirements described in README.md.

### Resources

The application consists of three resources:

- Vocabulary / Phrase
- Grammar
- Study Log

### Vocabulary Fields

- Word / Phrase
- Part of Speech
- Meaning
- Example Sentence (English)
- Example Sentence (Japanese)
- Memorized (boolean)

### Grammar Fields

- Grammar Name
- Explanation
- Example Sentence (English)
- Example Sentence (Japanese)
- Memorized (boolean)

### Study Log Fields

- Review Date
- Review Count

### General Rules

- No authentication.
- No user management.
- Use Blade instead of SPA frameworks.
- Keep JavaScript to a minimum.
- Use Tailwind CSS for styling.
- Follow RESTful conventions.
- Use Eloquent ORM.
- Design for both desktop and mobile.

### UI Concept

The application should feel like a clean digital notebook for English study.

Prioritize:

- Simplicity
- Readability
- Fast navigation
- Easy review workflow
- Comfortable long-term usage
- Minimal visual clutter
