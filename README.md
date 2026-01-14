# SellNow – Engineering Refactor Manifesto

## Overview

SellNow is a legacy PHP-based digital marketplace provided as a **working prototype**, not a finished product.  
The goal of this task was **not feature completeness**, but to demonstrate how I approach:

- Inherited, imperfect codebases
- Architectural decision-making without a framework
- Security, maintainability, and scalability under time constraints

This repository reflects a **deliberate evolution** of the original system, not a rewrite.

---

## Guiding Principles

While refactoring this project, I followed these principles:

- **Evolution, not erasure** – Legacy code remains functional while new architecture is introduced gradually
- **Explicit over magic** – No hidden framework behavior
- **Security is a responsibility, not a feature**
- **Clear boundaries over clever abstractions**
- **Pragmatic prioritization** – Not everything is fixed, intentionally

---

## The Audit: What Was Inherited

The original codebase had several typical real-world issues:

### Architectural Issues

- Controllers handled HTTP, validation, business logic, and database access
- No clear separation between domain, application, and infrastructure concerns
- Tight coupling between controllers and concrete implementations

### Data & Validation

- Raw `$_POST` / `$_GET` usage across the codebase
- No explicit data contracts
- Implicit assumptions about request shape

### Security Risks

- Plain-text password storage
- No CSRF protection
- Session fixation risk after login
- Ad-hoc SQL usage without a consistent pattern

### Structural Problems

- Inconsistent namespaces
- No enforced coding standards
- Entry point (`index.php`) mixed bootstrapping, routing, and logic

These issues are **intentional**, and reflect real-world legacy systems.

---

## Priority Matrix: What I Fixed First (and Why)

Given the suggested 3-day limit, I prioritized **high-impact, foundational improvements**:

### 1. Architectural Boundaries (High Impact)

- Introduced clear layers:
  - HTTP
  - Application (Services, DTOs)
  - Domain (Entities, Interfaces)
  - Infrastructure (PDO implementations)
- Controllers were reduced to orchestration only

### 2. Security Fundamentals (Critical Risk)

- Password hashing using PHP-native APIs
- CSRF protection for all state-changing requests
- Session fixation prevention
- Centralized request handling

### 3. Front Controller Refactor

- Converted `public/index.php` into a proper Front Controller
- Centralized bootstrapping, routing, and security enforcement

### 4. Data Contracts

- Introduced DTOs to enforce predictable input
- Validation moved closer to data boundaries

---

## What I Intentionally Did NOT Do

To respect time constraints and realism, I intentionally did **not**:

- Fully rewrite all legacy controllers
- Introduce a full routing library
- Add comprehensive test coverage
- Normalize all templates or database schemas
- Implement advanced auth features (MFA, rate limiting)

These omissions are deliberate and documented.

---

## Architectural Overview

src/
├── Http/ # Request / Response abstractions
├── Controller/ # Thin controllers (new)
├── Application/ # Use cases / business logic
│ └── Cart/
│ └── Auth/
├── Domain/ # Core business entities & interfaces
│ └── Cart/
│ └── User/
├── Infrastructure/ # PDO implementations
│ └── Persistence/
├── Support/ # Cross-cutting concerns (CSRF)

Legacy code under `SellNow\` namespaces remains operational.

---

## Responsibility of Components

### Controllers

- Handle HTTP orchestration only
- No business rules
- No persistence logic

### Application Services

- Own business use cases (e.g. Add to Cart, Login)
- Enforce business rules
- Operate on DTOs and domain interfaces

### Domain

- Contains entities and contracts
- No knowledge of HTTP or persistence

### Infrastructure

- Concrete implementations (PDO)
- Easily replaceable via interfaces

---

## The Contract of Data

Data validity is enforced through:

- DTOs (e.g. `AddToCartDTO`)
- Constructor-level validation
- Centralized request handling

This ensures predictable data flow from HTTP → Domain.

---

## Security Improvements

### Password Handling

- Passwords are hashed using:
  - `password_hash()`
  - `password_verify()`
- No custom cryptography
- Automatic salting and algorithm upgrades

### CSRF Protection

- Session-bound CSRF tokens
- Cryptographically secure generation
- Explicit verification on all POST actions

### Session Security

- Session regeneration after login
- Reduced session fixation risk

Security logic is centralized and explicit.

---

## Front Controller

The `public/index.php` file was refactored into a proper Front Controller:

- Single entry point
- Centralized bootstrapping
- Explicit routing
- Centralized security enforcement
- Dependency wiring without a framework

This makes system behavior immediately understandable.

---

## Structural Scalability

The new structure allows:

- Adding new features without touching unrelated code
- Replacing infrastructure (e.g. database, payment) via interfaces
- Gradual migration of legacy controllers into the new architecture

Adding the 100th feature will not be exponentially harder than adding the 5th.

---

## Pragmatic Performance Considerations

While not aggressively optimized, the refactor:

- Avoids obvious N+1 query patterns
- Uses prepared statements
- Reduces redundant logic in controllers
- Keeps business logic centralized for easier profiling later

Performance optimizations were intentionally deferred until bottlenecks are proven.

---

## Trade-offs & Honest Reflection

Every architectural choice has a cost:

- Supporting dual namespaces (`SellNow\` and `App\`) adds complexity
- Manual dependency wiring is more verbose than a framework
- Some legacy imperfections remain visible

These trade-offs were accepted to prioritize **clarity, safety, and evolution** over perfection.

---

## Final Thoughts

This refactor does not aim to be a finished product.  
It aims to demonstrate **engineering judgment**.

If given more time, the next steps would be:

- Gradual migration of all legacy controllers
- Login throttling
- Domain-level validation
- Automated tests

What matters most is that the system now has a **clear architectural direction**.

---

**Author**  
Ruhul Amin  
Junior Software Engineer (Laravel / PHP)
