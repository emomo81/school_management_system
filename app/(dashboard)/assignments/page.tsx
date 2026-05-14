import { Plus } from "lucide-react";
import { DeleteButton } from "@/components/DeleteButton";
import { SelectField } from "@/components/Field";
import { PageHeader } from "@/components/PageHeader";
import { SubmitButton } from "@/components/SubmitButton";
import { createAssignmentAction, deleteAssignmentAction } from "@/lib/actions";
import { getAssignments, getClasses, getSubjects, getTeachers, id, label } from "@/lib/queries";

export default async function AssignmentsPage() {
  const [assignments, teachers, subjects, classes] = await Promise.all([getAssignments(), getTeachers(), getSubjects(), getClasses()]);

  return (
    <>
      <PageHeader title="Assignments" kicker="Teacher subject mapping" />
      <div className="grid gap-6 lg:grid-cols-[380px_1fr]">
        <form action={createAssignmentAction} className="h-fit rounded-md border border-stone-300 bg-white p-5 shadow-sm">
          <h2 className="mb-4 font-[var(--font-display)] text-xl font-bold">New Assignment</h2>
          <div className="grid gap-4">
            <SelectField label="Teacher" name="teacher" required options={teachers.map((teacher: any) => ({ value: id(teacher), label: teacher.user?.name || "Teacher" }))} />
            <SelectField label="Subject" name="subject" required options={subjects.map((subject: any) => ({ value: id(subject), label: subject.name }))} />
            <SelectField label="Program" name="schoolClass" required options={classes.map((schoolClass: any) => ({ value: id(schoolClass), label: `${schoolClass.name} ${schoolClass.section}` }))} />
            <SubmitButton>
              <Plus className="size-4" />
              Assign
            </SubmitButton>
          </div>
        </form>
        <section className="overflow-x-auto rounded-md border border-stone-300 bg-white shadow-sm">
          <table className="w-full min-w-[720px] text-left text-sm">
            <thead className="bg-stone-100 text-xs uppercase tracking-[0.14em] text-stone-600">
              <tr>
                <th className="px-4 py-3">Teacher</th>
                <th className="px-4 py-3">Subject</th>
                <th className="px-4 py-3">Program</th>
                <th className="px-4 py-3">Action</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-stone-200">
              {assignments.map((assignment: any) => (
                <tr key={id(assignment)}>
                  <td className="px-4 py-3 font-bold">{assignment.teacher?.user?.name}</td>
                  <td className="px-4 py-3">{label(assignment.subject)}</td>
                  <td className="px-4 py-3">{assignment.schoolClass ? `${assignment.schoolClass.name} ${assignment.schoolClass.section}` : "Not assigned"}</td>
                  <td className="px-4 py-3"><DeleteButton action={deleteAssignmentAction} id={id(assignment)} /></td>
                </tr>
              ))}
            </tbody>
          </table>
        </section>
      </div>
    </>
  );
}
