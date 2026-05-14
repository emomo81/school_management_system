import { CheckCircle2, Plus } from "lucide-react";
import { DeleteButton, HiddenSubmit } from "@/components/DeleteButton";
import { Field } from "@/components/Field";
import { PageHeader } from "@/components/PageHeader";
import { SubmitButton } from "@/components/SubmitButton";
import { activateAcademicYearAction, createAcademicYearAction, deleteAcademicYearAction } from "@/lib/actions";
import { dateInput, formatDate } from "@/lib/format";
import { getAcademicYears, id } from "@/lib/queries";

export default async function AcademicYearsPage() {
  const years = await getAcademicYears();

  return (
    <>
      <PageHeader title="Academic Years" kicker="Enrollment periods" />
      <div className="grid gap-6 lg:grid-cols-[380px_1fr]">
        <form action={createAcademicYearAction} className="h-fit rounded-md border border-stone-300 bg-white p-5 shadow-sm">
          <h2 className="mb-4 font-[var(--font-display)] text-xl font-bold">New Academic Year</h2>
          <div className="grid gap-4">
            <Field label="Name" name="name" placeholder="2026 Academic Year" required />
            <Field label="Start date" name="startDate" type="date" required defaultValue={dateInput(new Date())} />
            <Field label="End date" name="endDate" type="date" required />
            <SubmitButton>
              <Plus className="size-4" />
              Add year
            </SubmitButton>
          </div>
        </form>

        <section className="grid gap-3">
          {years.map((year: any) => (
            <article key={id(year)} className="flex flex-col gap-3 rounded-md border border-stone-300 bg-white p-4 shadow-sm md:flex-row md:items-center md:justify-between">
              <div>
                <div className="flex items-center gap-2">
                  <h2 className="font-[var(--font-display)] text-lg font-bold">{year.name}</h2>
                  {year.isActive ? <span className="rounded bg-emerald-100 px-2 py-1 text-xs font-bold text-emerald-800">Active</span> : null}
                </div>
                <p className="text-sm text-stone-600">
                  {formatDate(year.startDate)} to {formatDate(year.endDate)}
                </p>
              </div>
              <div className="flex gap-2">
                {!year.isActive ? (
                  <HiddenSubmit action={activateAcademicYearAction} id={id(year)}>
                    <CheckCircle2 className="size-4" />
                    Make active
                  </HiddenSubmit>
                ) : null}
                <DeleteButton action={deleteAcademicYearAction} id={id(year)} />
              </div>
            </article>
          ))}
        </section>
      </div>
    </>
  );
}
