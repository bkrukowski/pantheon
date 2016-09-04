const
    requireDir = require('require-dir'),
    figlet = require('figlet');

console.log(figlet.textSync('Pantheon Gulp'));
requireDir('gulp');