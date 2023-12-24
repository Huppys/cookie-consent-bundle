import {globSync} from "glob";
import fs from "fs";
import autoprefixer from "autoprefixer";
import * as sass from "sass";
import postcss from "postcss";
import path from "path";

// read all scss files
const scssFiles = globSync('Resources/assets/scss/*.scss', { ignore: 'node_modules/**' });

scssFiles.forEach((file) => {
    // transform sass to css
    const result = sass.compile(file, {style: 'compressed'});

    // get filename without extension
    const filename = path.parse(file).name;

    // early return if no css is available
    if (!result.css) {
        return;
    }

    // handle css by postcss and autoprefixer
    postcss([autoprefixer])
        .process(result.css, { from: `Resources/public/css/${filename}.css`, to: `Resources/public/css/${filename}.min.css` })
        .then(result => {
            // write css file to file system
            fs.writeFileSync(`Resources/public/css/${filename}.min.css`, result.css);
        });
});
