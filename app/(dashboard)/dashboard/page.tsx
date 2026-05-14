import Link from "next/link";
import { ArrowRight, BookOpen, CalendarCheck, Clock, CreditCard, FileSpreadsheet, GraduationCap, School, TrendingUp, Users } from "lucide-react";
import { PageHeader } from "@/components/PageHeader";
import { EmptyState } from "@/components/EmptyState";
import { requireUser } from "@/lib/auth";
import { formatDate, money } from "@/lib/format";
import { getDashboardData, getStudentDashboardData, label } from "@/lib/queries";

export default async function DashboardPage() {
  const user = await requireUser();

  if (user.role === "student") {
    return <StudentDashboard userId={user.id} />;
  }

  const data = await getDashboardData();
  const stats = [
    { label: "Students", value: data.students, icon: GraduationCap, tone: "bg-emerald-800 text-white" },
    { label: "Teachers", value: data.teachers, icon: Users, tone: "bg-stone-900 text-white" },
    { label: "Programs", value: data.classes, icon: School, tone: "bg-amber-300 text-stone-950" },
    { label: "Exams", value: data.exams, icon: FileSpreadsheet, tone: "bg-red-700 text-white" },
  ];

  return (
    <>
      <PageHeader title="Dashboard" kicker="School overview" />
      <div className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {stats.map((stat) => {
          const Icon = stat.icon;
          return (
            <section key={stat.label} className="rounded-md border border-stone-300 bg-white p-4 shadow-sm sm:p-5">
              <div className={`mb-4 grid size-10 place-items-center rounded-md sm:mb-5 sm:size-11 ${stat.tone}`}>
                <Icon className="size-5" />
              </div>
              <p className="text-sm font-bold text-stone-500">{stat.label}</p>
              <p className="mt-1 font-[var(--font-display)] text-3xl font-bold text-stone-950 sm:text-4xl">{stat.value}</p>
            </section>
          );
        })}
      </div>

      <div className="mt-6 grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
        <section className="rounded-md border border-stone-300 bg-white p-4 shadow-sm sm:p-5">
          <div className="mb-4 flex items-center gap-2">
            <CalendarCheck className="size-5 shrink-0 text-emerald-800" />
            <h2 className="font-[var(--font-display)] text-lg font-bold sm:text-xl">Attendance Snapshot</h2>
          </div>
          {data.attendance.length ? (
            <div className="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
              {data.attendance.map((item: any) => (
                <div key={item._id} className="rounded-md border border-stone-200 bg-stone-50 p-4">
                  <p className="text-sm font-bold capitalize text-stone-600">{item._id}</p>
                  <p className="font-[var(--font-display)] text-3xl font-bold">{item.count}</p>
                </div>
              ))}
            </div>
          ) : (
            <EmptyState label="No attendance records yet." />
          )}
        </section>

        <section className="rounded-md border border-stone-300 bg-white p-4 shadow-sm sm:p-5">
          <div className="mb-4 flex items-center gap-2">
            <BookOpen className="size-5 shrink-0 text-emerald-800" />
            <h2 className="font-[var(--font-display)] text-lg font-bold sm:text-xl">Latest Notices</h2>
          </div>
          <NoticeList notices={data.notices} />
        </section>
      </div>
    </>
  );
}

