# MongoDB Migration Guide

## Overview
This document explains how to set up and migrate user data to MongoDB Atlas.

## Prerequisites
- MongoDB Atlas account
- Connection string from MongoDB Atlas
- Node.js and npm installed

## Setup Steps

### 1. Update Environment Variables
Edit `.env.local` and set your MongoDB URI:
```
MONGODB_URI=mongodb+srv://username:password@cluster.mongodb.net/school_management_system
```

### 2. Install Dependencies
```bash
npm install
```

### 3. Migrate Users to MongoDB
Run the migration script to add users to MongoDB:
```bash
npm run migrate:users
```

This will create three default users:
- **Admin**: admin@school.com / admin123
- **Teacher**: teacher@school.com / teacher123
- **Student**: student@school.com / student123

## What Gets Migrated

The migration script creates the following users in MongoDB:

| Name | Email | Password | Role |
|------|-------|----------|------|
| Admin User | admin@school.com | admin123 | admin |
| Emmanuel Momo | teacher@school.com | teacher123 | teacher |
| John Student | student@school.com | student123 | student |

## MongoDB Collections

After migration, your MongoDB database will have a `users` collection with documents structured as:

```json
{
  "_id": ObjectId,
  "name": "Admin User",
  "email": "admin@school.com",
  "passwordHash": "$2a$10$...",
  "role": "admin",
  "createdAt": ISODate,
  "updatedAt": ISODate
}
```

## Troubleshooting

### Connection Error
If you get a connection error:
1. Verify your MongoDB Atlas credentials are correct
2. Check that your IP address is whitelisted in MongoDB Atlas
3. Ensure the database name in the URI matches: `school_management_system`

### Users Already Exist
If the script says users already exist, they've been previously migrated. To re-migrate:
1. Delete the users collection in MongoDB Atlas
2. Run the migration script again

## Manual Database Verification

To verify users were created in MongoDB Atlas:
1. Go to MongoDB Atlas → Collections
2. Select your database: `school_management_system`
3. View the `users` collection
4. You should see 3 user documents

## Adding New Users

To add new users programmatically, use the User model from `lib/models.ts`:

```typescript
import { User } from "../lib/models";
import bcrypt from "bcryptjs";

const passwordHash = await bcrypt.hash("password123", 10);
await User.create({
  name: "New User",
  email: "newuser@school.com",
  passwordHash,
  role: "student"
});
```
