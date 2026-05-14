import { Plus } from "lucide-react";
import { Field, SelectField } from "@/components/Field";
import { PageHeader } from "@/components/PageHeader";
import { SubmitButton } from "@/components/SubmitButton";
import { createSubjectAction } from "@/lib/actions";
import { getDepartments, getSubjects, id, label } from "@/lib/queries";

export default async function SubjectsPage() {
  const [subjects, departments] = await Promise.all([getSubjects(), getDepartments()]);
  const departmentOptions = departments.map((department: any) => ({ value: id(department), label: department.name }));

  return (
    <>
      <PageHeader title="Subjects" kicker="Curriculum" />
      <div className="grid gap-6 lg:grid-cols-[360px_1fr]">
        <form action={createSubjectAction} className="h-fit rounded-md border border-stone-300 bg-white p-5 shadow-sm">
          <h2 className="mb-4 font-[var(--font-display)] text-xl font-bold">New Subject</h2>
          <div className="grid gap-4">
            <Field label="Name" name="name" required />
            <Field label="Code" name="code" required />
            <SelectField label="Department" name="department" options={departmentOptions} />
            <Field label="Credits" name="credits" type="number" defaultValue={0} />
            <Field label="Total marks" name="totalMarks" type="number" defaultValue={100} />
            <SubmitButton>
              <Plus className="size-4" />
              Add subject
            </SubmitButton>
          </div>
        </form>

        <section className="overflow-x-auto rounded-md border border-stone-300 bg-white shadow-sm">
          <table className="w-full min-w-[680px] text-left text-sm">
            <thead className="bg-stone-100 text-xs uppercase tracking-[0.14em] text-stone-600">
              <tr>
                <th className="px-4 py-3">Subject</th>
                <th className="px-4 py-3">Code</th>
                <th className="px-4 py-3">Department</th>
                <th className="px-4 py-3">Marks</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-stone-200">
              {subjects.map((subject: any) => (
                <tr key={id(subject)}>
                  <td className="px-4 py-3 font-bold">{subject.name}</td>
                  <td className="px-4 py-3 font-mono text-stone-600">{subject.code}</td>
                  <td className="px-4 py-3">{label(subject.department)}</td>
                  <td className="px-4 py-3">{subject.totalMarks || 100}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </section>
      </div>
    </>
  );
}
