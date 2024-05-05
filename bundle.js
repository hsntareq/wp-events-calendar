import { execSync } from 'child_process';

const outputFileName = `events-calendar.zip`;
const excludes = `.DS_Store */.DS_Store */*/.DS_Store mix-manifest.json .git .gitattributes .github .editorconfig .gitignore .php-cs-fixer.cache gulpfile.js composer.lock node_modules package-lock.json webpack.mix.js src yarn.lock bundle.js phpcs.xml events-calendar.zip`;

const cmd = `dir-archiver --src . --dest ./${outputFileName} --exclude ${excludes}`;
try {
	execSync(cmd);
	console.log(`Created zip file: ${outputFileName}`);
} catch (error) {
	console.error('Error creating zip file:', error);
	process.exit(1);
}
