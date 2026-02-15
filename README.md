# 💰 Jovly POS - Point of Sale System

> Complete point-of-sale solution with inventory management, sales tracking, and role-based access control

[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat&logo=php)](https://www.php.net/)
[![Yii2](https://img.shields.io/badge/Yii2-2.0-green?style=flat)](https://www.yiiframework.com/)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

---

## 📖 About

**Jovly POS** is a web-based point-of-sale system designed for retail businesses. It provides comprehensive sales management, inventory tracking, and multi-user support with role-based permissions.

### Key Features

- 💼 **Role-Based Access** - Admin, Manager, and Cashier roles with specific permissions
- 🛒 **Sales Processing** - Fast checkout with barcode scanning support
- 📦 **Inventory Management** - Real-time stock tracking and low-stock alerts
- 🧾 **Receipt Generation** - Thermal printer-ready receipts
- 📊 **Purchase Management** - Track supplier purchases and returns
- 📈 **Reports & Analytics** - Sales reports, inventory reports, and performance tracking

---

## ✨ Features by Role

### 👔 Admin
- Full system access and configuration
- User management (create/edit/delete users)
- View all reports and analytics
- Manage inventory and suppliers
- Access to all modules

### 📋 Manager
- Sales oversight and management
- Inventory management
- Purchase and return processing
- View reports and analytics
- Cannot manage users

### 💵 Cashier
- Process sales transactions
- Generate receipts
- View product information
- Basic inventory lookup
- Limited to cashier functions

---

## 🚀 Core Modules

### Sales Management
- Quick product search and selection
- Multiple payment methods support
- Real-time price calculation
- Discount and tax handling
- Receipt printing (thermal printer optimized)

### Inventory Management
- Product catalog with categories
- Stock level tracking
- Low stock alerts
- Product variants support
- Barcode generation and scanning

### Purchase Management
- Supplier management
- Purchase order creation
- Stock receiving
- Return processing
- Purchase history tracking

### Reporting
- Daily/weekly/monthly sales reports
- Inventory status reports
- Cashier performance reports
- Product movement tracking
- Profit/loss analysis

---

## 🛠️ Tech Stack

**Backend**
- PHP (Yii2 Framework)
- MySQL Database
- MVC Architecture
- Session-based authentication

**Frontend**
- HTML5/CSS3, Bootstrap 5
- JavaScript (jQuery)
- AJAX for real-time updates
- Responsive design

**Features**
- Role-based access control (RBAC)
- Real-time stock synchronization
- Thermal printer integration
- Barcode support
- Multi-user sessions

---

## 💡 Use Cases

Perfect for:
- 🏪 Retail stores
- 🍽️ Restaurants and cafes
- 🛍️ Small to medium businesses
- 📚 Bookstores
- 👕 Clothing stores
- 🔧 Hardware shops

---

## 🗺️ Project Status

### ✅ Completed Features
- [x] User authentication with role-based access
- [x] Sales processing and receipt generation
- [x] Inventory management system
- [x] Purchase and return management
- [x] Real-time stock synchronization
- [x] Thermal printer-ready receipts
- [x] Product catalog with categories
- [x] Basic reporting module

### 🚧 In Development
- [ ] Advanced analytics dashboard
- [ ] Multi-store support
- [ ] Payment gateway integration

### 📅 Planned Features
- [ ] Mobile app for managers
- [ ] Customer loyalty program
- [ ] Email receipt option
- [ ] Cloud backup integration
- [ ] API for third-party integrations

---

## 📂 Project Structure

```
jovly-pos/
├── common/
│   ├── config/          # Shared configuration
│   └── models/          # Core models (User, Product, Sale, etc.)
├── frontend/
│   ├── controllers/     # Application logic
│   ├── models/          # Frontend models
│   ├── views/           # User interface
│   │   ├── sale/        # Sales module
│   │   ├── inventory/   # Inventory management
│   │   ├── purchase/    # Purchase management
│   │   └── report/      # Reports and analytics
│   └── web/             # Public directory
├── backend/             # Admin panel
└── console/             # CLI commands
```

---

## 🔐 Security Features

- ✅ Role-based access control (RBAC)
- ✅ Session management
- ✅ SQL injection prevention
- ✅ CSRF protection
- ✅ Secure password hashing
- ✅ Activity logging

---

## 📞 Connect With Me

**Abbos Jovliev** - Backend Developer

📧 [jovliyevabbosjon@gmail.com](mailto:jovliyevabbosjon@gmail.com)  
💼 [LinkedIn](https://www.linkedin.com/in/abbos-jovliev/)

---

## 🎯 Project Goals

Built as a freelance project to provide small and medium businesses with an affordable, reliable point-of-sale solution. Focus on simplicity, reliability, and ease of use.

---

## 📄 License

MIT License - feel free to learn from this project!

---

## 🎥 Demo

**See Jovly POS in action:**

> https://github.com/user-attachments/assets/51a3216e-12be-4a76-9f57-8c0a506017bf

*Watch the complete POS workflow - from login to sales processing and receipt generation*

---

**Built for efficient retail management** 💼