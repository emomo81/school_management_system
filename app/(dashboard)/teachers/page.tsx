import { Plus } from "lucide-react";
import { Field } from "@/components/Field";
import { PageHeader } from "@/components/PageHeader";
import { SubmitButton } from "@/components/SubmitButton";
import { createTeacherAction } from "@/lib/actions";
import { getTeachers, id } from "@/lib/queries";

export default async function TeachersPage() {
  const teachers = await getTeachers();

  return (
    <>
      <PageHeader title="Teachers" kicker="Staff records" />
      <div className="grid gap-6 lg:grid-cols-[380px_1fr]">
        <form action={createTeacherAction} className="h-fit rounded-md border border-stone-300 bg-white p-5 shadow-sm">
          <h2 className="mb-4 font-[var(--font-display)] text-xl font-bold">New Teacher</h2>
          <div className="grid gap-4">
            <Field label="Name" name="name" required />
            <Field label="Email" name="email" type="email" required />
            <Field label="Password" name="password" type="password" placeholder="teacher123" />
            <Field label="Phone" name="phone" />
            <Field label="Qualification" name="qualification" />
            <SubmitButton>
              <Plus className="size-4" />
              Add teacher
            </SubmitButton>
          </div>
        </form>

        <section className="grid gap-3">
          {teachers.map((teacher: any) => (
            <article key={id(teacher)} className="rounded-md border border-stone-300 bg-white p-5 shadow-sm">
              <h2 className="font-[var(--font-display)] text-xl font-bold">{teacher.user?.name}</h2>
              <p className="text-sm font-semibold text-stone-600">{teacher.user?.email}</p>
              <div className="mt-4 grid gap-2 text-sm md:grid-cols-2">
                <p><span className="font-bold">Phone:</span> {teacher.phone || "Not set"}</p>
                <p><span className="font-bold">Qualification:</span> {teacher.qualification || "Not set"}</p>
              </div>
            </article>
          ))}
        </section>
      </div>
    </>
  );
}
