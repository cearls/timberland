const fs = require('fs');
const crypto = require('crypto');
const path = require('path');
const { execSync } = require('child_process');
const distDir = './theme/assets/dist';

const cleanDist = (directory) => {
  if (fs.existsSync(directory)) {
    fs.readdirSync(directory).forEach((file) => {
      const filePath = path.join(directory, file);
      if (fs.statSync(filePath).isFile()) {
        fs.unlinkSync(filePath);
      }
    });
    console.log(`Cleaned up files in ${directory}`);
  } else {
    console.log(`Directory ${directory} does not exist. Skipping cleanup.`);
  }
};

cleanDist(distDir);

const styles = [
  { input: './theme/assets/styles/main.css', output: './theme/assets/dist/main.css' },
  { input: './theme/assets/styles/editor-style.css', output: './theme/assets/dist/editor-style.css' }
];

styles.forEach(({ input, output }) => {
  execSync(`npx @tailwindcss/cli -i ${input} -o ${output} --minify`, { stdio: 'inherit' });

  const fileContents = fs.readFileSync(output);
  const hash = crypto.createHash('md5').update(fileContents).digest('hex').slice(0, 8);

  const ext = path.extname(output);
  const baseName = path.basename(output, ext);
  const newFileName = `${baseName}.${hash}${ext}`;
  const newFilePath = path.join(path.dirname(output), newFileName);

  fs.renameSync(output, newFilePath);

  console.log(`Build complete. Output file: ${newFileName}`);
});
