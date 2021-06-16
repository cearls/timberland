module.exports = {
  mode: 'jit',
  purge: [
    './theme/views/**/*.twig',
    './theme/components/**/*.twig',
  ],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
