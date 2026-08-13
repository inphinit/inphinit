<style type="text/css">
.debug-inphinit {
    text-align: left;
    padding: 10px;
    background-color: rgba(0,0,0,.6);
    border-radius: 4px;
}
.debug-inphinit, .code-inphinit {
    white-space: normal;
    margin: 15px 15px 25px 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 1px 2px rgba(0,0,0,.24);
}
.debug-inphinit h3, .debug-inphinit h4, .code-inphinit-header, .code-inphinit-error {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
}
.debug-inphinit .debug-inphinit {
    background-color: #F7F6F6;
    color: #716a6a;
    margin: 5px 0 15px 0;
    box-shadow: none;
}
.code-inphinit {
    text-align: left;
    background-color: #1b1820;
    border-radius: 4px;
    overflow: hidden;
    color: #dfe0e0;
}
.code-inphinit .hl-line {
    background-color: #656565;
    border-radius: 2px;
    padding: 5px 0;
    margin: 5px 0;
    color: #fff;
    width: 100%;
}
.code-inphinit-header {
    background-color: #b60000;
    font-size: 10pt;
    padding: 10px;
    color: #fff;
}
.code-inphinit-header a {
    color: inherit !important;
    text-decoration: none !important;
    border-bottom: thin currentColor dotted !important;
    background-color: transparent !important;
}
.code-inphinit .code-inphinit-error {
    background-color: #252631;
    color: #fff;
    font-weight: bold;
    padding: 10px;
    margin: 0;

    box-shadow: 0 5px 3px -3px rgba(0,0,0,.2);
    border-bottom: thin solid #363744;
}
.code-inphinit .code-inphinit-error > a {
    color: inherit;
    text-decoration: none;
    border-bottom: thin #fff dotted;
}
.code-inphinit pre {
    line-height: 24px;
    padding: 5px;
    font-size: 9pt;
    overflow: auto;
    margin: 0;
}
.code-inphinit pre > span {
    display: inline-block;
}

.code-inphinit pre span:before {
    counter-increment: line;
    content: counter(line);
    display: inline-block;
    min-width: 22px;
    border-right: thin solid #363744;
    margin-right: 10px;
    color: #888;
    text-align: right;
    padding-right: 10px;
}

.code-inphinit pre span::before {
    counter-increment: line;
    content: counter(line);
    display: inline-block;
    min-width: 35px;
    border-right: thin solid #363744;
    margin-right: 10px;
    color: #888;
    text-align: right;
    padding-right: 10px;
}
.code-inphinit .hl-line:before {
    border-right-color: #9297a2;
}
.code-inphinit .hl-line::before {
    border-right-color: #9297a2;
    color: #fff;
}
</style>
