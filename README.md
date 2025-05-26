# Inventory Management System

A comprehensive inventory management system built with Laravel and AdminLTE. This application helps businesses track inventory levels, orders, sales, and deliveries with role-based access control for different user types.

## Features

- **Dashboard Analytics**
    - Admin: Full system overview with detailed analytics
    - Manager: Business metrics with trend analysis
    - Staff: Daily operational data focused on immediate tasks

- **Inventory Management**
    - Complete product catalog with detailed information
    - Stock level tracking with low stock alerts
    - Categorization system for better organization
    - Barcode/SKU support for quick identification

- **Transaction Tracking**
    - Record incoming inventory (purchases, returns)
    - Track outgoing inventory (sales, transfers)
    - Document transaction history with timestamps
    - Monitor inventory movements by date ranges

- **User Management**
    - Role-based access control (Admin, Manager, Staff)
    - Permission-based features and views
    - User activity logging and auditing
    - Secure authentication system

- **Reporting**
    - Stock level reports
    - Incoming/outgoing inventory reports
    - Transaction history reports
    - Custom date range filtering
    - Export capabilities (CSV, PDF)

- **Category Management**
    - Create, edit, delete product categories
    - Associate products with multiple categories
    - Track items by category for better inventory analysis

## Tech Stack

- **Backend**: Laravel (PHP)
- **Frontend**: Bootstrap, jQuery, AdminLTE
- **Database**: MySQL
- **Authentication**: Laravel's built-in auth with custom middleware
- **UI Components**: [jeroennoten/laravel-adminlte](https://github.com/jeroennoten/Laravel-AdminLTE)

## Installation

1. Clone the repository
   ```
   git clone https://github.com/ariexx/inventory-management.git
   ```

2. Install dependencies
   ```
   composer install
   npm install
   ```

3. Copy environment file and configure database
   ```
   cp .env.example .env
   ```

4. Generate application key
   ```
   php artisan key:generate
   ```

5. Run database migrations and seed
   ```
   php artisan migrate --seed
   ```

6. Start development server
   ```
   php artisan serve
   ```

## User Roles

1. **Admin**
    - Has full access to all features
    - Can manage users, permissions and system settings
    - Can create, update, and delete all data

2. **Manager**
    - Has access to reporting and analytics
    - Can view and edit most data
    - Cannot delete critical information
    - Can manage inventory levels

3. **Staff**
    - Has limited access to view inventory
    - Can record incoming and outgoing inventory
    - Has access to daily operational data only

## How to Contribute

1. Fork the repository
2. Create a feature branch (`git checkout -b feature-branch`)
3. Commit your changes (`git commit -m 'Add new feature'`)
4. Push to the branch (`git push origin feature-branch`)
5. Open a Pull Request

### Contribution Guidelines

- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation for any changes
- Keep pull requests focused on a single feature or bug fix
- Clearly describe the purpose of your pull request

## Improvement Areas

- Add barcode scanning functionality
- Implement email notifications for low stock
- Create mobile-responsive views for warehouse staff
- Add supplier management system
- Implement advanced search capabilities
- Add multi-language support
- Create API endpoints for integration with other systems
- Improve data visualization with charts and graphs

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

- [Laravel](https://laravel.com/) - The web framework used
- [AdminLTE](https://adminlte.io/) - Dashboard template
- [jeroennoten/laravel-adminlte](https://github.com/jeroennoten/Laravel-AdminLTE) - Laravel AdminLTE integration
