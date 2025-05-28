import typography from '@tailwindcss/typography'

export default {
  content: [
    './theme/views/**/*.twig',
    './theme/blocks/**/*.twig',
  ],
  theme: { extend: {} },
  plugins: [
    typography,
  ],
}
