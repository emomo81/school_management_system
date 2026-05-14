import bcrypt from "bcryptjs";
import { loadEnvConfig } from "@next/env";
import mongoose from "mongoose";
import { User } from "../lib/models";

loadEnvConfig(process.cwd());

async function migrateUsers() {
  try {
    // Connect to MongoDB using the connection string from .env.local
    const mongoUri = process.env.MONGODB_URI;
    if (!mongoUri) {
      throw new Error("MONGODB_URI not found in environment variables");
    }

    console.log("🔗 Connecting to MongoDB...");
    console.log(`   URI: ${mongoUri.substring(0, 50)}...`);

    await mongoose.connect(mongoUri);
    console.log("✅ Connected to MongoDB\n");

    // Define users to migrate
    const usersData = [
      {
        name: "Admin User",
        email: "admin@school.com",
        password: "admin123",
        role: "admin",
      },
      {
        name: "Emmanuel Momo",
        email: "teacher@school.com",
        password: "teacher123",
        role: "teacher",
      },
      {
        name: "John Student",
        email: "student@school.com",
        password: "student123",
        role: "student",
      },
    ];

    console.log(`📝 Migrating ${usersData.length} users...\n`);

    const results: Array<{name: string; email: string; status: string}> = [];

    for (const userData of usersData) {
      try {
        // Check if user exists
        const existingUser = await User.findOne({ email: userData.email });

        if (existingUser) {
          console.log(`⚠️  User already exists: ${userData.email}`);
          results.push({
            name: userData.name,
            email: userData.email,
            status: "skipped (already exists)",
          });
          continue;
        }

        // Hash password
        const passwordHash = await bcrypt.hash(userData.password, 10);

        // Create user
        const user = await User.create({
          name: userData.name,
          email: userData.email,
          passwordHash,
          role: userData.role,
        });

        console.log(`✓ User created: ${userData.name} (${userData.email})`);
        console.log(`  Email: ${userData.email}`);
        console.log(`  Password: ${userData.password}`);
        console.log(`  Role: ${userData.role}\n`);

        results.push({
          name: userData.name,
          email: userData.email,
          status: "created",
        });
      } catch (error) {
        console.error(
          `✗ Error creating user ${userData.email}:`,
          error instanceof Error ? error.message : error
        );
        results.push({
          name: userData.name,
          email: userData.email,
          status: "failed",
        });
      }
    }

    // Display summary
    console.log("\n" + "=".repeat(50));
    console.log("📊 Migration Summary:");
    console.log("=".repeat(50));

    for (const result of results) {
      console.log(`${result.name.padEnd(20)} | ${result.status}`);
    }

    // Count total users
    const totalUsers = await User.countDocuments();
    console.log("\n" + "=".repeat(50));
    console.log(`Total users in MongoDB: ${totalUsers}`);
    console.log("=".repeat(50) + "\n");

    console.log("✅ Migration completed successfully!\n");

    // Verify by fetching sample
    const sampleUser = await User.findOne();
    if (sampleUser) {
      console.log("Sample user document:");
      console.log(JSON.stringify(sampleUser.toJSON(), null, 2));
    }
  } catch (error) {
    console.error("❌ Migration failed:", error);
    process.exit(1);
  } finally {
    await mongoose.disconnect();
    console.log("\n✅ Disconnected from MongoDB");
  }
}

migrateUsers();
