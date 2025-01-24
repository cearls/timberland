const fs = require('fs');
const path = require('path');

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

module.exports = { cleanDist };
