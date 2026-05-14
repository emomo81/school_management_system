"use server";

import bcrypt from "bcryptjs";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";
import { connectDB } from "@/lib/db";
import {
  AcademicYear,
  Assignment,
  Attendance,
  Department,
  Exam,
  Fee,
  Mark,
  Notice,
  SchoolClass,
  Student,
  Subject,
  Teacher,
  Timetable,
  User,
} from "@/lib/models";
import { clearSession, createSession, refreshSessionUser, requireUser } from "@/lib/auth";

function text(formData: FormData, key: string) {
  return String(formData.get(key) || "").trim();
}

function optionalId(formData: FormData, key: string) {
  const value = text(formData, key);
  return value || undefined;
}

function optionalDate(formData: FormData, key: string) {
  const value = text(formData, key);
  return value ? new Date(value) : undefined;
}

export async function loginAction(formData: FormData) {
  await connectDB();
  const email = text(formData, "email").toLowerCase();
  const password = text(formData, "password");
  const user = await User.findOne({ email }).lean();

  if (!user || !(await bcrypt.compare(password, user.passwordHash))) {
    redirect("/login?error=invalid");
  }

  await createSession({
    id: user._id.toString(),
    name: user.name,
    email: user.email,
    role: user.role,
    profilePic: user.profilePic,
  });
  redirect("/dashboard");
}

export async function logoutAction() {
  await clearSession();
  redirect("/login");
}

export async function updateProfileAction(formData: FormData) {
  const user = await requireUser();
  await connectDB();

  const password = text(formData, "password");
  const update: Record<string, unknown> = {
    name: text(formData, "name"),
    email: text(formData, "email").toLowerCase(),
  };
  if (password) update.passwordHash = await bcrypt.hash(password, 10);

  await User.findByIdAndUpdate(user.id, update);
  await refreshSessionUser(user.id);
  revalidatePath("/profile");
  redirect("/profile?updated=1");
}

export async function createDepartmentAction(formData: FormData) {
  await requireUser(["admin"]);
  await connectDB();
  await Department.create({ name: text(formData, "name"), code: text(formData, "code") });
  revalidatePath("/departments");
}

export async function updateDepartmentAction(formData: FormData) {
  await requireUser(["admin"]);
  await connectDB();
  await Department.findByIdAndUpdate(text(formData, "id"), { name: text(formData, "name"), code: text(formData, "code") });
  revalidatePath("/departments");
}

export async function deleteDepartmentAction(formData: FormData) {
  await requireUser(["admin"]);
  await connectDB();
  await Department.findByIdAndDelete(text(formData, "id"));
  revalidatePath("/departments");
}

export async function createAcademicYearAction(formData: FormData) {
  await requireUser(["admin", "teacher"]);
  await connectDB();
  await AcademicYear.create({
    name: text(formData, "name"),
    startDate: optionalDate(formData, "startDate"),
    endDate: optionalDate(formData, "endDate"),
  });
  revalidatePath("/academic-years");
}

export async function activateAcademicYearAction(formData: FormData) {
  await requireUser(["admin", "teacher"]);
  await connectDB();
  await AcademicYear.updateMany({}, { isActive: false });
  await AcademicYear.findByIdAndUpdate(text(formData, "id"), { isActive: true });
  revalidatePath("/academic-years");
}

export async function deleteAcademicYearAction(formData: FormData) {
  await requireUser(["admin"]);
  await connectDB();
  await AcademicYear.findByIdAndDelete(text(formData, "id"));
  revalidatePath("/academic-years");
}

export async function createClassAction(formData: FormData) {
  await requireUser(["admin", "teacher"]);
  await connectDB();
  await SchoolClass.create({
    name: text(formData, "name"),
    section: text(formData, "section"),
    department: optionalId(formData, "department"),
  });
  revalidatePath("/classes");
}

export async function createSubjectAction(formData: FormData) {
  await requireUser(["admin", "teacher"]);
  await connectDB();
  await Subject.create({
    name: text(formData, "name"),
    code: text(formData, "code"),
    department: optionalId(formData, "department"),
    credits: Number(text(formData, "credits") || 0),
    totalMarks: Number(text(formData, "totalMarks") || 100),
  });
  revalidatePath("/subjects");
}

export async function createStudentAction(formData: FormData) {
  await requireUser(["admin", "teacher"]);
  await connectDB();
  const firstName = text(formData, "firstName");
  const lastName = text(formData, "lastName");
  const password = text(formData, "password") || "student123";

  const user = await User.create({
    name: `${firstName} ${lastName}`.trim(),
    email: text(formData, "email").toLowerCase(),
    passwordHash: await bcrypt.hash(password, 10),
    role: "student",
  });

  await Student.create({
    user: user._id,
    admissionNo: text(formData, "admissionNo"),
    dob: optionalDate(formData, "dob"),
    gender: text(formData, "gender"),
    address: text(formData, "address"),
    schoolClass: optionalId(formData, "schoolClass"),
    academicYear: optionalId(formData, "academicYear"),
  });
  revalidatePath("/students");
}

