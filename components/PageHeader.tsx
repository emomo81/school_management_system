type Props = {
  title: string;
  kicker?: string;
  action?: React.ReactNode;
};

export function PageHeader({ title, kicker, action }: Props) {
  return (
    <div className="mb-6 flex flex-col gap-3 border-b border-stone-300 pb-5 sm:flex-row sm:items-end sm:justify-between">
      <div>
        {kicker ? <p className="mb-1 text-xs font-bold uppercase tracking-[0.18em] text-emerald-800">{kicker}</p> : null}
        <h1 className="font-[var(--font-display)] text-2xl font-bold text-stone-950 sm:text-3xl">{title}</h1>
      </div>
      {action}
    </div>
  );
}
