# Mini E-Commerce API

A complete Laravel-based e-commerce API with authentication, products management, and orders system.

## 🚀 Features

### 1. User Authentication (Sanctum)
- User Registration
- Login/Logout
- Token-based Authentication
- Password Hashing
- Validation

### 2. Products Module
- CRUD Operations (Create, Read, Update, Delete)
- Image Upload
- Search by product name
- Filter by price range (min_price, max_price)
- Pagination
- Product Resource with proper formatting

### 3. Orders Module
- Create new orders with product arrays
- Automatic total price calculation
- Get user's orders
- Order items management
- Order status tracking

## 🛠️ Technologies Used

- **Backend**: Laravel 10.x
- **Authentication**: Laravel Sanctum
- **Database**: MySQL
- **API Resources**: Laravel API Resources
- **Architecture**: Repository Pattern + Service Layer
- **File Storage**: Local Storage with public linking

## 📁 Project Structure
app/
├── Http/
│ ├── Controllers/
│ │ ├── ProductController.php
│ │ ├── OrderController.php
│ │ └── AuthController.php
│ ├── Requests/
│ │ ├── StoreProductRequest.php
│ │ └── StoreOrderRequest.php
│ └── Resources/
│ ├── ProductResource.php
│ ├── OrderResource.php
│ └── OrderItemResource.php
├── Models/
│ ├── Product.php
│ ├── Order.php
│ └── OrderItem.php
├── Repositories/
│ ├── ProductRepository.php
│ └── OrderRepository.php
├── Services/
│ ├── ProductService.php
│ └── OrderService.php
└── Providers/
└── AppServiceProvider.php   

Available Endpoints
Authentication Endpoints
POST /api/register - User registration
POST /api/login - User login
POST /api/logout - User logout


Products Endpoints
GET /api/products - Get all products (with search & filter)
GET /api/products/{id} - Get single product
POST /api/products - Create new product (with image)
PUT /api/products/{id} - Update product
DELETE /api/products/{id} - Delete product
 
Orders Endpoints
GET /api/orders - Get user's orders
POST /api/orders - Create new order
