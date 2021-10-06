<style type="text/css">
@import url('https://fonts.googleapis.com/css?family=Roboto:100,300');

.debug-inphinit {
    text-align: left;
    padding: 10px;
    background-color: #fcfcfc;
    border-radius: 4px;
}
.debug-inphinit, .code-inphinit {
    white-space: normal;
    margin: 15px 15px 25px 15px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.18), 0 1px 2px rgba(0,0,0,0.30);
}
.debug-inphinit h3, .debug-inphinit h4, .code-inphinit-header, .code-inphinit .error {
    font-family: 'Roboto', 'Helvetica Neue', Helvetica, Arial, freesans, sans-serif;
}
.debug-inphinit .debug-inphinit {
    background-color: #F7F6F6;
    color: #716a6a;
    margin: 5px 0 15px 0;
    box-shadow: none;
}
.code-inphinit {
    text-align: left;
    background-color: #282C35;
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
    background-color: #ed591a;
    font-size: 10pt;
    padding: 10px;
    color: #fff;
}
.code-inphinit .error {
    background-color: #fcfcfc;
    font-weight: bold;
    color: #282C35;
    padding: 10px;
    margin: 0;
}
.code-inphinit .error > a {
    color: #5f5f8c;
    text-decoration: none;
    border-bottom: 1px #3b4045 dotted;
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
    border-right: 1px solid #414856;
    margin-right: 10px;
    color: #888;
    text-align: right;
    padding-right: 10px;
}

.code-inphinit pre span::before {
    counter-increment: line;
    content: counter(line);
    display: inline-block;
    min-width: 22px;
    border-right: 1px solid #414856;
    margin-right: 10px;
    color: #888;
    text-align: right;
    padding-right: 10px;
}
.code-inphinit .hl-line:before {
    border-right-color: #777e8e;
}
.code-inphinit .hl-line::before {
    border-right-color: #777e8e;
}
</style>
