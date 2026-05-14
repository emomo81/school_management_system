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
import { toPlain } from "@/lib/format";

type AnyDoc = Record<string, any>;

export async function getDashboardData() {
  await connectDB();
  const [students, teachers, classes, exams, notices, attendance] = await Promise.all([
    Student.countDocuments({ deletedAt: null }),
    Teacher.countDocuments({ deletedAt: null }),
    SchoolClass.countDocuments({ deletedAt: null }),
    Exam.countDocuments({}),
    Notice.find().sort({ createdAt: -1 }).limit(4).lean(),
    Attendance.aggregate([{ $group: { _id: "$status", count: { $sum: 1 } } }]),
  ]);

  return toPlain({ students, teachers, classes, exams, notices, attendance });
}

export async function getStudentDashboardData(userId: string) {
  await connectDB();
  const student = await Student.findOne({ user: userId, deletedAt: null }).populate("user").populate("schoolClass").lean();
  if (!student) {
    const notices = await Notice.find().sort({ createdAt: -1 }).limit(3).lean();
    return toPlain({ student: null, marks: [], fees: [], attendance: [], timetables: [], notices });
  }

  const [marks, fees, attendance, timetables, notices] = await Promise.all([
    Mark.find({ student: student._id }).populate("exam").populate("subject").sort({ createdAt: -1 }).limit(4).lean(),
    Fee.find({ student: student._id }).sort({ dueDate: 1 }).limit(4).lean(),
    Attendance.aggregate([{ $match: { student: student._id } }, { $group: { _id: "$status", count: { $sum: 1 } } }]),
    Timetable.find({ schoolClass: student.schoolClass })
      .populate("subject")
      .populate({ path: "teacher", populate: { path: "user" } })
      .limit(5)
      .lean(),
    Notice.find().sort({ createdAt: -1 }).limit(3).lean(),
  ]);

  return toPlain({ student, marks, fees, attendance, timetables, notices });
}

export async function getDepartments() {
  await connectDB();
  return toPlain(await Department.find().sort({ name: 1 }).lean());
}

export async function getAcademicYears() {
  await connectDB();
  return toPlain(await AcademicYear.find().sort({ startDate: -1 }).lean());
}

export async function getClasses() {
  await connectDB();
  return toPlain(await SchoolClass.find({ deletedAt: null }).populate("department").sort({ name: 1 }).lean());
}

export async function getSubjects() {
  await connectDB();
  return toPlain(await Subject.find({ deletedAt: null }).populate("department").sort({ name: 1 }).lean());
}

export async function getStudents() {
  await connectDB();
  return toPlain(
    await Student.find({ deletedAt: null })
      .populate("user")
      .populate("schoolClass")
      .populate("academicYear")
      .sort({ createdAt: -1 })
      .lean(),
  );
}

export async function getStudent(id: string) {
  await connectDB();
  return toPlain(await Student.findById(id).populate("user").populate("schoolClass").populate("academicYear").lean());
}

export async function getTeachers() {
  await connectDB();
  return toPlain(await Teacher.find({ deletedAt: null }).populate("user").sort({ createdAt: -1 }).lean());
}

export async function getNotices() {
  await connectDB();
  return toPlain(await Notice.find().sort({ createdAt: -1 }).lean());
}

export async function getAssignments() {
  await connectDB();
  return toPlain(
    await Assignment.find()
      .populate({ path: "teacher", populate: { path: "user" } })
      .populate("subject")
      .populate("schoolClass")
      .sort({ createdAt: -1 })
      .lean(),
  );
}

export async function getFees() {
  await connectDB();
  return toPlain(
    await Fee.find()
      .populate({ path: "student", populate: [{ path: "user" }, { path: "schoolClass" }] })
      .sort({ createdAt: -1 })
      .lean(),
  );
}

export async function getExams() {
  await connectDB();
  return toPlain(await Exam.find().populate("department").populate("program").sort({ date: -1 }).lean());
}

export async function getMarks() {
  await connectDB();
  return toPlain(
    await Mark.find()
      .populate("exam")
      .populate("subject")
      .populate({ path: "student", populate: { path: "user" } })
      .sort({ createdAt: -1 })
      .lean(),
  );
}

export async function getAttendanceForClass(classId?: string, date?: string) {
  await connectDB();
  if (!classId || !date) return toPlain({ students: [], records: [] });

  const day = new Date(date);
  const [students, records] = await Promise.all([
    Student.find({ schoolClass: classId, deletedAt: null }).populate("user").sort({ createdAt: 1 }).lean(),
    Attendance.find({ schoolClass: classId, date: day }).lean(),
  ]);

  return toPlain({ students, records });
}

export async function getTimetables() {
  await connectDB();
  return toPlain(
    await Timetable.find()
      .populate("schoolClass")
      .populate("subject")
      .populate({ path: "teacher", populate: { path: "user" } })
      .lean(),
  );
}

export async function getTimetablesForStudent(userId: string) {
  await connectDB();
  const student = await Student.findOne({ user: userId, deletedAt: null }).lean();
  if (!student?.schoolClass) return [];

  return toPlain(
    await Timetable.find({ schoolClass: student.schoolClass })
      .populate("schoolClass")
      .populate("subject")
      .populate({ path: "teacher", populate: { path: "user" } })
      .lean(),
  );
}

export async function getProfile(userId: string) {
  await connectDB();
  return toPlain(await User.findById(userId).lean());
}

export async function getStudentReport(userId: string) {
  await connectDB();
  const student = await Student.findOne({ user: userId }).populate("user").populate("schoolClass").lean();
  if (!student) return toPlain({ student: null, marks: [], fees: [], attendance: [] });

  const [marks, fees, attendance] = await Promise.all([
    Mark.find({ student: student._id }).populate("exam").populate("subject").sort({ createdAt: -1 }).lean(),
    Fee.find({ student: student._id }).sort({ dueDate: 1 }).lean(),
    Attendance.aggregate([{ $match: { student: student._id } }, { $group: { _id: "$status", count: { $sum: 1 } } }]),
  ]);

  return toPlain({ student, marks, fees, attendance });
}

export function label(doc: AnyDoc | undefined | null, fallback = "Not assigned") {
  return doc?.name || doc?.title || fallback;
}

export function id(doc: AnyDoc) {
  return String(doc?._id || "");
}
