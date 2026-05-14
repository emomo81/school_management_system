import { Plus } from "lucide-react";
import { Field, SelectField } from "@/components/Field";
import { PageHeader } from "@/components/PageHeader";
import { SubmitButton } from "@/components/SubmitButton";
import { createClassAction } from "@/lib/actions";
import { getClasses, getDepartments, id, label } from "@/lib/queries";

export default async function ClassesPage() {
  const [classes, departments] = await Promise.all([getClasses(), getDepartments()]);
  const departmentOptions = departments.map((department: any) => ({ value: id(department), label: department.name }));

  return (
    <>
      <PageHeader title="Programs" kicker="Classes and sections" />
      <div className="grid gap-6 lg:grid-cols-[360px_1fr]">
        <form action={createClassAction} className="h-fit rounded-md border border-stone-300 bg-white p-5 shadow-sm">
          <h2 className="mb-4 font-[var(--font-display)] text-xl font-bold">New Program</h2>
          <div className="grid gap-4">
            <Field label="Name" name="name" placeholder="Grade 9" required />
            <Field label="Section" name="section" placeholder="A" required />
            <SelectField label="Department" name="department" options={departmentOptions} />
            <SubmitButton>
              <Plus className="size-4" />
              Add program
            </SubmitButton>
          </div>
        </form>

        <section className="grid gap-3 md:grid-cols-2">
          {classes.map((schoolClass: any) => (
            <article key={id(schoolClass)} className="rounded-md border border-stone-300 bg-white p-5 shadow-sm">
              <p className="text-xs font-bold uppercase tracking-[0.16em] text-emerald-800">{label(schoolClass.department)}</p>
              <h2 className="mt-2 font-[var(--font-display)] text-2xl font-bold text-stone-950">{schoolClass.name}</h2>
              <p className="mt-1 text-sm font-semibold text-stone-600">Section {schoolClass.section}</p>
            </article>
          ))}
        </section>
      </div>
    </>
  );
}
