import Link from "next/link";
import { redirect } from "next/navigation";
import { LogOut, UserRound, GraduationCap } from "lucide-react";
import { logoutAction } from "@/lib/actions";
import { getSessionUser } from "@/lib/auth";
import { navItems } from "@/lib/navigation";

export default async function DashboardLayout({ children }: { children: React.ReactNode }) {
  const user = await getSessionUser();
  if (!user) redirect("/login");
  const items = navItems.filter((item) => item.roles.includes(user.role));

  return (
    <div className="min-h-screen lg:grid lg:grid-cols-[280px_1fr]">
      <aside className="hidden border-r border-emerald-950/15 bg-stone-950 text-stone-100 lg:block lg:min-h-screen">
        <div className="flex items-center gap-3 border-b border-white/10 px-5 py-5">
          <div className="grid size-10 place-items-center rounded-md bg-amber-300 text-emerald-950">
            <GraduationCap className="size-6" />
          </div>
          <div>
            <div className="font-[var(--font-display)] text-lg font-bold">SchoolSys</div>
            <div className="text-xs font-semibold uppercase tracking-[0.16em] text-stone-400">Operations</div>
          </div>
        </div>

        <nav className="grid gap-1 px-3 py-4">
          {items.map((item) => {
            const Icon = item.icon;
            return (
              <Link
                key={item.href}
                href={item.href}
                className="focus-ring flex items-center gap-3 rounded-md px-3 py-2.5 text-sm font-semibold text-stone-300 hover:bg-white/10 hover:text-white"
              >
                <Icon className="size-4 text-amber-300" />
                {item.label}
              </Link>
            );
          })}
        </nav>
      </aside>

      <div className="min-w-0">
        <div className="border-b border-white/10 bg-stone-950 text-stone-100 lg:hidden">
          <div className="flex items-center gap-3 px-4 py-4">
            <div className="grid size-10 shrink-0 place-items-center rounded-md bg-amber-300 text-emerald-950">
              <GraduationCap className="size-6" />
            </div>
            <div className="min-w-0">
              <div className="font-[var(--font-display)] text-lg font-bold">SchoolSys</div>
              <div className="text-xs font-semibold uppercase tracking-[0.16em] text-stone-400">Operations</div>
            </div>
          </div>
          <nav className="flex gap-2 overflow-x-auto px-4 pb-4">
            {items.map((item) => {
              const Icon = item.icon;
              return (
                <Link
                  key={item.href}
                  href={item.href}
                  className="focus-ring inline-flex h-10 shrink-0 items-center gap-2 rounded-md bg-white/10 px-3 text-sm font-semibold text-stone-100"
                >
                  <Icon className="size-4 text-amber-300" />
                  {item.label}
                </Link>
              );
            })}
          </nav>
        </div>
        <header className="sticky top-0 z-10 flex items-center justify-between border-b border-stone-300 bg-[#f6f3ec]/90 px-5 py-4 backdrop-blur">
          <div>
            <p className="text-xs font-bold uppercase tracking-[0.18em] text-emerald-800">{user.role}</p>
            <p className="font-[var(--font-display)] text-lg font-bold text-stone-950">{user.name}</p>
          </div>
          <div className="flex items-center gap-3">
            <Link href="/profile" className="focus-ring grid size-10 place-items-center rounded-md border border-stone-300 bg-white text-stone-800 shadow-sm">
              <UserRound className="size-5" />
            </Link>
            <form action={logoutAction}>
              <button className="focus-ring grid size-10 place-items-center rounded-md border border-red-200 bg-red-50 text-red-700 shadow-sm" type="submit">
                <LogOut className="size-5" />
              </button>
            </form>
          </div>
        </header>
        <main className="mx-auto max-w-7xl px-4 py-5 sm:px-5 sm:py-7">{children}</main>
      </div>
    </div>
  );
}
