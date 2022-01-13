import Alpine from 'alpinejs'

window.Alpine = Alpine

// Import js for componenents
function importAll(r) {
  r.keys().forEach(r)
}

importAll(require.context("../../blocks/", true, /\/script\.js$/))

window.Alpine.start()
