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
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'],
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
