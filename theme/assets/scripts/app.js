import "alpinejs";

// Import js for componenents
function importAll(r) {
  r.keys().forEach(r);
}

importAll(require.context("../../components/", true, /\/script\.js$/));
