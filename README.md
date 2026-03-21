# 🚀 Visionary Verse

### Agile Project Management System with DSS & AI Chatbot

![PHP](https://img.shields.io/badge/PHP-8.x-blue)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange)
![MVC](https://img.shields.io/badge/Architecture-Custom%20MVC-green)
![Status](https://img.shields.io/badge/Project-Completed-success)

---

## 📌 Overview

**Visionary Verse** is a web-based **Management System** designed to streamline project workflows, improve collaboration, and support better decision-making.

It integrates:

* 📊 **Decision Support System (DSS)**
* 🤖 **AI Chatbot**
* 🔔 **Real-time Notification System**

Built using a **custom PHP MVC framework**, the system supports multiple user roles and provides a centralized platform for managing projects, tasks, and client interactions.

---

## 🎯 Key Features

### 👤 Role-Based System

* **Admin**

  * Manage clients, projects, and tasks
  * Access DSS insights
* **Staff**

  * View assigned tasks
  * Update task status
* **Client**

  * View projects
  * Approve/reject deliverables

---

### 📁 Core Modules

* 🔐 Authentication System (Session-based)
* 📂 Client Management
* 📊 Project Management
* ✅ Task Management
* 📊 Client Priority Score (DSS Features)
* 📦 Deliverables & Approval Workflow
* 🔔 Real-time Notification System (AJAX polling)
* 📈 Reports

---

### 🧠 Decision Support System (DSS)

* Project delay risk detection
* Client priority scoring
* Data-driven insights for decision-making

---

### 🤖 AI Chatbot

* Role-based responses
* Handles queries like:

  * "Show my tasks"
  * "Project status"
  * "Pending approvals"

---

## 🏗️ System Architecture

```
app/
 ├── controllers/
 ├── models/
 ├── services/
 ├── views/
 ├── core/
public/
 ├── assets/
 ├── index.php
config/
db/
```

* Custom **MVC Architecture**
* PDO for database interaction
* Clean separation of concerns

---

## 🛠️ Tech Stack

| Layer       | Technology            |
| ----------- | --------------------- |
| Backend     | PHP (Custom MVC)      |
| Database    | MySQL                 |
| Frontend    | HTML, CSS, JavaScript |
| Async       | AJAX                  |
| Environment | WAMP                  |

---

## ⚙️ Installation

### 1. Clone the repository

```bash
git clone https://github.com/your-username/visionary-verse.git
```

### 2. Move to WAMP directory

```
C:\wamp64\www\
```

### 3. Import database

* Open **phpMyAdmin**
* Import SQL file from `/db/`

### 4. Configure database

Update:

```
config/config.php
```

### 5. Run project

```
http://localhost/pvv/public
```

---

## 🔐 Default Roles

| Role   | Access                   |
| ------ | ------------------------ |
| Admin  | Full system access       |
| Staff  | Assigned tasks only      |
| Client | Own projects & approvals |

---

## 🔄 System Workflow

1. Admin creates clients and projects
2. Tasks are assigned to staff
3. Staff updates progress
4. Deliverables are submitted
5. Clients approve/reject
6. DSS analyzes risks
7. Chatbot assists users

---

## 📊 Project Highlights

* ✔ Full-stack MVC implementation
* ✔ Role-based access control
* ✔ DSS integration
* ✔ AI chatbot system
* ✔ Real-time notifications
* ✔ Clean modular architecture

---

## 🚧 Future Enhancements

* 📱 Mobile application
* ⚡ Real-time WebSocket updates
* 🔐 Advanced security features
* 📊 Advanced analytics dashboard

---

## 👨‍💻 Author

**Ken**
Final Year Computer Science Student

---

## 📄 License

MIT License

Copyright (c) 2026 Visionary Verse Project

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

---

## ⭐ Acknowledgements

* Project Supervisor
* Academic Institution
* Open-source community

---

### 🌟 If you like this project, give it a star!
