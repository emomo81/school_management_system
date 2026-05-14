import { Save } from "lucide-react";
import { Field } from "@/components/Field";
import { PageHeader } from "@/components/PageHeader";
import { SubmitButton } from "@/components/SubmitButton";
import { updateProfileAction } from "@/lib/actions";
import { requireUser } from "@/lib/auth";
import { getProfile } from "@/lib/queries";

export default async function ProfilePage({
  searchParams,
}: {
  searchParams: Promise<{ updated?: string }>;
}) {
  const session = await requireUser();
  const params = await searchParams;
  const user = await getProfile(session.id);

  return (
    <>
      <PageHeader title="Profile" kicker="Account settings" />
      <form action={updateProfileAction} className="max-w-2xl rounded-md border border-stone-300 bg-white p-5 shadow-sm">
        {params.updated ? <div className="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-bold text-emerald-800">Profile updated.</div> : null}
        <div className="grid gap-4">
          <Field label="Name" name="name" required defaultValue={user?.name} />
          <Field label="Email" name="email" type="email" required defaultValue={user?.email} />
          <Field label="New password" name="password" type="password" />
          <SubmitButton>
            <Save className="size-4" />
            Save profile
          </SubmitButton>
        </div>
      </form>
    </>
  );
}
