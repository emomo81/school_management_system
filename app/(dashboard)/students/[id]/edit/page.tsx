import Link from "next/link";
import { ArrowLeft, Save } from "lucide-react";
import { Field, SelectField, TextAreaField } from "@/components/Field";
import { PageHeader } from "@/components/PageHeader";
import { SubmitButton } from "@/components/SubmitButton";
import { updateStudentAction } from "@/lib/actions";
import { dateInput } from "@/lib/format";
import { getAcademicYears, getClasses, getStudent, id } from "@/lib/queries";

export default async function StudentEditPage({ params }: { params: Promise<{ id: string }> }) {
  const { id: studentId } = await params;
  const [student, classes, years] = await Promise.all([getStudent(studentId), getClasses(), getAcademicYears()]);
  const [firstName, ...rest] = String(student?.user?.name || "").split(" ");
  const classOptions = classes.map((schoolClass: any) => ({ value: id(schoolClass), label: `${schoolClass.name} ${schoolClass.section}` }));
  const yearOptions = years.map((year: any) => ({ value: id(year), label: year.name }));

  return (
    <>
      <PageHeader
        title="Edit Student"
        kicker={student?.admissionNo}
        action={
          <Link href="/students" className="focus-ring inline-flex h-10 items-center gap-2 rounded-md bg-stone-900 px-4 text-sm font-bold text-white">
            <ArrowLeft className="size-4" />
            Back
          </Link>
        }
      />
      <form action={updateStudentAction} className="rounded-md border border-stone-300 bg-white p-5 shadow-sm">
        <input type="hidden" name="id" value={studentId} />
        <div className="grid gap-4 md:grid-cols-2">
          <Field label="First name" name="firstName" required defaultValue={firstName} />
          <Field label="Last name" name="lastName" required defaultValue={rest.join(" ")} />
          <Field label="Email" name="email" type="email" required defaultValue={student?.user?.email} />
          <Field label="New password" name="password" type="password" />
          <Field label="Admission no." name="admissionNo" required defaultValue={student?.admissionNo} />
          <Field label="Date of birth" name="dob" type="date" defaultValue={dateInput(student?.dob)} />
          <SelectField label="Gender" name="gender" defaultValue={student?.gender} options={[{ value: "male", label: "Male" }, { value: "female", label: "Female" }, { value: "other", label: "Other" }]} />
          <SelectField label="Program" name="schoolClass" defaultValue={id(student?.schoolClass)} options={classOptions} />
          <SelectField label="Academic year" name="academicYear" defaultValue={id(student?.academicYear)} options={yearOptions} />
          <TextAreaField label="Address" name="address" defaultValue={student?.address} />
          <div className="md:col-span-2">
            <SubmitButton>
              <Save className="size-4" />
              Save student
            </SubmitButton>
          </div>
        </div>
      </form>
    </>
  );
}
