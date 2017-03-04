var compiler;
try {
    compiler = require('less');
} catch(e) {
    process.stderr.write(e.message);
}

var args = process.argv;
if (args[3]) {
    process.chdir(args[3]);
}

compiler.render(args[2],
    function(e, output) {
        if (e) {
            process.stderr.write(e.message);
        } else {
            process.stdout.write(output.css);
        }
    }
);

