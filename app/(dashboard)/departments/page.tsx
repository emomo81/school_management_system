import { Plus } from "lucide-react";
import { DeleteButton } from "@/components/DeleteButton";
import { Field } from "@/components/Field";
import { PageHeader } from "@/components/PageHeader";
import { SubmitButton } from "@/components/SubmitButton";
import { createDepartmentAction, deleteDepartmentAction, updateDepartmentAction } from "@/lib/actions";
import { getDepartments, id } from "@/lib/queries";

export default async function DepartmentsPage() {
  const departments = await getDepartments();

  return (
    <>
      <PageHeader title="Departments" kicker="Academic structure" />
      <div className="grid gap-6 lg:grid-cols-[360px_1fr]">
        <form action={createDepartmentAction} className="h-fit rounded-md border border-stone-300 bg-white p-5 shadow-sm">
          <h2 className="mb-4 font-[var(--font-display)] text-xl font-bold">New Department</h2>
          <div className="grid gap-4">
            <Field label="Name" name="name" required />
            <Field label="Code" name="code" required />
            <SubmitButton>
              <Plus className="size-4" />
              Add department
            </SubmitButton>
          </div>
        </form>

        <section className="overflow-x-auto rounded-md border border-stone-300 bg-white shadow-sm">
          <table className="w-full min-w-[700px] text-left text-sm">
            <thead className="bg-stone-100 text-xs uppercase tracking-[0.14em] text-stone-600">
              <tr>
                <th className="px-4 py-3">Department</th>
                <th className="px-4 py-3">Code</th>
                <th className="px-4 py-3">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-stone-200">
              {departments.map((department: any) => (
                <tr key={id(department)}>
                  <td className="px-4 py-3">
                    <form action={updateDepartmentAction} className="grid gap-2 md:grid-cols-[1fr_120px_auto]">
                      <input type="hidden" name="id" value={id(department)} />
                      <input className="focus-ring h-10 rounded-md border border-stone-300 px-3" name="name" defaultValue={department.name} />
                      <input className="focus-ring h-10 rounded-md border border-stone-300 px-3" name="code" defaultValue={department.code} />
                      <SubmitButton variant="quiet">Save</SubmitButton>
                    </form>
                  </td>
                  <td className="px-4 py-3 font-mono text-stone-600">{department.code}</td>
                  <td className="px-4 py-3">
                    <DeleteButton action={deleteDepartmentAction} id={id(department)} />
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </section>
      </div>
    </>
  );
}
