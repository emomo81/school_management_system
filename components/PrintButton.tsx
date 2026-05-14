"use client";

import { Printer } from "lucide-react";

export function PrintButton() {
  function handlePrint() {
    const printable = document.querySelector(".print-area");
    if (!printable) {
      window.print();
      return;
    }

    const printWindow = window.open("", "_blank", "width=900,height=1200");
    if (!printWindow) {
      window.print();
      return;
    }

    const styles = Array.from(document.querySelectorAll('style, link[rel="stylesheet"]'))
      .map((node) => node.outerHTML)
      .join("\n");

    printWindow.document.open();
    printWindow.document.write(`
      <!doctype html>
      <html>
        <head>
          <title>Report Card</title>
          ${styles}
          <style>
            @page { size: A4; margin: 12mm; }
            body { background: #ffffff !important; margin: 0; }
            .print-hidden { display: none !important; }
            .print-area { width: 100% !important; max-width: none !important; overflow: visible !important; }
            .print-card { box-shadow: none !important; border: 1px solid #222 !important; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
          </style>
        </head>
        <body>
          ${printable.outerHTML}
          <script>
            window.addEventListener("load", () => {
              window.focus();
              setTimeout(() => {
                window.print();
                window.close();
              }, 250);
            });
          </script>
        </body>
      </html>
    `);
    printWindow.document.close();
  }

  return (
    <button
      type="button"
      onClick={handlePrint}
      className="print-hidden focus-ring inline-flex h-10 items-center gap-2 rounded-md bg-emerald-900 px-4 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-950"
    >
      <Printer className="size-4" />
      Print report
    </button>
  );
}
