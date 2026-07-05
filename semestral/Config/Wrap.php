<?php
declare(strict_types=1);

/**
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '0');
 */

function ASSERT_OR_PANIC(bool $condition, string $message): void
{
    if ($condition) {
        return;
    }

    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

    $caller = $trace[1] ?? $trace[0];

    throw new RuntimeException(
        sprintf(
            "%s (called from %s:%d)",
            $message,
            $caller["file"] ?? "unknown",
            $caller["line"] ?? 0,
        ),
    );
}

function html(?string $text): string
{
    return htmlspecialchars($text ?? "", ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
}

function url(string $text): string
{
    return urlencode($text);
}

function js_html(mixed $value): string
{
    return json_encode(
        $value,
        JSON_THROW_ON_ERROR |
            JSON_HEX_TAG |
            JSON_HEX_AMP |
            JSON_HEX_APOS |
            JSON_HEX_QUOT |
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES,
    );
}

function js_api(mixed $value): string
{
    return json_encode(
        $value,
        JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
    );
}

function js_pretty(mixed $value): string
{
    return json_encode(
        $value,
        JSON_THROW_ON_ERROR |
            JSON_PRETTY_PRINT |
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES,
    );
}

/*
Exampler

<p><?= html($user->name) ?></p>

<a href="/search?q=<?= url($query) ?>">Search</a>

<script>
const user = <?= js($user) ?>;
</script>
*/