async function StudentDashboard({ userId }: { userId: string }) {
  const data = await getStudentDashboardData(userId);
  const earned = data.marks.reduce((sum: number, mark: any) => sum + Number(mark.score || 0), 0);
  const possible = data.marks.reduce((sum: number, mark: any) => sum + Number(mark.total || 0), 0);
  const average = possible ? Math.round((earned / possible) * 100) : 0;
  const attendanceTotal = data.attendance.reduce((sum: number, item: any) => sum + Number(item.count || 0), 0);
  const outstanding = data.fees.filter((fee: any) => fee.status !== "paid").reduce((sum: number, fee: any) => sum + Number(fee.amount || 0), 0);
  const studentName = data.student?.user?.name || "Student";

  return (
    <>
      <PageHeader
        title={`Welcome, ${studentName.split(" ")[0]}`}
        kicker={data.student?.admissionNo || "Student dashboard"}
        action={
          <Link href="/report-card" className="focus-ring inline-flex h-10 items-center gap-2 rounded-md bg-emerald-900 px-4 text-sm font-bold text-white">
            Report card
            <ArrowRight className="size-4" />
          </Link>
        }
      />

      <section className="overflow-hidden rounded-md border border-emerald-950 bg-stone-950 text-white shadow-lg">
        <div className="grid gap-5 p-5 sm:p-6 lg:grid-cols-[1.4fr_0.6fr]">
          <div className="min-w-0">
            <p className="text-xs font-bold uppercase tracking-[0.2em] text-amber-200">My academic profile</p>
            <h2 className="mt-2 break-words font-[var(--font-display)] text-2xl font-bold sm:text-4xl">{studentName}</h2>
            <p className="mt-2 text-sm font-semibold text-stone-300">
              {label(data.student?.schoolClass, "No program assigned")} {data.student?.schoolClass?.section || ""}
            </p>
          </div>
          <div className="grid grid-cols-3 gap-2 sm:gap-3">
            <Metric label="Average" value={data.marks.length ? `${average}%` : "--"} />
            <Metric label="Days" value={attendanceTotal} />
            <Metric label="Balance" value={money(outstanding)} compact />
          </div>
        </div>
      </section>

      <div className="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <QuickLink href="/report-card" icon={FileSpreadsheet} label="Report Card" value={data.marks.length ? `${data.marks.length} marks` : "Pending"} />
        <QuickLink href="/timetables" icon={Clock} label="Timetable" value={data.timetables.length ? `${data.timetables.length} sessions` : "No sessions"} />
        <SummaryCard icon={CalendarCheck} label="Attendance" value={`${attendanceTotal} records`} />
        <SummaryCard icon={CreditCard} label="Fees" value={outstanding ? `${money(outstanding)} due` : "Cleared"} />
      </div>

      <div className="mt-6 grid gap-6 xl:grid-cols-[1fr_380px]">
        <section className="rounded-md border border-stone-300 bg-white p-4 shadow-sm sm:p-5">
          <div className="mb-4 flex items-center gap-2">
            <TrendingUp className="size-5 shrink-0 text-emerald-800" />
            <h2 className="font-[var(--font-display)] text-lg font-bold sm:text-xl">Recent Marks</h2>
          </div>
          <div className="grid gap-3">
            {data.marks.length ? (
              data.marks.map((mark: any) => {
                const percent = mark.total ? Math.round((mark.score / mark.total) * 100) : 0;
                return (
                  <article key={mark._id} className="rounded-md border border-stone-200 bg-stone-50 p-3 sm:p-4">
                    <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                      <div className="min-w-0">
                        <p className="break-words font-bold text-stone-950">{label(mark.subject)}</p>
                        <p className="text-sm text-stone-600">{label(mark.exam)}</p>
                      </div>
                      <p className="shrink-0 font-[var(--font-display)] text-2xl font-bold text-emerald-900">
                        {mark.score}/{mark.total}
                      </p>
                    </div>
                    <div className="mt-3 h-2 overflow-hidden rounded-full bg-stone-200">
                      <div className="h-full rounded-full bg-amber-400" style={{ width: `${Math.min(percent, 100)}%` }} />
                    </div>
                  </article>
                );
              })
            ) : (
              <EmptyState label="No marks posted yet." />
            )}
          </div>
        </section>

        <aside className="grid gap-6">
          <section className="rounded-md border border-stone-300 bg-white p-4 shadow-sm sm:p-5">
            <div className="mb-4 flex items-center gap-2">
              <Clock className="size-5 shrink-0 text-emerald-800" />
              <h2 className="font-[var(--font-display)] text-lg font-bold sm:text-xl">Upcoming Timetable</h2>
            </div>
            <div className="grid gap-3">
              {data.timetables.length ? (
                data.timetables.map((item: any) => (
                  <article key={item._id} className="rounded-md border border-stone-200 bg-stone-50 p-3">
                    <p className="font-bold text-stone-950">{label(item.subject)}</p>
                    <p className="text-sm text-stone-600">
                      {item.dayOfWeek} - {item.startTime}-{item.endTime}
                    </p>
                    <p className="text-xs font-semibold text-stone-500">{item.teacher?.user?.name || "Teacher not assigned"}</p>
                  </article>
                ))
              ) : (
                <EmptyState label="No timetable sessions yet." />
              )}
            </div>
          </section>

          <section className="rounded-md border border-stone-300 bg-white p-4 shadow-sm sm:p-5">
            <div className="mb-4 flex items-center gap-2">
              <BookOpen className="size-5 shrink-0 text-emerald-800" />
              <h2 className="font-[var(--font-display)] text-lg font-bold sm:text-xl">Latest Notices</h2>
            </div>
            <NoticeList notices={data.notices} />
          </section>
        </aside>
      </div>
    </>
  );
}

function Metric({ label, value, compact }: { label: string; value: string | number; compact?: boolean }) {
  return (
    <div className="min-w-0 rounded-md border border-white/15 bg-white/10 p-3">
      <p className="text-[10px] font-bold uppercase tracking-[0.14em] text-stone-300">{label}</p>
      <p className={`mt-1 truncate font-[var(--font-display)] font-bold text-amber-200 ${compact ? "text-sm sm:text-lg" : "text-xl sm:text-3xl"}`}>{value}</p>
    </div>
  );
}

function QuickLink({ href, icon: Icon, label, value }: { href: string; icon: typeof FileSpreadsheet; label: string; value: string }) {
  return (
    <Link href={href} className="focus-ring rounded-md border border-stone-300 bg-white p-4 shadow-sm transition hover:border-emerald-800">
      <div className="mb-4 grid size-10 place-items-center rounded-md bg-amber-300 text-stone-950">
        <Icon className="size-5" />
      </div>
      <p className="text-sm font-bold text-stone-500">{label}</p>
      <p className="mt-1 break-words font-[var(--font-display)] text-xl font-bold text-stone-950">{value}</p>
    </Link>
  );
}

function SummaryCard({ icon: Icon, label, value }: { icon: typeof CalendarCheck; label: string; value: string }) {
  return (
    <section className="rounded-md border border-stone-300 bg-white p-4 shadow-sm">
      <div className="mb-4 grid size-10 place-items-center rounded-md bg-emerald-800 text-white">
        <Icon className="size-5" />
      </div>
      <p className="text-sm font-bold text-stone-500">{label}</p>
      <p className="mt-1 break-words font-[var(--font-display)] text-xl font-bold text-stone-950">{value}</p>
    </section>
  );
}

function NoticeList({ notices }: { notices: any[] }) {
  return (
    <div className="grid gap-3">
      {notices.length ? (
        notices.map((notice: any) => (
          <article key={notice._id} className="rounded-md border border-stone-200 bg-stone-50 p-3 sm:p-4">
            <h3 className="break-words font-bold text-stone-950">{notice.title}</h3>
            <p className="line-clamp-2 text-sm text-stone-600">{notice.content}</p>
            <p className="mt-2 text-xs font-semibold text-stone-500">{formatDate(notice.createdAt)}</p>
          </article>
        ))
      ) : (
        <EmptyState label="No notices posted yet." />
      )}
    </div>
  );
}
