type FieldProps = {
  label: string;
  name: string;
  type?: string;
  required?: boolean;
  defaultValue?: string | number;
  placeholder?: string;
};

export function Field({ label, name, type = "text", required, defaultValue, placeholder }: FieldProps) {
  return (
    <label className="grid gap-1.5 text-sm font-semibold text-stone-700">
      {label}
      <input
        className="focus-ring h-11 rounded-md border border-stone-300 bg-white px-3 text-stone-950 shadow-sm"
        name={name}
        type={type}
        required={required}
        defaultValue={defaultValue}
        placeholder={placeholder}
      />
    </label>
  );
}

type SelectProps = {
  label: string;
  name: string;
  required?: boolean;
  defaultValue?: string;
  options: Array<{ value: string; label: string }>;
};

export function SelectField({ label, name, required, defaultValue, options }: SelectProps) {
  return (
    <label className="grid gap-1.5 text-sm font-semibold text-stone-700">
      {label}
      <select
        className="focus-ring h-11 rounded-md border border-stone-300 bg-white px-3 text-stone-950 shadow-sm"
        name={name}
        required={required}
        defaultValue={defaultValue || ""}
      >
        <option value="">Select</option>
        {options.map((option) => (
          <option key={option.value} value={option.value}>
            {option.label}
          </option>
        ))}
      </select>
    </label>
  );
}

export function TextAreaField({ label, name, required, defaultValue }: Omit<FieldProps, "type">) {
  return (
    <label className="grid gap-1.5 text-sm font-semibold text-stone-700 md:col-span-2">
      {label}
      <textarea
        className="focus-ring min-h-28 rounded-md border border-stone-300 bg-white px-3 py-2 text-stone-950 shadow-sm"
        name={name}
        required={required}
        defaultValue={defaultValue}
      />
    </label>
  );
}
