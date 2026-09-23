<?php
return array(
    /*
     * Defines which external assistant service should be used when debugging errors.
     *
     * Accepted values:
     *   - 'chatgpt'     → OpenAI ChatGPT
     *   - 'claude'      → Anthropic Claude
     *   - 'claude.code' → Claude Code
     *   - 'codex'       → OpenAI Codex
     *   - 'cursor'      → Cursor
     *   - 'duck.ai'     → DuckDuckGo AI
     *   - 'duckduckgo'  → DuckDuckGo Search
     *   - 'google'      → Google Search
     *   - 'google.ai'   → Google AI
     *   - 'perplexity'  → Perplexity AI
     *
     * You can also specify a custom URL containing the placeholder {error},
     * which will be replaced by the actual error message.
     * Example: 'https://example.foo/?q={error}'
     */
    'assistant' => 'duckduckgo',

    /*
     * Defines which code editor should open when clicking on file links in error pages.
     *
     * Accepted values:
     *   - 'vscode'      → Visual Studio Code
     *   - 'sublimetext' → Sublime Text
     *
     * Note: For Sublime Text, the "subl protocol" package is required:
     *   https://packagecontrol.io/packages/subl%20protocol
     */
    'editor' => 'vscode',
);

