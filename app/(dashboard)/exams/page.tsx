import { Plus, Save } from "lucide-react";
import { Field, SelectField } from "@/components/Field";
import { PageHeader } from "@/components/PageHeader";
import { SubmitButton } from "@/components/SubmitButton";
import { createExamAction, saveMarkAction } from "@/lib/actions";
import { formatDate } from "@/lib/format";
import { getDepartments, getExams, getMarks, getClasses, getStudents, getSubjects, id, label } from "@/lib/queries";

export default async function ExamsPage() {
  const [exams, marks, departments, classes, students, subjects] = await Promise.all([
    getExams(),
    getMarks(),
    getDepartments(),
    getClasses(),
    getStudents(),
    getSubjects(),
  ]);

  return (
    <>
      <PageHeader title="Exams & Marks" kicker="Assessment" />
      <div className="grid gap-6 xl:grid-cols-[380px_1fr]">
        <div className="grid h-fit gap-6">
          <form action={createExamAction} className="rounded-md border border-stone-300 bg-white p-5 shadow-sm">
            <h2 className="mb-4 font-[var(--font-display)] text-xl font-bold">New Exam</h2>
            <div className="grid gap-4">
              <Field label="Name" name="name" required />
              <Field label="Date" name="date" type="date" required />
              <SelectField label="Department" name="department" options={departments.map((department: any) => ({ value: id(department), label: department.name }))} />
              <SelectField label="Program" name="program" options={classes.map((schoolClass: any) => ({ value: id(schoolClass), label: `${schoolClass.name} ${schoolClass.section}` }))} />
              <Field label="Term" name="term" placeholder="Term 1" />
              <SubmitButton>
                <Plus className="size-4" />
                Add exam
              </SubmitButton>
            </div>
          </form>

          <form action={saveMarkAction} className="rounded-md border border-stone-300 bg-white p-5 shadow-sm">
            <h2 className="mb-4 font-[var(--font-display)] text-xl font-bold">Enter Mark</h2>
            <div className="grid gap-4">
              <SelectField label="Exam" name="exam" required options={exams.map((exam: any) => ({ value: id(exam), label: exam.name }))} />
              <SelectField label="Student" name="student" required options={students.map((student: any) => ({ value: id(student), label: `${student.user?.name} - ${student.admissionNo}` }))} />
              <SelectField label="Subject" name="subject" required options={subjects.map((subject: any) => ({ value: id(subject), label: subject.name }))} />
              <Field label="Score" name="score" type="number" required />
              <Field label="Total" name="total" type="number" defaultValue={100} />
              <SubmitButton>
                <Save className="size-4" />
                Save mark
              </SubmitButton>
            </div>
          </form>
        </div>

        <div className="grid gap-6">
          <section className="overflow-x-auto rounded-md border border-stone-300 bg-white shadow-sm">
            <table className="w-full min-w-[680px] text-left text-sm">
              <thead className="bg-stone-100 text-xs uppercase tracking-[0.14em] text-stone-600">
                <tr>
                  <th className="px-4 py-3">Exam</th>
                  <th className="px-4 py-3">Date</th>
                  <th className="px-4 py-3">Program</th>
                  <th className="px-4 py-3">Term</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-stone-200">
                {exams.map((exam: any) => (
                  <tr key={id(exam)}>
                    <td className="px-4 py-3 font-bold">{exam.name}</td>
                    <td className="px-4 py-3">{formatDate(exam.date)}</td>
                    <td className="px-4 py-3">{label(exam.program)}</td>
                    <td className="px-4 py-3">{exam.term || "Not set"}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </section>

          <section className="overflow-x-auto rounded-md border border-stone-300 bg-white shadow-sm">
            <table className="w-full min-w-[680px] text-left text-sm">
              <thead className="bg-stone-100 text-xs uppercase tracking-[0.14em] text-stone-600">
                <tr>
                  <th className="px-4 py-3">Student</th>
                  <th className="px-4 py-3">Exam</th>
                  <th className="px-4 py-3">Subject</th>
                  <th className="px-4 py-3">Score</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-stone-200">
                {marks.map((mark: any) => (
                  <tr key={id(mark)}>
                    <td className="px-4 py-3 font-bold">{mark.student?.user?.name}</td>
                    <td className="px-4 py-3">{label(mark.exam)}</td>
                    <td className="px-4 py-3">{label(mark.subject)}</td>
                    <td className="px-4 py-3">{mark.score}/{mark.total}</td>
                  </tr>
                ))}
              </tbody>
            </table>
          </section>
        </div>
      </div>
    </>
  );
}
