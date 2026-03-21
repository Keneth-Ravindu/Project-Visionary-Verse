# 🚀 Visionary Verse Digital Marketing Agency Web Application Management System

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

### Authentication

<img width="1920" height="910" alt="1" src="https://github.com/user-attachments/assets/2b962f94-b53a-4b98-8aed-7e94e196ba1e" />

### Admin
  
<img width="1920" height="910" alt="2" src="https://github.com/user-attachments/assets/cfd291bb-ba4d-43fd-a9c1-52c6abd1d63f" />
<img width="1920" height="911" alt="3" src="https://github.com/user-attachments/assets/696966c3-c3dc-4cb8-84e7-63580c37e890" />
<img width="1920" height="910" alt="4" src="https://github.com/user-attachments/assets/62e910ef-fd7d-4a06-b277-9a8204e33955" />
<img width="1920" height="912" alt="5" src="https://github.com/user-attachments/assets/242e9969-aefd-4d3a-ba8d-203a277d86f1" />
<img width="1920" height="911" alt="6" src="https://github.com/user-attachments/assets/fac56780-8561-418f-b4ae-149484eac6ee" />
<img width="1920" height="913" alt="7" src="https://github.com/user-attachments/assets/9321fd2d-a332-4493-be3d-05e1c821c91b" />
<img width="1920" height="911" alt="8" src="https://github.com/user-attachments/assets/1690b189-3a13-44c2-9d81-1bd5f57d99b7" />
<img width="1920" height="911" alt="9" src="https://github.com/user-attachments/assets/e96c60a4-739f-41a8-bf85-53e6c231735b" />
<img width="1920" height="912" alt="10" src="https://github.com/user-attachments/assets/e16dbf38-ee32-4f28-8f8d-75ec381047b8" />

### Staff

<img width="1920" height="908" alt="11" src="https://github.com/user-attachments/assets/197863b4-adec-44b1-b900-e1f358d39f8c" />
<img width="1920" height="906" alt="12" src="https://github.com/user-attachments/assets/37ec2095-41b4-41e1-bbd3-faad2a8f250a" />
<img width="1920" height="913" alt="13" src="https://github.com/user-attachments/assets/daafe119-c200-4385-b783-6e3f47fc135b" />
<img width="1920" height="910" alt="14" src="https://github.com/user-attachments/assets/142e192f-1115-40b7-8539-f7afca777a2b" />
<img width="1920" height="913" alt="15" src="https://github.com/user-attachments/assets/3275d94f-846b-4afc-a6b6-69ba3828fd1e" />

### Client

<img width="1920" height="908" alt="16" src="https://github.com/user-attachments/assets/c69fcb40-2f0e-4f80-8dba-e04a12619a82" />
<img width="1920" height="910" alt="17" src="https://github.com/user-attachments/assets/e296a20b-3b16-4e71-b23a-6c3de67b91d9" />
<img width="1920" height="908" alt="18" src="https://github.com/user-attachments/assets/eae9aeaa-5a98-4c1b-8316-5783a53188e9" />
<img width="1920" height="910" alt="19" src="https://github.com/user-attachments/assets/3f586684-06b2-4ee1-9195-bc0122f60051" />
<img width="1920" height="911" alt="20" src="https://github.com/user-attachments/assets/b3062e6a-b475-47ce-8c6e-dd7b8327c607" />


---

### 📁 Core Modules

* 🔐 Role Based Authentication System (Admin, Staff and Client)
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

### 2. Rename the root file name to pvv instead project-visionary-verse

### 3. Move pvv to WAMP directory

```
C:\wamp64\www\
```

### 4. Import database

* Open **phpMyAdmin**
* Import SQL file from `/db/`

### 5. Configure database

Update:

```
config/config.php
```

### 6. Run project

```
http://localhost/pvv/public
```

---

## 🔐 Default Roles

| Role   | Access                   |
| ------ | ------------------------ |
| Admin  | Full system access       |
| Staff  | Assigned tasks only      |
| Client | Own projects & review approvals |

---

## 🔄 System Workflow

1. Admin creates clients and projects
2. Tasks are assigned to staff
3. Staff updates progress
4. Deliverables are submitted
5. Clients reviews deliverables
6. DSS analyzes risks
7. Chatbot assists users

---

## 📊 Project Highlights

* ✔ Full-stack MVC implementation
* ✔ Role-based access control
* ✔ DSS integration
* ✔ Chatbot system
* ✔ Real-time notifications
* ✔ Clean modular architecture

---

## 🚧 Future Enhancements

* 📱 Mobile application
* ⚡ Real-time WebSocket updates
* 🔐 Advanced security features
* 📊 Advanced analytics dashboard

---

## 👨‍💻 Authors

**Project Manager - K.K.R Poornima - 2433190**
**Quality Manager - R.G.D Rajapaksha - 2523676**
**Risk Manager - R.A.D.N Nivarthana - 2526224**
**Scheduling Manager - R.A.C.K Jayalath - 2433285**
**Startup Manager - B.A.D.R Berugoda - 2433414**

University of Bedfordshire (SLIIT CITY UNI) Final Year Computer Science Students to fulfill Agile Project Management Module

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
