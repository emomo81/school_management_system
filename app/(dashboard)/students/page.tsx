import Link from "next/link";
import { Eye, Pencil, Plus } from "lucide-react";
import { DeleteButton } from "@/components/DeleteButton";
import { Field, SelectField, TextAreaField } from "@/components/Field";
import { PageHeader } from "@/components/PageHeader";
import { SubmitButton } from "@/components/SubmitButton";
import { createStudentAction, deleteStudentAction } from "@/lib/actions";
import { dateInput } from "@/lib/format";
import { getAcademicYears, getClasses, getStudents, id, label } from "@/lib/queries";

export default async function StudentsPage() {
  const [students, classes, years] = await Promise.all([getStudents(), getClasses(), getAcademicYears()]);
  const classOptions = classes.map((schoolClass: any) => ({ value: id(schoolClass), label: `${schoolClass.name} ${schoolClass.section}` }));
  const yearOptions = years.map((year: any) => ({ value: id(year), label: year.name }));

  return (
    <>
      <PageHeader title="Students" kicker="Enrollment" />
      <div className="grid gap-6 xl:grid-cols-[420px_1fr]">
        <form action={createStudentAction} className="h-fit rounded-md border border-stone-300 bg-white p-5 shadow-sm">
          <h2 className="mb-4 font-[var(--font-display)] text-xl font-bold">Enroll Student</h2>
          <div className="grid gap-4 md:grid-cols-2">
            <Field label="First name" name="firstName" required />
            <Field label="Last name" name="lastName" required />
            <Field label="Email" name="email" type="email" required />
            <Field label="Password" name="password" type="password" placeholder="student123" />
            <Field label="Admission no." name="admissionNo" required />
            <Field label="Date of birth" name="dob" type="date" />
            <SelectField label="Gender" name="gender" options={[{ value: "male", label: "Male" }, { value: "female", label: "Female" }, { value: "other", label: "Other" }]} />
            <SelectField label="Program" name="schoolClass" options={classOptions} />
            <SelectField label="Academic year" name="academicYear" options={yearOptions} />
            <TextAreaField label="Address" name="address" />
            <div className="md:col-span-2">
              <SubmitButton>
                <Plus className="size-4" />
                Enroll student
              </SubmitButton>
            </div>
          </div>
        </form>

        <section className="overflow-x-auto rounded-md border border-stone-300 bg-white shadow-sm">
          <table className="w-full min-w-[760px] text-left text-sm">
            <thead className="bg-stone-100 text-xs uppercase tracking-[0.14em] text-stone-600">
              <tr>
                <th className="px-4 py-3">Student</th>
                <th className="px-4 py-3">Admission</th>
                <th className="px-4 py-3">Program</th>
                <th className="px-4 py-3">Year</th>
                <th className="px-4 py-3">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-stone-200">
              {students.map((student: any) => (
                <tr key={id(student)}>
                  <td className="px-4 py-3">
                    <p className="font-bold">{student.user?.name}</p>
                    <p className="text-stone-600">{student.user?.email}</p>
                  </td>
                  <td className="px-4 py-3 font-mono">{student.admissionNo}</td>
                  <td className="px-4 py-3">{label(student.schoolClass)}</td>
                  <td className="px-4 py-3">{label(student.academicYear)}</td>
                  <td className="px-4 py-3">
                    <div className="flex flex-wrap gap-2">
                      <Link href={`/students/${id(student)}`} className="focus-ring inline-flex h-9 items-center gap-2 rounded-md bg-stone-200 px-3 font-bold text-stone-900 hover:bg-stone-300">
                        <Eye className="size-4" />
                        View
                      </Link>
                      <Link href={`/students/${id(student)}/edit`} className="focus-ring inline-flex h-9 items-center gap-2 rounded-md bg-amber-200 px-3 font-bold text-stone-950 hover:bg-amber-300">
                        <Pencil className="size-4" />
                        Edit
                      </Link>
                      <DeleteButton action={deleteStudentAction} id={id(student)} />
                    </div>
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
