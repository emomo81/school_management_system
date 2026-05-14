import { Award, CalendarCheck, CreditCard, GraduationCap } from "lucide-react";
import { PageHeader } from "@/components/PageHeader";
import { PrintButton } from "@/components/PrintButton";
import { requireUser } from "@/lib/auth";
import { formatDate, money } from "@/lib/format";
import { getStudentReport, label } from "@/lib/queries";

function gradeFor(average: number) {
  if (average >= 80) return "A";
  if (average >= 70) return "B";
  if (average >= 60) return "C";
  if (average >= 50) return "D";
  return "Needs Support";
}

export default async function ReportCardPage() {
  const user = await requireUser(["student"]);
  const report = await getStudentReport(user.id);
  const marks = report.marks || [];
  const earned = marks.reduce((sum: number, mark: any) => sum + Number(mark.score || 0), 0);
  const possible = marks.reduce((sum: number, mark: any) => sum + Number(mark.total || 0), 0);
  const average = possible ? Math.round((earned / possible) * 100) : 0;
  const grade = marks.length ? gradeFor(average) : "Pending";
  const attendanceTotal = report.attendance.reduce((sum: number, item: any) => sum + Number(item.count || 0), 0);
  const paidFees = report.fees.filter((fee: any) => fee.status === "paid").reduce((sum: number, fee: any) => sum + Number(fee.amount || 0), 0);
  const outstandingFees = report.fees.filter((fee: any) => fee.status !== "paid").reduce((sum: number, fee: any) => sum + Number(fee.amount || 0), 0);

  return (
    <>
      <PageHeader title="My Report Card" kicker={report.student?.admissionNo || "Student"} action={<PrintButton />} />

      <section className="print-area max-w-full overflow-hidden">
        <article className="print-card w-full min-w-0 overflow-hidden rounded-md border border-stone-300 bg-[#fffdf7] shadow-xl">
          <header className="relative border-b-4 border-emerald-950 bg-stone-950 px-4 py-5 text-white sm:px-6 md:px-8">
            <div className="absolute right-5 top-5 hidden rounded-full border border-amber-300/40 px-4 py-1 text-xs font-bold uppercase tracking-[0.24em] text-amber-200 md:block">
              Official Copy
            </div>
            <div className="flex min-w-0 flex-col gap-4 md:flex-row md:items-center md:justify-between">
              <div className="flex min-w-0 items-start gap-3 sm:items-center sm:gap-4">
                <div className="grid size-12 shrink-0 place-items-center rounded-md bg-amber-300 text-emerald-950 sm:size-16">
                  <GraduationCap className="size-7 sm:size-9" />
                </div>
                <div className="min-w-0">
                  <p className="text-xs font-bold uppercase tracking-[0.22em] text-amber-200">SchoolSys Academic Office</p>
                  <h1 className="mt-1 break-words font-[var(--font-display)] text-2xl font-bold sm:text-3xl">Student Performance Report</h1>
                  <p className="mt-1 text-sm text-stone-300">Generated on {formatDate(new Date())}</p>
                </div>
              </div>
              <div className="w-full rounded-md border border-white/15 bg-white/10 px-4 py-3 text-left sm:w-auto md:text-right">
                <p className="text-xs font-bold uppercase tracking-[0.18em] text-stone-300">Final Grade</p>
                <p className="break-words font-[var(--font-display)] text-3xl font-bold text-amber-200 sm:text-4xl">{grade}</p>
              </div>
            </div>
          </header>

          <div className="grid min-w-0 gap-0 lg:grid-cols-[minmax(0,1fr)_320px]">
            <main className="min-w-0 p-4 sm:p-6 md:p-8">
              <section className="print-avoid-break mb-6 grid gap-3 border-b border-stone-300 pb-6 md:grid-cols-4">
                <Info label="Student" value={report.student?.user?.name || user.name} strong />
                <Info label="Admission No." value={report.student?.admissionNo || "Not set"} />
                <Info label="Program" value={`${label(report.student?.schoolClass)} ${report.student?.schoolClass?.section || ""}`.trim()} />
                <Info label="Average" value={marks.length ? `${average}%` : "Pending"} strong />
              </section>

              <section className="print-avoid-break">
                <div className="mb-4 flex items-center gap-2">
                  <Award className="size-5 shrink-0 text-emerald-900" />
                  <h2 className="font-[var(--font-display)] text-xl font-bold text-stone-950 sm:text-2xl">Assessment Record</h2>
                </div>

                <div className="grid gap-3 md:hidden print:hidden">
                  {marks.length ? (
                    marks.map((mark: any) => {
                      const percent = mark.total ? Math.round((mark.score / mark.total) * 100) : 0;
                      return (
                        <article key={mark._id} className="rounded-md border border-stone-300 bg-white p-4">
                          <p className="break-words text-sm font-bold text-stone-500">{label(mark.exam)}</p>
                          <div className="mt-2 flex items-start justify-between gap-3">
                            <div className="min-w-0">
                              <p className="break-words font-[var(--font-display)] text-lg font-bold text-stone-950">{label(mark.subject)}</p>
                              <p className="text-sm font-semibold text-stone-600">{percent}% progress</p>
                            </div>
                            <p className="shrink-0 rounded-md bg-amber-200 px-2 py-1 text-sm font-bold text-stone-950">
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
                    <div className="rounded-md border border-stone-300 bg-white px-4 py-8 text-center font-semibold text-stone-500">
                      Marks have not been entered yet.
                    </div>
                  )}
                </div>

                <div className="hidden overflow-x-auto rounded-md border border-stone-300 md:block print:block">
                  <table className="w-full min-w-[620px] text-left text-sm">
                    <thead className="bg-emerald-950 text-xs uppercase tracking-[0.16em] text-white">
                      <tr>
                        <th className="px-4 py-3">Exam</th>
                        <th className="px-4 py-3">Subject</th>
                        <th className="px-4 py-3">Score</th>
                        <th className="px-4 py-3">Progress</th>
                      </tr>
                    </thead>
                    <tbody className="divide-y divide-stone-200 bg-white">
                      {marks.length ? (
                        marks.map((mark: any) => {
                          const percent = mark.total ? Math.round((mark.score / mark.total) * 100) : 0;
                          return (
                            <tr key={mark._id}>
                              <td className="px-4 py-4 font-bold text-stone-950">{label(mark.exam)}</td>
                              <td className="px-4 py-4">{label(mark.subject)}</td>
                              <td className="px-4 py-4 font-bold">
                                {mark.score}/{mark.total}
                              </td>
                              <td className="px-4 py-4">
                                <div className="h-2 w-full overflow-hidden rounded-full bg-stone-200">
                                  <div className="h-full rounded-full bg-amber-400" style={{ width: `${Math.min(percent, 100)}%` }} />
                                </div>
                                <p className="mt-1 text-xs font-bold text-stone-500">{percent}%</p>
                              </td>
                            </tr>
                          );
                        })
                      ) : (
                        <tr>
                          <td className="px-4 py-8 text-center font-semibold text-stone-500" colSpan={4}>
                            Marks have not been entered yet.
                          </td>
                        </tr>
                      )}
                    </tbody>
                  </table>
                </div>
              </section>

              <section className="print-avoid-break mt-8 grid gap-6 md:grid-cols-2">
                <div>
                  <p className="text-xs font-bold uppercase tracking-[0.18em] text-stone-500">Class Teacher</p>
                  <div className="mt-8 border-t border-stone-500 pt-2 text-sm font-semibold text-stone-700">Signature</div>
                </div>
                <div>
                  <p className="text-xs font-bold uppercase tracking-[0.18em] text-stone-500">Head of School</p>
                  <div className="mt-8 border-t border-stone-500 pt-2 text-sm font-semibold text-stone-700">Signature</div>
                </div>
              </section>
            </main>

            <aside className="min-w-0 border-t border-stone-300 bg-stone-100 p-4 sm:p-6 lg:border-l lg:border-t-0">
              <section className="print-avoid-break min-w-0 rounded-md border border-stone-300 bg-white p-4 sm:p-5">
                <div className="mb-4 flex items-center gap-2">
                  <CalendarCheck className="size-5 shrink-0 text-emerald-900" />
                  <h2 className="font-[var(--font-display)] text-xl font-bold">Attendance</h2>
                </div>
                <p className="font-[var(--font-display)] text-4xl font-bold text-stone-950">{attendanceTotal}</p>
                <p className="text-sm font-semibold text-stone-500">Recorded days</p>
                <div className="mt-4 grid gap-2">
                  {report.attendance.length ? (
                    report.attendance.map((item: any) => (
                      <p key={item._id} className="flex justify-between rounded bg-stone-50 px-3 py-2 text-sm capitalize">
                        <span>{item._id}</span>
                        <span className="font-bold">{item.count}</span>
                      </p>
                    ))
                  ) : (
                    <p className="text-sm font-semibold text-stone-500">No attendance records.</p>
                  )}
                </div>
              </section>

              <section className="print-avoid-break mt-4 min-w-0 rounded-md border border-stone-300 bg-white p-4 sm:p-5">
                <div className="mb-4 flex items-center gap-2">
                  <CreditCard className="size-5 shrink-0 text-emerald-900" />
                  <h2 className="font-[var(--font-display)] text-xl font-bold">Fees</h2>
                </div>
                <div className="grid gap-3 sm:grid-cols-2">
                  <div className="min-w-0 rounded-md bg-emerald-50 p-3">
                    <p className="text-xs font-bold uppercase text-emerald-900">Paid</p>
                    <p className="break-words font-bold">{money(paidFees)}</p>
                  </div>
                  <div className="min-w-0 rounded-md bg-red-50 p-3">
                    <p className="text-xs font-bold uppercase text-red-800">Balance</p>
                    <p className="break-words font-bold">{money(outstandingFees)}</p>
                  </div>
                </div>
                <div className="mt-4 grid gap-2">
                  {report.fees.length ? (
                    report.fees.map((fee: any) => (
                      <p key={fee._id} className="rounded bg-stone-50 px-3 py-2 text-sm">
                        <span className="font-bold">{fee.title}</span> - {money(fee.amount)} - {fee.status} - {formatDate(fee.dueDate)}
                      </p>
                    ))
                  ) : (
                    <p className="text-sm font-semibold text-stone-500">No fee records.</p>
                  )}
                </div>
              </section>

              <section className="print-avoid-break mt-4 min-w-0 rounded-md border-2 border-emerald-950 bg-amber-100 p-4 text-center sm:p-5">
                <p className="text-xs font-bold uppercase tracking-[0.14em] text-emerald-950 sm:tracking-[0.2em]">Academic Standing</p>
                <p className="mt-2 break-words font-[var(--font-display)] text-4xl font-bold text-emerald-950 sm:text-5xl">{marks.length ? `${average}%` : "--"}</p>
              </section>
            </aside>
          </div>
        </article>
      </section>
    </>
  );
}

function Info({ label, value, strong }: { label: string; value: string; strong?: boolean }) {
  return (
    <div className="min-w-0 rounded-md border border-stone-300 bg-white p-4">
      <p className="text-xs font-bold uppercase tracking-[0.16em] text-stone-500">{label}</p>
      <p className={`mt-1 break-words text-stone-950 ${strong ? "font-[var(--font-display)] text-xl font-bold" : "font-semibold"}`}>{value}</p>
    </div>
  );
}