export async function updateStudentAction(formData: FormData) {
  await requireUser(["admin", "teacher"]);
  await connectDB();
  const student = await Student.findById(text(formData, "id"));
  if (!student) return;

  const updateUser: Record<string, unknown> = {
    name: `${text(formData, "firstName")} ${text(formData, "lastName")}`.trim(),
    email: text(formData, "email").toLowerCase(),
  };
  const password = text(formData, "password");
  if (password) updateUser.passwordHash = await bcrypt.hash(password, 10);

  await User.findByIdAndUpdate(student.user, updateUser);
  await Student.findByIdAndUpdate(student._id, {
    admissionNo: text(formData, "admissionNo"),
    dob: optionalDate(formData, "dob"),
    gender: text(formData, "gender"),
    address: text(formData, "address"),
    schoolClass: optionalId(formData, "schoolClass"),
    academicYear: optionalId(formData, "academicYear"),
  });

  revalidatePath("/students");
  redirect("/students");
}

export async function deleteStudentAction(formData: FormData) {
  await requireUser(["admin"]);
  await connectDB();
  await Student.findByIdAndUpdate(text(formData, "id"), { deletedAt: new Date() });
  revalidatePath("/students");
}

export async function createTeacherAction(formData: FormData) {
  await requireUser(["admin"]);
  await connectDB();
  const user = await User.create({
    name: text(formData, "name"),
    email: text(formData, "email").toLowerCase(),
    passwordHash: await bcrypt.hash(text(formData, "password") || "teacher123", 10),
    role: "teacher",
  });

  await Teacher.create({
    user: user._id,
    phone: text(formData, "phone"),
    qualification: text(formData, "qualification"),
  });
  revalidatePath("/teachers");
}

export async function createNoticeAction(formData: FormData) {
  await requireUser(["admin", "teacher"]);
  await connectDB();
  await Notice.create({ title: text(formData, "title"), content: text(formData, "content") });
  revalidatePath("/notices");
  revalidatePath("/dashboard");
}

export async function deleteNoticeAction(formData: FormData) {
  await requireUser(["admin", "teacher"]);
  await connectDB();
  await Notice.findByIdAndDelete(text(formData, "id"));
  revalidatePath("/notices");
  revalidatePath("/dashboard");
}

export async function createAssignmentAction(formData: FormData) {
  await requireUser(["admin"]);
  await connectDB();
  await Assignment.create({
    teacher: text(formData, "teacher"),
    subject: text(formData, "subject"),
    schoolClass: text(formData, "schoolClass"),
  });
  revalidatePath("/assignments");
}

export async function deleteAssignmentAction(formData: FormData) {
  await requireUser(["admin"]);
  await connectDB();
  await Assignment.findByIdAndDelete(text(formData, "id"));
  revalidatePath("/assignments");
}

export async function createFeeAction(formData: FormData) {
  await requireUser(["admin"]);
  await connectDB();
  await Fee.create({
    student: text(formData, "student"),
    title: text(formData, "title"),
    amount: Number(text(formData, "amount") || 0),
    status: text(formData, "status") || "pending",
    dueDate: optionalDate(formData, "dueDate"),
  });
  revalidatePath("/fees");
}

export async function markFeePaidAction(formData: FormData) {
  await requireUser(["admin"]);
  await connectDB();
  await Fee.findByIdAndUpdate(text(formData, "id"), { status: "paid" });
  revalidatePath("/fees");
}

export async function createExamAction(formData: FormData) {
  await requireUser(["admin", "teacher"]);
  await connectDB();
  await Exam.create({
    name: text(formData, "name"),
    date: optionalDate(formData, "date"),
    department: optionalId(formData, "department"),
    program: optionalId(formData, "program"),
    term: text(formData, "term"),
  });
  revalidatePath("/exams");
}

export async function saveMarkAction(formData: FormData) {
  await requireUser(["admin", "teacher"]);
  await connectDB();
  await Mark.findOneAndUpdate(
    {
      exam: text(formData, "exam"),
      student: text(formData, "student"),
      subject: text(formData, "subject"),
    },
    {
      score: Number(text(formData, "score") || 0),
      total: Number(text(formData, "total") || 100),
    },
    { upsert: true, returnDocument: "after" },
  );
  revalidatePath("/exams");
}

export async function saveAttendanceAction(formData: FormData) {
  await requireUser(["admin", "teacher"]);
  await connectDB();
  const schoolClass = text(formData, "schoolClass");
  const date = optionalDate(formData, "date");
  const studentIds = formData.getAll("studentId").map(String);

  await Promise.all(
    studentIds.map((studentId) =>
      Attendance.findOneAndUpdate(
        { student: studentId, date },
        {
          student: studentId,
          schoolClass,
          date,
          status: text(formData, `status-${studentId}`),
          remarks: text(formData, `remarks-${studentId}`),
        },
        { upsert: true, returnDocument: "after" },
      ),
    ),
  );
  revalidatePath("/attendance");
}

export async function createTimetableAction(formData: FormData) {
  await requireUser(["admin", "teacher"]);
  await connectDB();
  await Timetable.create({
    schoolClass: text(formData, "schoolClass"),
    subject: text(formData, "subject"),
    teacher: text(formData, "teacher"),
    dayOfWeek: text(formData, "dayOfWeek"),
    startTime: text(formData, "startTime"),
    endTime: text(formData, "endTime"),
    roomNumber: text(formData, "roomNumber"),
  });
  revalidatePath("/timetables");
}

export async function deleteTimetableAction(formData: FormData) {
  await requireUser(["admin", "teacher"]);
  await connectDB();
  await Timetable.findByIdAndDelete(text(formData, "id"));
  revalidatePath("/timetables");
}
