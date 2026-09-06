<?php

namespace App\Services;

class HtmlSanitizer
{
    /**
     * Allowed iframe host domains for trusted video embeds.
     *
     * @var array<string>
     */
    protected static array $allowedIframeHosts = [
        'youtube.com',
        'www.youtube.com',
        'youtube-nocookie.com',
        'www.youtube-nocookie.com',
    ];

    /**
     * Clean rich HTML content from CKEditor.
     * Removes malicious execution vectors (scripts, untrusted iframes, on* handlers, javascript: URIs)
     * while preserving inline styles, colors, table structures, and alignment classes.
     *
     * @param  string|null  $html
     * @return string
     */
    public static function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        $clean = $html;

        // 1. Remove dangerous executable/container tags and their contents
        $dangerousTags = ['script', 'style', 'applet', 'object', 'embed', 'form', 'svg', 'math', 'base', 'meta', 'link'];
        foreach ($dangerousTags as $tag) {
            $pattern = '/<\s*' . $tag . '\b[^>]*>(.*?)<\s*\/\s*' . $tag . '\s*>/is';
            while (preg_match($pattern, $clean)) {
                $clean = preg_replace($pattern, '', $clean);
            }
            $selfClosingPattern = '/<\s*' . $tag . '\b[^>]*\/?>/is';
            while (preg_match($selfClosingPattern, $clean)) {
                $clean = preg_replace($selfClosingPattern, '', $clean);
            }
        }

        // 2. Filter <iframe> tags: allow only trusted YouTube and YouTube-nocookie embeds
        $clean = preg_replace_callback('/<\s*iframe\b([^>]*)>(?:.*?<\s*\/\s*iframe\s*>)?/is', function ($matches) {
            $attributesString = $matches[1];

            // Extract src attribute
            if (preg_match('/\bsrc\s*=\s*(["\'])(.*?)\1/i', $attributesString, $srcMatches)) {
                $src = trim($srcMatches[2]);
            } elseif (preg_match('/\bsrc\s*=\s*([^\s>]+)/i', $attributesString, $srcMatches)) {
                $src = trim($srcMatches[1]);
            } else {
                return ''; // Missing src, strip
            }

            // Decode HTML entities to inspect the actual destination
            $decodedSrc = html_entity_decode($src, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $parsed = parse_url($decodedSrc);

            if (!isset($parsed['host']) || !isset($parsed['path'])) {
                return '';
            }

            $host = strtolower($parsed['host']);
            if (!in_array($host, self::$allowedIframeHosts, true)) {
                return ''; // Untrusted domain
            }

            // Path must start with /embed/
            if (!str_starts_with($parsed['path'], '/embed/')) {
                return '';
            }

            // Protocol must be https or http
            if (isset($parsed['scheme']) && !in_array(strtolower($parsed['scheme']), ['https', 'http'], true)) {
                return '';
            }

            // Clean dangerous event attributes or protocols from the trusted iframe tag
            $safeAttrs = self::stripEventAttributes($attributesString);
            $safeAttrs = self::stripJavascriptUris($safeAttrs);

            return '<iframe' . $safeAttrs . '></iframe>';
        }, $clean);

        // 3. Strip all inline event attributes (on*, e.g., onload, onclick, onerror, onmouseover)
        $clean = self::stripEventAttributes($clean);

        // 4. Strip javascript: and vbscript: URIs from href, src, action, and data attributes
        $clean = self::stripJavascriptUris($clean);

        // 5. Clean style attributes from dangerous CSS expressions (expression, behavior, url(javascript:...))
        $clean = self::cleanStyleAttributes($clean);

        return $clean;
    }

    /**
     * Remove inline event handlers (onload, onerror, onclick, etc.) from any tag.
     *
     * @param  string  $html
     * @return string
     */
    protected static function stripEventAttributes(string $html): string
    {
        $pattern = '/\s+on[a-zA-Z]+\s*=\s*(?:(["\'])(?:(?!\1).)*\1|[^\s>]+)/i';
        while (preg_match($pattern, $html)) {
            $html = preg_replace($pattern, '', $html);
        }
        return $html;
    }

    /**
     * Neutralize javascript:, vbscript:, and dangerous data: URIs in link/source attributes.
     *
     * @param  string  $html
     * @return string
     */
    protected static function stripJavascriptUris(string $html): string
    {
        return preg_replace_callback('/(\b(?:href|src|action|data|poster)\s*=\s*)(["\'])(.*?)\2/is', function ($matches) {
            $attrPrefix = $matches[1];
            $quote = $matches[2];
            $val = $matches[3];

            // Normalize and decode entities & control chars
            $normalized = html_entity_decode($val, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $normalized = preg_replace('/[\x00-\x1F\x7F\s]+/u', '', $normalized);

            if (preg_match('/^(?:javascript|vbscript|data(?!\:image\/(?:png|jpeg|webp|gif))):/i', $normalized)) {
                return $attrPrefix . $quote . '#' . $quote;
            }

            return $matches[0];
        }, $html);
    }

    /**
     * Neutralize dangerous CSS expression vectors in style attributes while strictly
     * preserving all formatting (color, background-color, font styles, borders, etc.).
     *
     * @param  string  $html
     * @return string
     */
    protected static function cleanStyleAttributes(string $html): string
    {
        return preg_replace_callback('/(\bstyle\s*=\s*)(["\'])(.*?)\2/is', function ($matches) {
            $attrPrefix = $matches[1];
            $quote = $matches[2];
            $styleContent = $matches[3];

            // Remove legacy expression(...), behavior:..., and url(javascript:...)
            $cleanedStyle = preg_replace('/expression\s*\(.*?\)/is', '', $styleContent);
            $cleanedStyle = preg_replace('/behavior\s*:[^;]*/is', '', $cleanedStyle);
            $cleanedStyle = preg_replace('/url\s*\(\s*["\']?\s*(?:javascript|vbscript|data(?!\:image\/)):[^)]*\)/is', '', $cleanedStyle);

            return $attrPrefix . $quote . $cleanedStyle . $quote;
        }, $html);
    }
}
