import mongoose, { Schema, type InferSchemaType, type Model } from "mongoose";

const objectId = Schema.Types.ObjectId;

const userSchema = new Schema(
  {
    name: { type: String, required: true, trim: true },
    email: { type: String, required: true, unique: true, lowercase: true, trim: true },
    passwordHash: { type: String, required: true },
    role: { type: String, enum: ["admin", "teacher", "student", "parent"], required: true },
    profilePic: String,
  },
  { timestamps: true },
);

const departmentSchema = new Schema(
  {
    name: { type: String, required: true, trim: true },
    code: { type: String, required: true, trim: true },
  },
  { timestamps: true },
);

const academicYearSchema = new Schema(
  {
    name: { type: String, required: true, trim: true },
    startDate: { type: Date, required: true },
    endDate: { type: Date, required: true },
    isActive: { type: Boolean, default: false },
  },
  { timestamps: true },
);

const schoolClassSchema = new Schema(
  {
    name: { type: String, required: true, trim: true },
    section: { type: String, required: true, trim: true },
    department: { type: objectId, ref: "Department" },
    deletedAt: Date,
  },
  { timestamps: true },
);

const studentSchema = new Schema(
  {
    user: { type: objectId, ref: "User", required: true },
    admissionNo: { type: String, required: true, unique: true, trim: true },
    dob: Date,
    gender: { type: String, enum: ["male", "female", "other", ""], default: "" },
    address: String,
    schoolClass: { type: objectId, ref: "SchoolClass" },
    academicYear: { type: objectId, ref: "AcademicYear" },
    deletedAt: Date,
  },
  { timestamps: true },
);

const teacherSchema = new Schema(
  {
    user: { type: objectId, ref: "User", required: true },
    phone: String,
    qualification: String,
    deletedAt: Date,
  },
  { timestamps: true },
);

const subjectSchema = new Schema(
  {
    name: { type: String, required: true, trim: true },
    code: { type: String, required: true, trim: true },
    department: { type: objectId, ref: "Department" },
    credits: { type: Number, default: 0 },
    totalMarks: { type: Number, default: 100 },
    deletedAt: Date,
  },
  { timestamps: true },
);

const noticeSchema = new Schema(
  {
    title: { type: String, required: true, trim: true },
    content: { type: String, required: true },
  },
  { timestamps: true },
);

const assignmentSchema = new Schema(
  {
    teacher: { type: objectId, ref: "Teacher", required: true },
    subject: { type: objectId, ref: "Subject", required: true },
    schoolClass: { type: objectId, ref: "SchoolClass", required: true },
  },
  { timestamps: true },
);

const feeSchema = new Schema(
  {
    student: { type: objectId, ref: "Student", required: true },
    title: { type: String, required: true },
    amount: { type: Number, required: true },
    status: { type: String, enum: ["pending", "paid", "overdue"], default: "pending" },
    dueDate: Date,
  },
  { timestamps: true },
);

const attendanceSchema = new Schema(
  {
    student: { type: objectId, ref: "Student", required: true },
    schoolClass: { type: objectId, ref: "SchoolClass", required: true },
    date: { type: Date, required: true },
    status: { type: String, enum: ["present", "absent", "late", "excused"], required: true },
    remarks: String,
  },
  { timestamps: true },
);

attendanceSchema.index({ student: 1, date: 1 }, { unique: true });

const examSchema = new Schema(
  {
    name: { type: String, required: true },
    date: { type: Date, required: true },
    department: { type: objectId, ref: "Department" },
    program: { type: objectId, ref: "SchoolClass" },
    term: String,
  },
  { timestamps: true },
);

const markSchema = new Schema(
  {
    exam: { type: objectId, ref: "Exam", required: true },
    student: { type: objectId, ref: "Student", required: true },
    subject: { type: objectId, ref: "Subject", required: true },
    score: { type: Number, required: true },
    total: { type: Number, default: 100 },
  },
  { timestamps: true },
);

markSchema.index({ exam: 1, student: 1, subject: 1 }, { unique: true });

const timetableSchema = new Schema(
  {
    schoolClass: { type: objectId, ref: "SchoolClass", required: true },
    subject: { type: objectId, ref: "Subject", required: true },
    teacher: { type: objectId, ref: "Teacher", required: true },
    dayOfWeek: { type: String, required: true },
    startTime: { type: String, required: true },
    endTime: { type: String, required: true },
    roomNumber: String,
  },
  { timestamps: true },
);

function model<T>(name: string, schema: Schema<T>) {
  return (mongoose.models[name] as Model<T>) || mongoose.model<T>(name, schema);
}

export type UserDoc = InferSchemaType<typeof userSchema>;
export const User = model("User", userSchema);
export const Department = model("Department", departmentSchema);
export const AcademicYear = model("AcademicYear", academicYearSchema);
export const SchoolClass = model("SchoolClass", schoolClassSchema);
export const Student = model("Student", studentSchema);
export const Teacher = model("Teacher", teacherSchema);
export const Subject = model("Subject", subjectSchema);
export const Notice = model("Notice", noticeSchema);
export const Assignment = model("Assignment", assignmentSchema);
export const Fee = model("Fee", feeSchema);
export const Attendance = model("Attendance", attendanceSchema);
export const Exam = model("Exam", examSchema);
export const Mark = model("Mark", markSchema);
export const Timetable = model("Timetable", timetableSchema);
