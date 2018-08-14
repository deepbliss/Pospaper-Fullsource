
// CodeMirror, copyright (c) by Marijn Haverbeke and others
// Distributed under an MIT license: http://codemirror.net/LICENSE

(function (mod) {
    mod(CodeMirror);
})(function (CodeMirror) {
    "use strict";

    CodeMirror.defineMode("htmlembedded", function (config, parserConfig) {
        var closeComment = parserConfig.closeComment || "--%>"
        return CodeMirror.multiplexingMode(CodeMirror.getMode(config, "htmlmixed"), {
            open: parserConfig.openComment || "<%--",
            close: closeComment,
            delimStyle: "comment",
            mode: {token: function (stream) {
                    stream.skipTo(closeComment) || stream.skipToEnd()
                    return "comment"
                }}
        }, {
            open: parserConfig.open || parserConfig.scriptStartRegex || "<%",
            close: parserConfig.close || parserConfig.scriptEndRegex || "%>",
            mode: CodeMirror.getMode(config, parserConfig.scriptingModeSpec)
        });
    }, "htmlmixed");

    CodeMirror.defineMIME("application/x-ejs", {name: "htmlembedded", scriptingModeSpec: "javascript"});
    CodeMirror.defineMIME("application/x-aspx", {name: "htmlembedded", scriptingModeSpec: "text/x-csharp"});
    CodeMirror.defineMIME("application/x-jsp", {name: "htmlembedded", scriptingModeSpec: "text/x-java"});
    CodeMirror.defineMIME("application/x-erb", {name: "htmlembedded", scriptingModeSpec: "ruby"});
});
