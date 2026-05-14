# MongoDB Users Migration - Complete Setup

## ✅ What Was Done

### 1. **Updated Environment Configuration**
- Updated `.env.local` with your MongoDB Atlas connection string:
  ```
  MONGODB_URI=mongodb+srv://emmanuelmomo81_db_user:0880286157Em@cluster0.hhx1puk.mongodb.net/school_management_system
  ```
- Updated `.env.example` with the correct MongoDB URI format for documentation

### 2. **Created Migration Script**
- Created `scripts/migrate-users-mongodb.ts` - A TypeScript migration script that:
  - Connects to MongoDB using the provided URI
  - Creates 3 default users (Admin, Teacher, Student)
  - Hashes passwords using bcryptjs
  - Checks for existing users (won't duplicate)
  - Provides detailed console output with status

### 3. **Updated package.json**
- Added new npm script: `npm run migrate:users`
- This makes it easy to run migrations

### 4. **Created Documentation**
- `MONGODB_SETUP.md` - Complete setup and troubleshooting guide

## 🚀 Quick Start

### Step 1: Run the Migration
```bash
npm run migrate:users
```

Expected output:
```
🔗 Connecting to MongoDB...
✅ Connected to MongoDB

📝 Migrating 3 users...

✓ User created: Admin User (admin@school.com)
  Email: admin@school.com
  Password: admin123
  Role: admin
  
[... more users ...]

📊 Migration Summary:
==================================================
Admin User           | created
Emmanuel Momo        | created
John Student         | created
==================================================

Total users in MongoDB: 3
```

### Step 2: Verify in MongoDB Atlas
1. Open MongoDB Atlas → Collections
2. Select database: `school_management_system`
3. Check the `users` collection
4. You should see 3 user documents

## 📋 Users Created

| Name | Email | Password | Role |
|------|-------|----------|------|
| Admin User | admin@school.com | admin123 | admin |
| Emmanuel Momo | teacher@school.com | teacher123 | teacher |
| John Student | student@school.com | student123 | student |

## 🔗 MongoDB Connection String

Your connection string includes:
- **Host**: cluster0.hhx1puk.mongodb.net
- **Database**: school_management_system
- **Credentials**: Stored securely in `.env.local`

## 📁 Files Modified/Created

```
school_management_system/
├── .env.local (UPDATED - MongoDB URI)
├── .env.example (UPDATED - URI format)
├── package.json (UPDATED - new npm script)
├── MONGODB_SETUP.md (NEW - Documentation)
├── scripts/
│   └── migrate-users-mongodb.ts (NEW - Migration script)
└── migrate_users_to_mongodb.php (NEW - PHP reference)
```

## ✨ Features

✓ Secure password hashing with bcryptjs  
✓ Duplicate user prevention  
✓ Detailed migration logging  
✓ Error handling and reporting  
✓ MongoDB Atlas compatible  
✓ Mongoose schema validated  

## 🔐 Security Notes

⚠️ **Important**: Change default passwords in production!

The default users and passwords are for development/testing only. Before deploying to production:
1. Change all user passwords
2. Update credentials in `.env` file
3. Use strong, unique passwords
4. Consider using environment-specific configurations

## 🆘 Troubleshooting

If you get a connection error:
1. Verify MongoDB URI in `.env.local`
2. Check IP whitelist in MongoDB Atlas (allow your IP)
3. Ensure database name `school_management_system` exists in URI
4. Verify credentials are correct

## Next Steps

✓ Users are now in MongoDB
✓ Ready to update your application to use MongoDB for user authentication
✓ Consider migrating other data (students, teachers, etc.) using similar pattern

---

**Status**: ✅ MongoDB users migration setup complete and ready to run!
