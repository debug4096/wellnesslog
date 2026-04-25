# WellnessLog

<p align="center">
<a href="https://www.php.net"><img src="https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php&logoColor=white" alt="PHP Version"></a>
<a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel&logoColor=white" alt="Laravel Version"></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-green" alt="License"></a>
</p>

Mental health and wellness tracking REST API built with Laravel 12

## Tech Stack

- PHP 8.2+, Laravel 12
- Laravel Sanctum (token-based authentication)
- MySQL / PostgreSQL
- Redis (queue driver)
- Docker

## Architecture

- Service Layer with interfaces and dependency injection
- Policy-based authorization
- Form Request validation
- API Resources for response transformation
- Enum-backed value objects (mood, energy, medication units/frequency)

## API Endpoints

### Authentication
| Method | Endpoint      | Description          |
|--------|---------------|----------------------|
| POST   | /api/register | Register a new user  |
| POST   | /api/login    | Login                |
| POST   | /api/logout   | Logout               |
| GET    | /api/me       | Current user profile |

### Daily Entries
| Method | Endpoint             | Description                                     |
|--------|----------------------|-------------------------------------------------|
| GET    | /api/entries         | List entries (filterable by date_from, date_to) |
| POST   | /api/entries         | Create entry                                    |
| GET    | /api/entries/{entry} | Show entry                                      |
| PUT    | /api/entries/{entry} | Update entry                                    |
| DELETE | /api/entries/{entry} | Delete entry                                    |

### Medications
| Method | Endpoint                      | Description                      |
|--------|-------------------------------|----------------------------------|
| GET    | /api/medications              | List medications                 |
| POST   | /api/medications              | Create medication                |
| GET    | /api/medications/{medication} | Show medication                  |
| PUT    | /api/medications/{medication} | Update medication                |
| DELETE | /api/medications/{medication} | Archive medication (soft delete) |

### Medication Logs
| Method | Endpoint                           | Description              |
|--------|------------------------------------|--------------------------|
| GET    | /api/medications/{medication}/logs | List logs for medication |
| POST   | /api/medications/{medication}/logs | Log medication intake    |

### Statistics
| Method | Endpoint        | Description                                       |
|--------|-----------------|---------------------------------------------------|
| GET    | /api/statistics | Median mood, energy, sleep (filterable by period) |

## Getting Started

```bash
git clone git@github.com:debug4096/wellnesslog.git
cd wellnesslog
cp .env.example .env
docker-compose up -d
php artisan migrate --seed
```

## Running Tests

```bash
php artisan test
```

## License

This project is open-sourced under the [MIT license](LICENSE).
