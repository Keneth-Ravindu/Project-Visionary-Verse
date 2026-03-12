# Development Notes

## Project Architecture

This project follows the MVC (Model-View-Controller) pattern:

- **Models** (`app/models/`): Database interaction and business logic
- **Views** (`app/views/`): UI templates and presentation
- **Controllers** (`app/controllers/`): Request handling and routing

## Directory Structure

```
app/
├── controllers/    # Application controllers
├── core/          # Core framework files
├── models/        # Data models
└── views/         # View templates

config/            # Configuration files
public/            # Public accessible files
└── assets/       # CSS, JS, and images
```

## Database Setup

1. Create a MySQL database
2. Import `SQL_QUERIES.sql`
3. Update credentials in `config/database.php`

## Key Features

- Role-based authentication (Admin, Staff, Client)
- Task management system
- Project tracking
- Decision support system
- Real-time notifications
- Client management
- Report generation

## Development Tips

- Use prepared statements for database queries
- Follow the existing code structure
- Test with different user roles
- Clear cache when making core changes
