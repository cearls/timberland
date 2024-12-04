const fs = require('fs');
const crypto = require('crypto');
const path = require('path');
const { execSync } = require('child_process');

const styles = [
  { input: './theme/assets/styles/main.css', output: './theme/assets/dist/main.css' },
  { input: './theme/assets/styles/editor-style.css', output: './theme/assets/dist/editor-style.css' }
];

styles.forEach(({ input, output }) => {
  // Compile Tailwind CSS
  execSync(`npx tailwindcss -i ${input} -o ${output} --minify`, { stdio: 'inherit' });

  // Generate hash for the CSS file
  const fileContents = fs.readFileSync(output);
  const hash = crypto.createHash('md5').update(fileContents).digest('hex').slice(0, 8);

  // Rename the file with the hash
  const ext = path.extname(output);
  const baseName = path.basename(output, ext);
  const newFileName = `${baseName}.${hash}${ext}`;
  const newFilePath = path.join(path.dirname(output), newFileName);

  fs.renameSync(output, newFilePath);

  console.log(`Build complete. Output file: ${newFileName}`);
});
