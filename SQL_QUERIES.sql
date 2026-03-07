CREATE DATABASE IF NOT EXISTS visionary_verse CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

USE visionary_verse;

/* Tables */
-- 1. USERS TABLE
CREATE TABLE
    users (
        user_id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM ('admin', 'staff', 'client') NOT NULL,
        full_name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_email (email),
        INDEX idx_role (role)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 2. CLIENTS TABLE
CREATE TABLE
    clients (
        client_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        company VARCHAR(255),
        phone VARCHAR(50),
        status ENUM ('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE SET NULL,
        INDEX idx_status (status),
        INDEX idx_name (name)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 3. PROJECTS TABLE
CREATE TABLE
    projects (
        project_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        client_id INT NOT NULL,
        service ENUM (
            'SEO',
            'Ads',
            'Social Media',
            'Web Design',
            'Content',
            'Other'
        ) NOT NULL,
        status ENUM ('To Do', 'In Progress', 'Review', 'Completed') DEFAULT 'To Do',
        due_date DATE,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (client_id) REFERENCES clients (client_id) ON DELETE CASCADE,
        INDEX idx_status (status),
        INDEX idx_service (service),
        INDEX idx_due_date (due_date)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 4. TASKS TABLE
CREATE TABLE
    tasks (
        task_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        project_id INT NOT NULL,
        assignee_id INT,
        priority ENUM ('Low', 'Medium', 'High') DEFAULT 'Medium',
        status ENUM ('To Do', 'In Progress', 'Review', 'Done') DEFAULT 'To Do',
        deadline DATE,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (project_id) REFERENCES projects (project_id) ON DELETE CASCADE,
        FOREIGN KEY (assignee_id) REFERENCES users (user_id) ON DELETE SET NULL,
        INDEX idx_status (status),
        INDEX idx_priority (priority),
        INDEX idx_deadline (deadline)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 5. DELIVERABLES / APPROVALS TABLE
CREATE TABLE
    deliverables (
        deliverable_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        project_id INT NOT NULL,
        uploaded_by INT NOT NULL,
        file_path VARCHAR(500),
        file_name VARCHAR(255),
        status ENUM ('Pending', 'Approved', 'Changes Requested') DEFAULT 'Pending',
        feedback TEXT,
        submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        reviewed_at TIMESTAMP NULL,
        FOREIGN KEY (project_id) REFERENCES projects (project_id) ON DELETE CASCADE,
        FOREIGN KEY (uploaded_by) REFERENCES users (user_id) ON DELETE CASCADE,
        INDEX idx_status (status),
        INDEX idx_project (project_id)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 6. NOTIFICATIONS TABLE
CREATE TABLE
    notifications (
        notification_id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        type ENUM ('task', 'approval', 'dss', 'system') NOT NULL,
        message TEXT NOT NULL,
        is_read BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE,
        INDEX idx_user_read (user_id, is_read),
        INDEX idx_created (created_at)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- 7. REPORTS TABLE (DSS - Decision Support System)
CREATE TABLE
    reports (
        report_id INT AUTO_INCREMENT PRIMARY KEY,
        project_id INT NOT NULL,
        progress_percentage INT DEFAULT 0,
        tasks_todo INT DEFAULT 0,
        tasks_in_progress INT DEFAULT 0,
        tasks_review INT DEFAULT 0,
        tasks_done INT DEFAULT 0,
        risk_level ENUM ('Low', 'Medium', 'High') DEFAULT 'Low',
        generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (project_id) REFERENCES projects (project_id) ON DELETE CASCADE,
        INDEX idx_project (project_id),
        INDEX idx_generated (generated_at)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- INSERT SAMPLE DATA
-- Sample Users (hashed password: 'password123')
INSERT INTO
    users (email, password, role, full_name)
VALUES
    (
        'admin@visionaryverse.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'admin',
        'Admin User'
    ),
    (
        'john@visionaryverse.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'staff',
        'John Smith'
    ),
    (
        'sarah@visionaryverse.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'staff',
        'Sarah Johnson'
    ),
    (
        'client1@techcorp.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'client',
        'Mike Wilson'
    ),
    (
        'client2@startupco.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'client',
        'Emily Davis'
    );

-- Sample Clients
INSERT INTO
    clients (user_id, name, email, company, status)
VALUES
    (
        4,
        'Mahinda Mahattaya',
        'client1@techcorp.com',
        'Tech Corp',
        'active'
    ),
    (
        5,
        'Anura Mahattaya',
        'client2@startupco.com',
        'Startup Co',
        'active'
    ),
    (
        NULL,
        'Ranil Mahattaya',
        'ranil@business.com',
        'Business Inc',
        'active'
    );

-- Sample Projects
INSERT INTO
    projects (
        name,
        client_id,
        service,
        status,
        due_date,
        description
    )
VALUES
    (
        'SEO Optimization',
        1,
        'SEO',
        'In Progress',
        '2026-04-15',
        'Complete SEO overhaul for Tech Corp website'
    ),
    (
        'Ads Campaign',
        2,
        'Ads',
        'Review',
        '2026-03-30',
        'Q1 Google Ads campaign for Startup Co'
    ),
    (
        'Social Media Content',
        1,
        'Social Media',
        'In Progress',
        '2026-04-01',
        'Monthly social media content creation'
    ),
    (
        'Website Redesign',
        3,
        'Web Design',
        'To Do',
        '2026-05-20',
        'Complete website redesign for Business Inc'
    );

-- Sample Tasks
INSERT INTO
    tasks (
        name,
        project_id,
        assignee_id,
        priority,
        status,
        deadline
    )
VALUES
    (
        'Keyword Research',
        1,
        2,
        'High',
        'Done',
        '2026-03-10'
    ),
    (
        'On-page optimization',
        1,
        2,
        'High',
        'In Progress',
        '2026-03-20'
    ),
    (
        'Backlink analysis',
        1,
        3,
        'Medium',
        'To Do',
        '2026-03-25'
    ),
    (
        'Ad copy creation',
        2,
        3,
        'High',
        'Review',
        '2026-03-15'
    ),
    (
        'Campaign setup',
        2,
        2,
        'High',
        'Done',
        '2026-03-12'
    ),
    (
        'Instagram posts',
        3,
        3,
        'Medium',
        'In Progress',
        '2026-03-28'
    ),
    (
        'Facebook content',
        3,
        3,
        'Low',
        'To Do',
        '2026-03-30'
    );

-- Sample Deliverables
INSERT INTO
    deliverables (name, project_id, uploaded_by, status)
VALUES
    ('SEO Audit Report v1', 1, 2, 'Approved'),
    ('Ad Campaign Draft', 2, 3, 'Pending'),
    (
        'Social Media Calendar March',
        3,
        3,
        'Changes Requested'
    );

-- Sample Notifications
INSERT INTO
    notifications (user_id, type, message, is_read)
VALUES
    (
        1,
        'task',
        'Task "Ad copy creation" moved to Review',
        FALSE
    ),
    (
        1,
        'approval',
        'Deliverable "Ad Campaign Draft" needs approval',
        FALSE
    ),
    (
        4,
        'approval',
        'Your deliverable has been approved',
        FALSE
    ),
    (
        2,
        'task',
        'You have been assigned to "On-page optimization"',
        TRUE
    );

-- Sample Reports
INSERT INTO
    reports (
        project_id,
        progress_percentage,
        tasks_todo,
        tasks_in_progress,
        tasks_review,
        tasks_done
    )
VALUES
    (1, 78, 2, 3, 1, 18),
    (2, 62, 3, 4, 2, 10),
    (3, 70, 2, 2, 1, 14),
    (4, 35, 6, 3, 0, 4);