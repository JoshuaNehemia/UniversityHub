# UniversityHub

## REST API Structure
```
APP
│
├── API
│   ├── index.php
│   ├── .htaccess
│   └── routes
│        ├── auth.php
│        ├── dosen.php
│        ├── mahasiswa.php
│        ├── event.php
│        ├── group.php
│        ├── chat.php
│        └── upload.php
│
├── CORE
│        ├── Request.php
│        ├── Response.php
│        ├── Database.php
│
├── CONTROLLERS
│        ├── AuthController.php
│        ├── DosenController.php
│        ├── MahasiswaController.php
│        ├── EventController.php
│        ├── GroupController.php
│        ├── ChatController.php
│        ├── UploadController.php
│
└── MODELS
         ├── Dosen.php
         ├── Mahasiswa.php
         ├── Event.php
         ├── Group.php
         ├── Chat.php
         └── Akun.php
```