import { CheckCircle2, Plus } from "lucide-react";
import { HiddenSubmit } from "@/components/DeleteButton";
import { Field, SelectField } from "@/components/Field";
import { PageHeader } from "@/components/PageHeader";
import { SubmitButton } from "@/components/SubmitButton";
import { createFeeAction, markFeePaidAction } from "@/lib/actions";
import { formatDate, money } from "@/lib/format";
import { getFees, getStudents, id } from "@/lib/queries";

export default async function FeesPage() {
  const [fees, students] = await Promise.all([getFees(), getStudents()]);

  return (
    <>
      <PageHeader title="Fees & Finance" kicker="Billing" />
      <div className="grid gap-6 lg:grid-cols-[380px_1fr]">
        <form action={createFeeAction} className="h-fit rounded-md border border-stone-300 bg-white p-5 shadow-sm">
          <h2 className="mb-4 font-[var(--font-display)] text-xl font-bold">New Fee</h2>
          <div className="grid gap-4">
            <SelectField label="Student" name="student" required options={students.map((student: any) => ({ value: id(student), label: `${student.user?.name} - ${student.admissionNo}` }))} />
            <Field label="Title" name="title" required />
            <Field label="Amount" name="amount" type="number" required />
            <SelectField label="Status" name="status" options={[{ value: "pending", label: "Pending" }, { value: "paid", label: "Paid" }, { value: "overdue", label: "Overdue" }]} />
            <Field label="Due date" name="dueDate" type="date" />
            <SubmitButton>
              <Plus className="size-4" />
              Add fee
            </SubmitButton>
          </div>
        </form>
        <section className="grid gap-3">
          {fees.map((fee: any) => (
            <article key={id(fee)} className="flex flex-col gap-4 rounded-md border border-stone-300 bg-white p-5 shadow-sm md:flex-row md:items-center md:justify-between">
              <div>
                <h2 className="font-[var(--font-display)] text-xl font-bold">{fee.title}</h2>
                <p className="text-sm text-stone-600">{fee.student?.user?.name} · {fee.student?.admissionNo}</p>
                <p className="mt-2 text-sm font-bold">{formatDate(fee.dueDate)}</p>
              </div>
              <div className="flex items-center gap-3">
                <span className="font-[var(--font-display)] text-2xl font-bold">{money(fee.amount)}</span>
                <span className="rounded bg-stone-100 px-2 py-1 text-xs font-bold capitalize text-stone-700">{fee.status}</span>
                {fee.status !== "paid" ? (
                  <HiddenSubmit action={markFeePaidAction} id={id(fee)}>
                    <CheckCircle2 className="size-4" />
                    Paid
                  </HiddenSubmit>
                ) : null}
              </div>
            </article>
          ))}
        </section>
      </div>
    </>
  );
}
