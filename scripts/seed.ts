import bcrypt from "bcryptjs";
import { loadEnvConfig } from "@next/env";
import { connectDB } from "../lib/db";
import {
  AcademicYear,
  Department,
  Exam,
  Mark,
  SchoolClass,
  Student,
  Subject,
  Teacher,
  User,
} from "../lib/models";

loadEnvConfig(process.cwd());

async function main() {
  await connectDB();

  const passwordHash = await bcrypt.hash("admin123", 10);
  const studentHash = await bcrypt.hash("student123", 10);
  const teacherHash = await bcrypt.hash("teacher123", 10);

  const admin = await User.findOneAndUpdate(
    { email: "admin@school.com" },
    { name: "Admin User", email: "admin@school.com", passwordHash, role: "admin" },
    { upsert: true, returnDocument: "after" },
  );

  const department = await Department.findOneAndUpdate(
    { code: "SCI" },
    { name: "Sciences", code: "SCI" },
    { upsert: true, returnDocument: "after" },
  );

  const year = await AcademicYear.findOneAndUpdate(
    { name: "2026 Academic Year" },
    { name: "2026 Academic Year", startDate: new Date("2026-01-01"), endDate: new Date("2026-12-31"), isActive: true },
    { upsert: true, returnDocument: "after" },
  );

  const schoolClass = await SchoolClass.findOneAndUpdate(
    { name: "Grade 9", section: "A" },
    { name: "Grade 9", section: "A", department: department._id },
    { upsert: true, returnDocument: "after" },
  );

  const subject = await Subject.findOneAndUpdate(
    { code: "MATH" },
    { name: "Mathematics", code: "MATH", department: department._id, credits: 4, totalMarks: 100 },
    { upsert: true, returnDocument: "after" },
  );

  const teacherUser = await User.findOneAndUpdate(
    { email: "teacher@school.com" },
    { name: "Emmanuel Momo", email: "teacher@school.com", passwordHash: teacherHash, role: "teacher" },
    { upsert: true, returnDocument: "after" },
  );
  await Teacher.findOneAndUpdate(
    { user: teacherUser._id },
    { user: teacherUser._id, phone: "0792951752", qualification: "BSc" },
    { upsert: true, returnDocument: "after" },
  );

  const studentUser = await User.findOneAndUpdate(
    { email: "student@school.com" },
    { name: "John Student", email: "student@school.com", passwordHash: studentHash, role: "student" },
    { upsert: true, returnDocument: "after" },
  );
  const student = await Student.findOneAndUpdate(
    { admissionNo: "ADM001" },
    {
      user: studentUser._id,
      admissionNo: "ADM001",
      dob: new Date("2005-01-01"),
      gender: "male",
      address: "Kigali",
      schoolClass: schoolClass._id,
      academicYear: year._id,
    },
    { upsert: true, returnDocument: "after" },
  );

  const exam = await Exam.findOneAndUpdate(
    { name: "Mid-Term 2026" },
    { name: "Mid-Term 2026", date: new Date("2026-05-14"), department: department._id, program: schoolClass._id, term: "Term 1" },
    { upsert: true, returnDocument: "after" },
  );

  await Mark.findOneAndUpdate(
    { exam: exam._id, student: student._id, subject: subject._id },
    { exam: exam._id, student: student._id, subject: subject._id, score: 90, total: 100 },
    { upsert: true, returnDocument: "after" },
  );

  console.log(`Seeded SchoolSys with admin ${admin.email}`);
}

main()
  .then(() => process.exit(0))
  .catch((error) => {
    console.error(error);
    process.exit(1);
  });
