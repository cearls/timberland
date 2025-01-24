const fs = require('fs');
const crypto = require('crypto');
const path = require('path');
const { execSync } = require('child_process');
const { cleanDist } = require('./utils');
const { distDir, styles } = require('./config');

// Clean the dist directory
cleanDist(distDir);

// Minify Tailwind CSS and add hashes
styles.forEach(({ input, output }) => {
  // Build and minify CSS
  execSync(`npx @tailwindcss/cli -i ${input} -o ${output} --minify`, { stdio: 'inherit' });

  // Generate a hash for the output file
  const fileContents = fs.readFileSync(output);
  const hash = crypto.createHash('md5').update(fileContents).digest('hex').slice(0, 8);

  // Rename the output file to include the hash
  const ext = path.extname(output);
  const baseName = path.basename(output, ext);
  const newFileName = `${baseName}.${hash}${ext}`;
  const newFilePath = path.join(path.dirname(output), newFileName);

  fs.renameSync(output, newFilePath);

  console.log(`Production build complete. Output file: ${newFileName}`);
});
