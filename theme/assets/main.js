import './styles/main.css'
import.meta.glob('../blocks/**/*.css', { eager: true })
import Alpine from 'alpinejs'

window.Alpine = Alpine

import.meta.globEager('../blocks/**/*.js')

window.Alpine.start()