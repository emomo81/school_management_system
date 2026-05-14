import { Plus } from "lucide-react";
import { DeleteButton } from "@/components/DeleteButton";
import { Field, TextAreaField } from "@/components/Field";
import { PageHeader } from "@/components/PageHeader";
import { SubmitButton } from "@/components/SubmitButton";
import { createNoticeAction, deleteNoticeAction } from "@/lib/actions";
import { formatDate } from "@/lib/format";
import { getNotices, id } from "@/lib/queries";

export default async function NoticesPage() {
  const notices = await getNotices();

  return (
    <>
      <PageHeader title="Noticeboard" kicker="Announcements" />
      <div className="grid gap-6 lg:grid-cols-[380px_1fr]">
        <form action={createNoticeAction} className="h-fit rounded-md border border-stone-300 bg-white p-5 shadow-sm">
          <h2 className="mb-4 font-[var(--font-display)] text-xl font-bold">New Notice</h2>
          <div className="grid gap-4">
            <Field label="Title" name="title" required />
            <TextAreaField label="Content" name="content" required />
            <SubmitButton>
              <Plus className="size-4" />
              Publish
            </SubmitButton>
          </div>
        </form>
        <section className="grid gap-3">
          {notices.map((notice: any) => (
            <article key={id(notice)} className="rounded-md border border-stone-300 bg-white p-5 shadow-sm">
              <div className="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                <div>
                  <h2 className="font-[var(--font-display)] text-xl font-bold">{notice.title}</h2>
                  <p className="mt-1 text-sm font-semibold text-stone-500">{formatDate(notice.createdAt)}</p>
                </div>
                <DeleteButton action={deleteNoticeAction} id={id(notice)} />
              </div>
              <p className="mt-4 whitespace-pre-line text-stone-700">{notice.content}</p>
            </article>
          ))}
        </section>
      </div>
    </>
  );
}
