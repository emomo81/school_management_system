import {
  BadgeDollarSign,
  Bell,
  BookOpen,
  Building2,
  CalendarCheck,
  CalendarDays,
  ClipboardList,
  FileSpreadsheet,
  GraduationCap,
  LayoutDashboard,
  Library,
  School,
  Users,
} from "lucide-react";
import type { Role } from "@/lib/auth";

export const navItems = [
  { href: "/dashboard", label: "Dashboard", icon: LayoutDashboard, roles: ["admin", "teacher", "student", "parent"] },
  { href: "/students", label: "Students", icon: GraduationCap, roles: ["admin", "teacher"] },
  { href: "/academic-years", label: "Academic Years", icon: CalendarDays, roles: ["admin", "teacher"] },
  { href: "/classes", label: "Programs", icon: School, roles: ["admin", "teacher"] },
  { href: "/subjects", label: "Subjects", icon: BookOpen, roles: ["admin", "teacher"] },
  { href: "/timetables", label: "Timetable", icon: CalendarCheck, roles: ["admin", "teacher", "student"] },
  { href: "/attendance", label: "Attendance", icon: ClipboardList, roles: ["admin", "teacher"] },
  { href: "/exams", label: "Exams & Marks", icon: FileSpreadsheet, roles: ["admin", "teacher"] },
  { href: "/notices", label: "Noticeboard", icon: Bell, roles: ["admin", "teacher"] },
  { href: "/teachers", label: "Teachers", icon: Users, roles: ["admin"] },
  { href: "/assignments", label: "Assignments", icon: Library, roles: ["admin"] },
  { href: "/fees", label: "Fees & Finance", icon: BadgeDollarSign, roles: ["admin"] },
  { href: "/departments", label: "Departments", icon: Building2, roles: ["admin"] },
  { href: "/report-card", label: "My Report Card", icon: FileSpreadsheet, roles: ["student"] },
] satisfies Array<{ href: string; label: string; icon: typeof LayoutDashboard; roles: Role[] }>;
