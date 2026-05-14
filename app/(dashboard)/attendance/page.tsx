import Link from "next/link";
import { Save, Search } from "lucide-react";
import { Field, SelectField } from "@/components/Field";
import { PageHeader } from "@/components/PageHeader";
import { SubmitButton } from "@/components/SubmitButton";
import { saveAttendanceAction } from "@/lib/actions";
import { dateInput } from "@/lib/format";
import { getAttendanceForClass, getClasses, id } from "@/lib/queries";

export default async function AttendancePage({
  searchParams,
}: {
  searchParams: Promise<{ classId?: string; date?: string }>;
}) {
  const params = await searchParams;
  const selectedDate = params.date || dateInput(new Date());
  const [classes, attendance] = await Promise.all([getClasses(), getAttendanceForClass(params.classId, selectedDate)]);
  const recordsByStudent = new Map(attendance.records.map((record: any) => [String(record.student), record]));

  return (
    <>
      <PageHeader title="Attendance" kicker="Daily registers" />
      <form className="mb-6 grid gap-3 rounded-md border border-stone-300 bg-white p-5 shadow-sm md:grid-cols-[1fr_220px_auto]">
        <SelectField label="Program" name="classId" required defaultValue={params.classId} options={classes.map((schoolClass: any) => ({ value: id(schoolClass), label: `${schoolClass.name} ${schoolClass.section}` }))} />
        <Field label="Date" name="date" type="date" required defaultValue={selectedDate} />
        <div className="flex items-end">
          <button className="focus-ring inline-flex h-11 items-center gap-2 rounded-md bg-stone-900 px-4 text-sm font-bold text-white" type="submit">
            <Search className="size-4" />
            Load
          </button>
        </div>
      </form>

      {params.classId ? (
        <form action={saveAttendanceAction} className="rounded-md border border-stone-300 bg-white p-5 shadow-sm">
          <input type="hidden" name="schoolClass" value={params.classId} />
          <input type="hidden" name="date" value={selectedDate} />
          <div className="grid gap-3">
            {attendance.students.map((student: any) => {
              const record: any = recordsByStudent.get(id(student));
              return (
                <div key={id(student)} className="grid gap-3 rounded-md border border-stone-200 bg-stone-50 p-3 md:grid-cols-[1fr_180px_1fr]">
                  <input type="hidden" name="studentId" value={id(student)} />
                  <div>
                    <p className="font-bold">{student.user?.name}</p>
                    <p className="text-sm text-stone-600">{student.admissionNo}</p>
                  </div>
                  <select name={`status-${id(student)}`} defaultValue={record?.status || "present"} className="focus-ring h-10 rounded-md border border-stone-300 bg-white px-3">
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="late">Late</option>
                    <option value="excused">Excused</option>
                  </select>
                  <input name={`remarks-${id(student)}`} defaultValue={record?.remarks || ""} placeholder="Remarks" className="focus-ring h-10 rounded-md border border-stone-300 bg-white px-3" />
                </div>
              );
            })}
          </div>
          <div className="mt-5">
            <SubmitButton>
              <Save className="size-4" />
              Save attendance
            </SubmitButton>
          </div>
        </form>
      ) : (
        <Link href={`/attendance?date=${selectedDate}`} className="text-sm font-bold text-emerald-800">Choose a program to take attendance.</Link>
      )}
    </>
  );
}
