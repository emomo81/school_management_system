import Link from "next/link";
import { ArrowLeft } from "lucide-react";
import { PageHeader } from "@/components/PageHeader";
import { dateInput, formatDate } from "@/lib/format";
import { getStudent, id, label } from "@/lib/queries";

export default async function StudentShowPage({ params }: { params: Promise<{ id: string }> }) {
  const { id: studentId } = await params;
  const student = await getStudent(studentId);

  return (
    <>
      <PageHeader
        title={student?.user?.name || "Student"}
        kicker="Student profile"
        action={
          <Link href="/students" className="focus-ring inline-flex h-10 items-center gap-2 rounded-md bg-stone-900 px-4 text-sm font-bold text-white">
            <ArrowLeft className="size-4" />
            Back
          </Link>
        }
      />
      <section className="grid gap-4 rounded-md border border-stone-300 bg-white p-5 shadow-sm md:grid-cols-2">
        <Info label="Email" value={student?.user?.email} />
        <Info label="Admission no." value={student?.admissionNo} />
        <Info label="Program" value={label(student?.schoolClass)} />
        <Info label="Academic year" value={label(student?.academicYear)} />
        <Info label="Gender" value={student?.gender || "Not set"} />
        <Info label="Date of birth" value={formatDate(student?.dob || dateInput(null))} />
        <Info label="Address" value={student?.address || "Not set"} wide />
      </section>
    </>
  );
}

function Info({ label, value, wide }: { label: string; value?: string; wide?: boolean }) {
  return (
    <div className={`rounded-md border border-stone-200 bg-stone-50 p-4 ${wide ? "md:col-span-2" : ""}`}>
      <p className="text-xs font-bold uppercase tracking-[0.16em] text-stone-500">{label}</p>
      <p className="mt-1 font-semibold text-stone-950">{value}</p>
    </div>
  );
}
