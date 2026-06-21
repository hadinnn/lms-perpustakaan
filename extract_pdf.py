import pdfplumber

pdf_path = r"c:\Users\Hadin\.gemini\antigravity-ide\scratch\lms-perpustakaan\Contoh Laporan PAW - Tim Herlin.pdf"
output_path = r"c:\Users\Hadin\.gemini\antigravity-ide\scratch\lms-perpustakaan\pdf_content.txt"

with pdfplumber.open(pdf_path) as pdf:
    with open(output_path, 'w', encoding='utf-8') as out:
        out.write(f"Total pages: {len(pdf.pages)}\n")
        for i, page in enumerate(pdf.pages):
            text = page.extract_text()
            out.write(f"\n{'='*60}\n")
            out.write(f"=== PAGE {i+1} ===\n")
            out.write(f"{'='*60}\n")
            if text:
                out.write(text + "\n")
            else:
                out.write("[No text found on this page]\n")

print("Done! Saved to pdf_content.txt")
