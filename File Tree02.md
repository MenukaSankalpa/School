project/
│
├── css/
│   ├── style.css
│   ├── pdashboard.css
│   ├── adashboard.css
│   └── school_dashboard.css
│
├── uploads/
│   └── (uploaded documents)
│   ├── Lbills
│   ├── ebills

├── includes/
│   ├── db.php
│   ├── header.php             # common page header (optional)
│   ├── footer.php             # common footer (optional)
│   └── auth_check.php         # session & role validation functions
│
├── index.php                  # login & register entry
├── login.php
├── logout.php
├── register.php
├── role_redirect.php          # redirect by role after login
│
├── parent/
│   ├── parent_dash.php
│   ├── submit_schools.php
│   ├── information.php
│   ├── save_information.php
│   ├── view_applications.php
│   ├── edit_application.php
│   └── update_application.php
│
├── school_admin/
│   ├── dashboard.php
│   ├── view_applications.php
│   ├── update_status.php
│   └── manage_school_info.php
│
├── admin/
│   ├── dashboard.php
│   ├── manage_users.php
│   ├── manage_schools.php
│   ├── view_all_applications.php
│   └── reports.php
│
└── js/
    └── script.js             # your existing JS from script.js
