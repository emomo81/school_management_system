import { GraduationCap, LogIn } from "lucide-react";
import { loginAction } from "@/lib/actions";
import { SubmitButton } from "@/components/SubmitButton";

export default async function LoginPage({
  searchParams,
}: {
  searchParams: Promise<{ error?: string }>;
}) {
  const params = await searchParams;

  return (
    <section className="overflow-hidden rounded-lg border border-stone-300 bg-white shadow-xl">
      <div className="border-b border-stone-200 bg-emerald-950 px-8 py-7 text-white">
        <div className="mb-5 flex size-12 items-center justify-center rounded-md bg-amber-300 text-emerald-950">
          <GraduationCap className="size-7" />
        </div>
        <h1 className="font-[var(--font-display)] text-3xl font-bold">SchoolSys</h1>
        <p className="mt-2 text-sm text-emerald-50">Sign in to manage students, staff, attendance, fees, and exams.</p>
      </div>

      <form action={loginAction} className="grid gap-4 px-8 py-7">
        {params.error ? (
          <div className="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm font-semibold text-red-800">
            Invalid email or password.
          </div>
        ) : null}
        <label className="grid gap-1.5 text-sm font-bold text-stone-700">
          Email
          <input className="focus-ring h-11 rounded-md border border-stone-300 px-3" name="email" type="email" required defaultValue="admin@school.com" />
        </label>
        <label className="grid gap-1.5 text-sm font-bold text-stone-700">
          Password
          <input className="focus-ring h-11 rounded-md border border-stone-300 px-3" name="password" type="password" required defaultValue="admin123" />
        </label>
        <SubmitButton>
          <LogIn className="size-4" />
          Sign in
        </SubmitButton>
      </form>
    </section>
  );
}
