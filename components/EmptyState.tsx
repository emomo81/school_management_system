import { Inbox } from "lucide-react";

export function EmptyState({ label }: { label: string }) {
  return (
    <div className="grid min-h-40 place-items-center rounded-md border border-dashed border-stone-300 bg-white/65 p-8 text-center">
      <div>
        <Inbox className="mx-auto mb-3 size-8 text-stone-400" />
        <p className="font-semibold text-stone-700">{label}</p>
      </div>
    </div>
  );
}
