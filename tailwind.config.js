export default {
  content: [
    './theme/views/**/*.twig',
    './theme/blocks/**/*.twig',
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
