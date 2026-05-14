import { Trash2 } from "lucide-react";
import { SubmitButton } from "@/components/SubmitButton";

export function DeleteButton({ action, id, label = "Delete" }: { action: (formData: FormData) => Promise<void>; id: string; label?: string }) {
  return (
    <form action={action}>
      <input type="hidden" name="id" value={id} />
      <button
        type="submit"
        className="focus-ring inline-flex h-9 items-center gap-2 rounded-md border border-red-200 bg-red-50 px-3 text-sm font-bold text-red-700 hover:bg-red-100"
      >
        <Trash2 className="size-4" />
        {label}
      </button>
    </form>
  );
}

export function HiddenSubmit({ action, id, children }: { action: (formData: FormData) => Promise<void>; id: string; children: React.ReactNode }) {
  return (
    <form action={action}>
      <input type="hidden" name="id" value={id} />
      <SubmitButton variant="quiet">{children}</SubmitButton>
    </form>
  );
}
