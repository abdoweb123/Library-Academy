
{{-- Print --}}
<script>
    function printDiv(divId, title = "") {
        var content = document.getElementById(divId).innerHTML;

        var printWindow = window.open('', '', 'width=900,height=650');
        printWindow.document.write(`
        <html>
            <head>
                   <title>${title}</title>
                <!-- Bootstrap 5 -->
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
                <style>
                    body { font-family: Arial, sans-serif; padding:20px; }
                    .text_no_justify { text-align: right; }
                    .ledger_number{
                        margin: 0 100px;
                    }
                    @media print {
                        tfoot {
                            display: table-row-group; /* يخلي التذييل زي باقي الصفوف */
                        }
                        svg:not(:root).svg-inline--fa{
                            width: 20px;
                        }
                    }
                </style>
            </head>
            <body>
                ${content}
            </body>
        </html>
    `);

        printWindow.document.close();

        // استنى لما الصفحة تتحمل بالكامل وبعدين اطبع
        printWindow.onload = function () {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        };
    }
</script>


{{-- Export Excel Table --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportTableToExcel(tableId, filename = 'display_ledger') {
        var table = document.getElementById(tableId);
        if (!table) {
            alert("⚠️ الجدول غير موجود: " + tableId);
            return;
        }

        // نسخة مؤقتة
        var clone = table.cloneNode(true);

        // امسح كل عناصر fontawesome (i, svg)
        clone.querySelectorAll('i, svg').forEach(el => el.remove());

        // كمان نظف الخلايا من أي بقايا نص جاي من FA
        clone.querySelectorAll('td, th').forEach(cell => {
            let txt = cell.innerText
                .replace(/fontawesome\.com/gi, '') // امسح كلمة fontawesome.com
                .replace(/Font Awesome/gi, '')     // امسح كلمة Font Awesome
                .replace(/→/g, '')                 // امسح السهم
                .trim();
            cell.innerText = txt;
        });

        var wb = XLSX.utils.table_to_book(clone, { sheet: "Sheet1" });
        XLSX.writeFile(wb, filename.endsWith('.xlsx') ? filename : filename + '.xlsx');
    }
</script>




{{-- PDF --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
<script>
    function exportTableToPDF(elementId = 'container_div', filename = 'display_ledger') {
        // جلب العنصر
        var element = document.getElementById(elementId);

        if (!element) {
            alert("⚠️ العنصر غير موجود: " + elementId);
            return;
        }

        // إعدادات pdf
        var opt = {
            margin:       0.5,
            filename:     filename + ".pdf", // ✅ اسم ديناميكي
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
        };

        // تحويل وتنزيل
        html2pdf().from(element).set(opt).save();
    }
</script>
