import { Plus } from "lucide-react";
import { DeleteButton } from "@/components/DeleteButton";
import { Field, SelectField } from "@/components/Field";
import { PageHeader } from "@/components/PageHeader";
import { SubmitButton } from "@/components/SubmitButton";
import { createTimetableAction, deleteTimetableAction } from "@/lib/actions";
import { requireUser } from "@/lib/auth";
import { getClasses, getSubjects, getTeachers, getTimetables, getTimetablesForStudent, id, label } from "@/lib/queries";

const days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

export default async function TimetablesPage() {
  const user = await requireUser(["admin", "teacher", "student"]);
  const canManage = user.role === "admin" || user.role === "teacher";
  const [timetables, classes, subjects, teachers] = canManage
    ? await Promise.all([getTimetables(), getClasses(), getSubjects(), getTeachers()])
    : [await getTimetablesForStudent(user.id), [], [], []];

  return (
    <>
      <PageHeader title="Timetable" kicker={canManage ? "Weekly schedule" : "My weekly schedule"} />
      <div className={canManage ? "grid gap-6 xl:grid-cols-[380px_1fr]" : "grid gap-6"}>
        {canManage ? (
          <form action={createTimetableAction} className="h-fit rounded-md border border-stone-300 bg-white p-5 shadow-sm">
            <h2 className="mb-4 font-[var(--font-display)] text-xl font-bold">New Session</h2>
            <div className="grid gap-4">
              <SelectField label="Program" name="schoolClass" required options={classes.map((schoolClass: any) => ({ value: id(schoolClass), label: `${schoolClass.name} ${schoolClass.section}` }))} />
              <SelectField label="Subject" name="subject" required options={subjects.map((subject: any) => ({ value: id(subject), label: subject.name }))} />
              <SelectField label="Teacher" name="teacher" required options={teachers.map((teacher: any) => ({ value: id(teacher), label: teacher.user?.name || "Teacher" }))} />
              <SelectField label="Day" name="dayOfWeek" required options={days.map((day) => ({ value: day, label: day }))} />
              <Field label="Start time" name="startTime" type="time" required />
              <Field label="End time" name="endTime" type="time" required />
              <Field label="Room" name="roomNumber" />
              <SubmitButton>
                <Plus className="size-4" />
                Add session
              </SubmitButton>
            </div>
          </form>
        ) : null}

        <section className="grid gap-3">
          {days.map((day) => {
            const rows = timetables.filter((item: any) => item.dayOfWeek === day);
            return (
              <div key={day} className="rounded-md border border-stone-300 bg-white p-4 shadow-sm">
                <h2 className="mb-3 font-[var(--font-display)] text-xl font-bold">{day}</h2>
                <div className="grid gap-2">
                  {rows.length ? (
                    rows.map((item: any) => (
                      <article key={id(item)} className="flex flex-col gap-3 rounded-md bg-stone-50 p-3 md:flex-row md:items-center md:justify-between">
                        <div>
                          <p className="font-bold">
                            {label(item.subject)} - {item.startTime}-{item.endTime}
                          </p>
                          <p className="text-sm text-stone-600">
                            {label(item.schoolClass)} {item.schoolClass?.section} - {item.teacher?.user?.name} - Room {item.roomNumber || "TBD"}
                          </p>
                        </div>
                        {canManage ? <DeleteButton action={deleteTimetableAction} id={id(item)} /> : null}
                      </article>
                    ))
                  ) : (
                    <p className="text-sm font-semibold text-stone-500">No sessions scheduled.</p>
                  )}
                </div>
              </div>
            );
          })}
        </section>
      </div>
    </>
  );
}
