/**
 * CKEditor 5 Initializer
 * Uses the official `ckeditor5` NPM package (GPL / Open Source).
 * Includes Direct Image Uploading, Office/Word paste sanitization, and rich Arabic typography controls.
 */
import {
    ClassicEditor,
    Essentials,
    Bold,
    Italic,
    Underline,
    Strikethrough,
    Link,
    Paragraph,
    Heading,
    BlockQuote,
    List,
    Indent,
    IndentBlock,
    Alignment,
    Table,
    TableToolbar,
    TableProperties,
    TableCellProperties,
    TableColumnResize,
    TableCaption,
    Image,
    ImageCaption,
    ImageStyle,
    ImageToolbar,
    ImageUpload,
    ImageInsert,
    ImageResize,
    AutoImage,
    SimpleUploadAdapter,
    MediaEmbed,
    HorizontalLine,
    SourceEditing,
    GeneralHtmlSupport,
    PasteFromOffice,
    FontFamily,
    FontSize,
    FontColor,
    FontBackgroundColor,
    RemoveFormat,
} from 'ckeditor5';

import 'ckeditor5/ckeditor5.css';

function initEditor() {
    const contentEl = document.querySelector('#content');
    if (!contentEl) return; // Only initialize when the element exists

    // Guard against double-initialization
    if (contentEl.dataset.ckeditorReady === 'true') return;
    contentEl.dataset.ckeditorReady = 'true';

    // Fetch CSRF Token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    ClassicEditor
        .create(contentEl, {
            licenseKey: 'GPL',
            plugins: [
                Essentials,
                Bold,
                Italic,
                Underline,
                Strikethrough,
                Link,
                Paragraph,
                Heading,
                BlockQuote,
                List,
                Indent,
                IndentBlock,
                Alignment,
                Table,
                TableToolbar,
                TableProperties,
                TableCellProperties,
                TableColumnResize,
                TableCaption,
                Image,
                ImageCaption,
                ImageStyle,
                ImageToolbar,
                ImageUpload,
                ImageInsert,
                ImageResize,
                AutoImage,
                SimpleUploadAdapter,
                MediaEmbed,
                HorizontalLine,
                SourceEditing,
                GeneralHtmlSupport,
                PasteFromOffice,
                FontFamily,
                FontSize,
                FontColor,
                FontBackgroundColor,
                RemoveFormat,
            ],
            toolbar: {
                items: [
                    'heading', '|',
                    'fontFamily', 'fontSize', '|',
                    'fontColor', 'fontBackgroundColor', '|',
                    'bold', 'italic', 'underline', 'strikethrough', 'removeFormat', '|',
                    'link', 'insertImage', 'mediaEmbed', 'insertTable', 'blockQuote', 'horizontalLine', '|',
                    'bulletedList', 'numberedList', '|',
                    'alignment', 'outdent', 'indent', '|',
                    'sourceEditing', '|',
                    'undo', 'redo',
                ],
                shouldNotGroupWhenFull: false,
            },
            simpleUpload: {
                uploadUrl: '/admin/crafts/upload-image',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            },
            fontFamily: {
                options: [
                    'default',
                    'Amiri, serif',
                    'Tajawal, sans-serif',
                    'Cairo, sans-serif',
                    'Traditional Arabic, serif',
                    'Arial, Helvetica, sans-serif',
                    'Times New Roman, Times, serif',
                ],
                supportAllValues: true,
            },
            fontSize: {
                options: [
                    10,
                    12,
                    14,
                    'default',
                    18,
                    20,
                    22,
                    24,
                    28,
                    32,
                ],
                supportAllValues: true,
            },
            fontColor: {
                colors: [
                    { color: '#1A2F4C', label: 'كحلي داكن (أساسي)' },
                    { color: '#E67E22', label: 'عنبري / برتقالي' },
                    { color: '#D4AF37', label: 'ذهبي تراثي' },
                    { color: '#264268', label: 'كحلي متوسط' },
                    { color: '#374151', label: 'رمادي داكن للنصوص' },
                    { color: '#059669', label: 'أخضر زمردي' },
                    { color: '#DC2626', label: 'أحمر قرمزي' },
                    { color: '#6B7280', label: 'رمادي متوسط' },
                    { color: '#000000', label: 'أسود' },
                    { color: '#FFFFFF', label: 'أبيض' },
                ],
                columns: 5,
            },
            fontBackgroundColor: {
                colors: [
                    { color: '#FFF6D6', label: 'تظليل ذهبي فاتح' },
                    { color: '#FEF3C7', label: 'تظليل أصفر دافئ' },
                    { color: '#FEE2E2', label: 'تظليل أحمر ناعم' },
                    { color: '#D1FAE5', label: 'تظليل أخضر ناعم' },
                    { color: '#DBEAFE', label: 'تظليل أزرق سماوي' },
                    { color: '#F3F4F6', label: 'تظليل رمادي خفيف' },
                ],
                columns: 3,
            },
            table: {
                contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells',
                    'tableProperties',
                    'tableCellProperties',
                ],
                tableProperties: {
                    borderColors: [
                        { color: '#1A2F4C', label: 'كحلي داكن' },
                        { color: '#E67E22', label: 'عنبري' },
                        { color: '#D4AF37', label: 'ذهبي' },
                        { color: '#e5e7eb', label: 'رمادي فاتح' },
                        { color: '#9ca3af', label: 'رمادي متوسط' },
                    ],
                    backgroundColors: [
                        { color: '#1A2F4C', label: 'كحلي داكن' },
                        { color: '#264268', label: 'كحلي متوسط' },
                        { color: '#E67E22', label: 'عنبري' },
                        { color: '#FFF6D6', label: 'ذهبي فاتح' },
                        { color: '#FEF3C7', label: 'أصفر دافئ' },
                        { color: '#F3F4F6', label: 'رمادي خفيف' },
                        { color: '#FFFFFF', label: 'أبيض' },
                    ],
                },
                tableCellProperties: {
                    borderColors: [
                        { color: '#1A2F4C', label: 'كحلي داكن' },
                        { color: '#E67E22', label: 'عنبري' },
                        { color: '#D4AF37', label: 'ذهبي' },
                        { color: '#e5e7eb', label: 'رمادي فاتح' },
                        { color: '#9ca3af', label: 'رمادي متوسط' },
                    ],
                    backgroundColors: [
                        { color: '#1A2F4C', label: 'كحلي داكن' },
                        { color: '#264268', label: 'كحلي متوسط' },
                        { color: '#E67E22', label: 'عنبري' },
                        { color: '#FFF6D6', label: 'ذهبي فاتح' },
                        { color: '#FEF3C7', label: 'أصفر دافئ' },
                        { color: '#FEE2E2', label: 'أحمر ناعم' },
                        { color: '#D1FAE5', label: 'أخضر ناعم' },
                        { color: '#DBEAFE', label: 'أزرق سماوي' },
                        { color: '#F3F4F6', label: 'رمادي خفيف' },
                        { color: '#FFFFFF', label: 'أبيض' },
                    ],
                },
            },
            image: {
                toolbar: [
                    'imageStyle:inline',
                    'imageStyle:block',
                    'imageStyle:side',
                    '|',
                    'imageStyle:wrapText',
                    'imageStyle:breakText',
                    '|',
                    'toggleImageCaption',
                    'imageTextAlternative',
                    '|',
                    'resizeImage',
                ],
                resizeUnit: '%',
                resizeOptions: [
                    { name: 'resizeImage:original', value: null, label: 'الأصلي' },
                    { name: 'resizeImage:25', value: '25', label: '25%' },
                    { name: 'resizeImage:50', value: '50', label: '50%' },
                    { name: 'resizeImage:75', value: '75', label: '75%' },
                    { name: 'resizeImage:100', value: '100', label: '100%' },
                ],
            },
            htmlSupport: {
                allow: [{ name: /.*/, attributes: true, classes: true, styles: true }],
            },
            language: 'ar',
        })
        .then(editor => {
            // Expose editor instance on the source element and window
            contentEl.ckeditorInstance = editor;
            window.ckeditorInstance = editor;

            // 1. Synchronize data in real time as the user edits or formats
            editor.model.document.on('change:data', () => {
                contentEl.value = editor.getData();
            });

            // 2. Pre-submit synchronization hook on the enclosing form
            const form = contentEl.closest('form');
            if (form) {
                form.addEventListener('submit', () => {
                    contentEl.value = editor.getData();
                }, true);

                // Direct click hook on submit buttons to guarantee synchronization
                const submitBtns = form.querySelectorAll('button[type="submit"], input[type="submit"]');
                submitBtns.forEach(btn => {
                    btn.addEventListener('click', () => {
                        contentEl.value = editor.getData();
                    });
                });
            }
        })
        .catch(err => {
            console.error('CKEditor initialization error:', err);
        });
}

// The bundle is emitted as a deferred ES module by Vite, which can execute
// after DOMContentLoaded has already fired. Initialize immediately when the
// DOM is ready, otherwise wait for DOMContentLoaded.
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initEditor);
} else {
    initEditor();
}
