"use client";

import { useFormStatus } from "react-dom";
import { Loader2 } from "lucide-react";

type Props = {
  children: React.ReactNode;
  variant?: "primary" | "quiet" | "danger";
};

export function SubmitButton({ children, variant = "primary" }: Props) {
  const { pending } = useFormStatus();
  const className =
    variant === "danger"
      ? "bg-red-700 text-white hover:bg-red-800"
      : variant === "quiet"
        ? "bg-stone-200 text-stone-900 hover:bg-stone-300"
        : "bg-emerald-800 text-white hover:bg-emerald-900";

  return (
    <button
      type="submit"
      disabled={pending}
      className={`focus-ring inline-flex h-10 items-center justify-center gap-2 rounded-md px-4 text-sm font-bold transition disabled:cursor-not-allowed disabled:opacity-65 ${className}`}
    >
      {pending ? <Loader2 className="size-4 animate-spin" /> : null}
      {children}
    </button>
  );
}
